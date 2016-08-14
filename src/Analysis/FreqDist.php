<?php
namespace TextAnalysis\Analysis;

use TextAnalysis\Exceptions\InvalidParameterSizeException;

/**
 * Extract the Frequency distribution of keywords
 * @author yooper
 */
class FreqDist 
{
      
    /**
     * An associative array that holds all the frequencies per token
     * @var array 
     */
    protected $keyValues = array();
    
    /**
     * The total number of tokens originally passed into FreqDist
     * @var int  
     */
    protected $totalTokens = null;
    
    /**
     * Caches a copy of keys by weight
     * @var array
     */
    protected $keysByWeight = [];
    
    /**
     * This sorts the token meta data collection right away so use 
     * frequency distribution data can be extracted.
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {  
        $this->preCompute($tokens);
        $this->totalTokens = count($tokens);        
        if($this->totalTokens === 0) { 
            throw new InvalidParameterSizeException("The tokens array must not be empty");
        }
    }
     
    /**
     * Get the total number of tokens in this tokensDocument
     * @return int 
     */
    public function getTotalTokens()
    {
        return $this->totalTokens;
    }
    
    /**
     * Internal function for summarizing all the data into a key value store
     * @param array $tokens The set of tokens passed into the constructor
     */
    protected function preCompute(array $tokens)
    {
        //count all the tokens up and put them in a key value store
        $this->keyValues = array_count_values($tokens);
        arsort($this->keyValues);        
    } 
    

    /**
     * Return the weight of a single token
     * @return float 
     */
    public function getWeightPerToken()
    {
        return 1 / $this->getTotalTokens();
    }
    
    /**
     * Return get the total number of unique tokens
     * @return int
     */
    public function getTotalUniqueTokens()
    {
        return count($this->keyValues);
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
     * Return an array with key frequency pairs
     * @return array 
     */
    public function getKeyValuesByFrequency()
    {
        return $this->keyValues;
    }
    
    /**
     * Return an array with key weight pairs
     * @return array 
     */
    public function getKeyValuesByWeight()
    {
        if(empty($this->keysByWeight)) {
            $weightPerToken = $this->getWeightPerToken();
            //make a copy of the array
            $keyValuesByWeight = $this->keyValues;
            array_walk($keyValuesByWeight, function(&$value, $key, $weightPerToken) {
                $value /= $weightPerToken;
            }, $this->totalTokens);
            
            $this->keysByWeight = $keyValuesByWeight;
        }
        return $this->keysByWeight;
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
            
        //get the head key
        $head = key($this->keyValues);
            
        //get the tail value,. set the internal pointer to the tail
        $tail = end($this->keyValues);
        // no hapaxes available
        if($tail > 1) { 
            return array();
        }
            
        do {
            $hapaxes[] = key($this->keyValues);
            prev($this->keyValues);
                
        } while(current($this->keyValues) == 1 && key($this->keyValues) !== $head);
            
        //reset the internal pointer in the array
        reset($this->keyValues);
        return $hapaxes; 
    }
    
    public function __destruct() 
    {
        unset($this->keyValues);
        unset($this->totalTokens);
        unset($this->keysByWeight);
    }
    
}
