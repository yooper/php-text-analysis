<?php
namespace TextAnalysis\Aggregates;

/**
 * Generate a bigram from the tokens
 * @author yooper
 */
class BigramAggregate 
{    
    static public $splitBigramChar = ' ';
    /**
     * All the tokens
     * @var array 
     */
    protected $tokens;
        
    public function __construct(array $tokens) 
    {
        $this->tokens = $tokens;
    }
  
    /**
     * Returns an array iterator with all the bigrams 
     * @return array
     */
    public function getAggregateTokens()
    { 
        $bigrams = array();
        
        $sizeOf = count($this->tokens);
        
        for($index = 1; $index < $sizeOf; $index++) { 
            $bigrams [] = array(
                $this->tokens[$index-1],
                $this->tokens[$index]
            );
        }
        return $bigrams;
    }
    
    /**
     * Return an array of strings
     * @return array 
     */
    public function getAggregateStrings()
    {
        $bigrams = array();
        
        $sizeOf = count($this->tokens);
        
        for($index = 1; $index < $sizeOf; $index++) { 
            $bigrams [] = array(
                $this->tokens[$index-1].self::$splitBigramChar.$this->tokens[$index]
            );
        }
        return $bigrams;
    }
    
}

