<?php

namespace TextAnalysis\Corpus;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\LexicalDiversity\Naive;

/**
 * Explore the text corpus
 * @author yooper
 */
class TextCorpus 
{
    /**
     *
     * @var string
     */
    protected $text;
    
    /**
     *
     * @var array
     */
    protected $tokens = [];
    
    public function __construct(string $text) 
    {
        $this->text = $text;
    }
    
    /**
     * Returns the original text
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }
    
    public function getTokens(string $tokenizerClassName = GeneralTokenizer::class) : array
    {
        if(empty($this->tokens)) {            
            $this->tokens = tokenize($this->getText(), $tokenizerClassName);
        }
        return $this->tokens;
    }
    
    /**
     * Return a list of positions that the needs were found in the text
     * @param array $needles
     * @return int[]
     */
    public function getDispersion(array $needles) : array
    {
        $found = array_fill_keys($needles, []);
        foreach(array_keys($needles) as $needle)
        {
            $found[$needle] = $this->findAll($needle);
        }
        return $found;
    }
    
    /**
     * Compute the lexical diversity, the default uses a naive algorithm
     * @param string $lexicalDiversityClassName
     * @return float
     */
    public function getLexicalDiversity(string $lexicalDiversityClassName = Naive::class) : float
    {
        return lexical_diversity($this->getTokens(), $lexicalDiversityClassName);
    }
    
    /**
     * See https://stackoverflow.com/questions/15737408/php-find-all-occurrences-of-a-substring-in-a-string
     * @param string $needle
     * @param int $spacing The amount of space left and right of the found needle
     * @return array
     */
    public function concordance(string $needle, int $spacing = 20) : array
    {
        $position = 0;
        $found = [];
        $text = trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $this->text));
        $needleLength = strlen($needle);
        $textLength = strlen($text);
        $bufferLength = $needleLength + 2 * $spacing;
                        
        while (($position = stripos($text, $needle, $position))!== false) 
        {
            $left = max($position - $spacing, 0);                        
            if($needleLength + $spacing + $position > $textLength) {
                $tmp = substr($text, $left); 
            } else { 
                $tmp = substr($text, $left, $bufferLength);
            }            
            $found[] = $tmp;
            $position += $needleLength;
        }
        return $found;
    }
    
    /**
     * Get percentage of times the needle shows up in the text
     * @param string $needle
     * @return float
     */
    public function percentage(string $needle) : float
    {
        $freqDist = freq_dist($this->getTokens());
        return $freqDist->getKeyValuesByFrequency()[$needle] / $freqDist->getTotalTokens();
    }
    
    /**
     * Performs a case insensitive search for the needle
     * @param string $needle
     * @return int
     */
    public function count(string $needle) : int
    {
        return substr_count(strtolower($this->getText()), strtolower($needle));
    }
    
    /**
     * Return all the position of the needle found in the text
     * @param string $needle
     * @return array
     */
    public function findAll(string $needle) : array
    {
        $lastPos = 0;
        $positions = [];
        $needle = strtolower($needle);
        $text = strtolower($this->getText());
        $needleLength = strlen($needle);
        while (($lastPos = stripos($text, $needle, $lastPos))!== false) 
        {
            $positions[] = $lastPos;
            $lastPos += $needleLength;
        }
        return $positions;
    }
    public function toString()
    {
        return $this->text;
    }
    
    public function __destruct() 
    {
        unset($this->text);
        unset($this->tokens);
    }
}
