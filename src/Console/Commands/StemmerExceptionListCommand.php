<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Stemmers\DictionaryStemmer;
use TextAnalysis\Adapters\PspellAdapter;
use TextAnalysis\Stemmers\SnowballStemmer;
use TextAnalysis\Comparisons\LevenshteinComparison;
use TextAnalysis\Filters\QuotesFilter;
use TextAnalysis\Filters\PossessiveNounFilter;

/**
 * Create a list of words that might need to be mapped manually
 * from the command line
 * cat tests/data/books/tom_sawyer.txt | php textconsole stemmer:exceptions
 * @author yooper
 */
class StemmerExceptionListCommand extends Command
{
    /**
     *
     * @var array
     */
    protected $filters = [];
    
    protected function configure()
    {
        $this->setName('stemmer:exceptions')
            ->setDescription('Process stdin and return a list of words that could be stemmed correctly');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {                
        
        if (ftell(STDIN) === 0) {
            $contents = '';
            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 1024);
            }            
            // filtered tokens
            $tokens = array_map([$this,'filter'], (new GeneralTokenizer())->tokenize($contents));            
            $tokens = array_unique($tokens);
            
            // stem the tokens
            $stemmer = new DictionaryStemmer(new PspellAdapter(), new SnowballStemmer());            
            $stemmedTokens = array_map(function($token) use ($stemmer) { return $stemmer->stem($token); }, $tokens );
            // use a dictionary to catch all stemmed words that must be fixed or ignored in this data set
            
            $stemmedTokens = array_map('mb_strtolower', $stemmedTokens);
            $comparison = new LevenshteinComparison();
            for($index = 0; $index < count($tokens); $index++)
            {
                // the stemmed word is not a word in the dictionary. The original token
                // will need to be manually mapped
                
                if(isset($stemmedTokens[$index]) && isset($tokens[$index]) &&
                        $comparison->distance($tokens[$index], $stemmedTokens[$index]) >= 4 ){  
                    echo "$tokens[$index],$stemmedTokens[$index]".PHP_EOL;

                    
                }
            }
                        
        } else {
            throw new \RuntimeException("Please pipe in STDIN");
        }
    }    
    
    protected function filter($token)
    {
        foreach($this->getFilters() as $filter)
        {
            $token = $filter->transform($token);
        }
        return $token;
    }
    
    /**
     * 
     * @return array
     */
    protected function getFilters()
    {
        if(empty($this->filters)) {
            $this->filters = [
                new PossessiveNounFilter(),
                new QuotesFilter(['"','`']),
                new LowerCaseFilter(),
                new PunctuationFilter(),
                new CharFilter()
            ];
        }
        return $this->filters;
    }}
