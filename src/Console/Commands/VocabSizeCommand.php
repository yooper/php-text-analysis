<?php

namespace TextAnalysis\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\QuotesFilter;
use TextAnalysis\Filters\PossessiveNounFilter;


/**
 * Get the vocab size from stdin 
 * @author yooper
 */
class VocabSizeCommand extends Command
{
    /**
     *
     * @var array
     */
    protected $filters = [];
    
    protected function configure()
    {
        $this->setName('vocab:size')
            ->setDescription('Process stdin and return the vocab size');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {                
        if (ftell(STDIN) === 0) {
            $contents = '';
            while (!feof(STDIN)) {
                $contents .= fread(STDIN, 4096);
            }            
            // filtered tokens
            $tokens = array_map([$this,'filter'], (new GeneralTokenizer())->tokenize($contents));
            $tokens = array_values( array_filter($tokens));
                        
            $ct =  (new FreqDist($tokens))->getTotalUniqueTokens(); 
            echo $ct.PHP_EOL;
            return $ct;
            
        } else {
            throw new \RuntimeException("Please pipe in STDIN");
        }
        return 0;
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
    }        
}
