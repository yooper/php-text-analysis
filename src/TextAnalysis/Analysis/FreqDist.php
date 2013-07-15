<?php
namespace TextAnalysis\Analysis;

use TextAnalysis\Aggregates\TokenMetaAggregator;
use \ArrayIterator;
use TextAnalysis\Token;

/**
 * Extract the Frequency distribution of keywords
 * @author yooper
 */
class FreqDist 
{
    /**
     * Stores the ArrayIterator provided by the tokenMetaAggregation Class
     * @var \ArrayIterator 
     */
    protected $tokenMetaCollection = null;
    
    /**
     * The total number of tokens and times they occur
     * @var int 
     */
    protected $totalTokens = 0;
    
    /**
     * An associative array that holds all the weights per token
     * @var array 
     */
    protected $keyValues = array();
    
    /**
     * This sorts the token meta data collection right away so use 
     * frequency distribution data can be extracted.
     * @param \ArrayIterator $tokenMetaCollection 
     */
    public function __construct(\ArrayIterator $tokenMetaCollection)
    {
        $this->tokenMetaCollection = $tokenMetaCollection;        
        $this->preCompute();
    }
     
    /**
     * Get the total number of tokens and number of times they occur
     * @return int 
     */
    public function getTotalTokens()
    {
        return $this->totalTokens;
    }
    
    /**
     * Internal function for summarizing all the data 
     */
    public function preCompute()
    {
        /** @var Token $token */
        foreach($this->tokenMetaCollection as $token){
            $this->totalTokens += $token->getPositionCount();
            $this->keyValues[$token->getWord()] = $token->getPositionCount();
        }
        
        $fractionOfWhole = 1 / $this->totalTokens;
        
        //compute all the weights
        foreach($this->tokenMetaCollection as $token){
            $this->keyValues[$token->getWord()] *= $fractionOfWhole;
        }        
        arsort($this->keyValues);        
    } 
    
    /**
     * Return get the total number of unique tokens
     * @return int
     */
    public function getTotalUniqueTokens()
    {
        return $this->tokenMetaCollection->count();
    }
    
    /**
     * Return the sorted keys by frequency desc
     * @return array 
     */
    public function getKeys()
    {
        return array_keys($this->keyValues);
    }
    
    /**
     * Return the sorted values by frequency desc
     * @return array 
     */
    public function getValues()
    {
        return array_values($this->keyValues);
    }
    
    /**
     *
     * @return array 
     */
    public function getKeyValues()
    {
        return $this->keyValues;
    }
    
    /**
     * 
     * Returns an array of tokens that occurred once 
     * @todo This is an inefficient approach
     * @return array
     */
    public function getHapaxes()
    {
        $hapaxes = array();
        /** var $token Token */
        foreach($this->tokenMetaCollection as $token) {
           if(count($token->getPositions()) === 1) {
               $hapaxes[] = $token->getWord();
           } 
        }
        return $hapaxes; 
    }
    
}

