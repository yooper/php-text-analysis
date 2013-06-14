<?php

namespace TextAnalysis\Analysis;

/**
 * Look up a set of words and get the context in which they are used. 
 * @author dcardin
 */
class Concordance 
{
    /**
     * The corpus string
     * @var string 
     */
    protected $text = null;
   
    protected $indexes = array();
    
    protected $caseSensitive;
    
    public function __construct($text, $caseSensitive = true)
    {
        $this->caseSensitive = $caseSensitive ;
        if($caseSensitive){
            $this->text = $text;
        } else {
            $this->text = strtolower($text);
        }
    }
    
    /**
     * Add strings to look for
     * @param string $searchFor 
     */
    public function addSearchText($searchFor)
    {
        $this->indexes[$searchFor] = array();
        return $this;
    }
        
    /**
     * Return an array of indexes with the new keys
     * @return Concordance 
     */
    public function execute()
    {
        $keys = array_keys($this->indexes);
        $offset = 0;
        foreach($keys as $key) {
            if(!$this->caseSensitive) { 
                $searchKey = strtolower($key);
            } else {
                $searchKey = $key;
            }
            $offset = 0;
            while(($offset = strpos($this->text, $searchKey, $offset)) !== false){
                $this->indexes[$key][] = $offset++;
            }
            
        }
        return $this;
    }
    
    public function getResults()
    {
        return $this->indexes;
    }
    
    
}
