<?php
namespace Text\Analysis;

/**
 * Report summary stats on the keyword hash table that is outputted from
 * the Keyword Density Class
 *
 * @author yooper
 */
class KeywordAggregation {
    
    /**
     * The keyword density hash table that was passed in
     * @var array 
     */
    protected $_keywordDensity = null;
        
    public function __construct(array $keywordDensity){
        $this->_keywordDensity = $keywordDensity;
    }
    
    
    public function getSortedByWordLength($wordCountIndex){ 
        $func = function($word) {
            return strlen($word);
        };

        $lengths = array_map($func, array_keys($this->_keywordDensity[$wordCountIndex]));            
        $data = array_combine(array_keys($this->_keywordDensity[$wordCountIndex]), $lengths);      
        arsort($data, SORT_NUMERIC);
        return $data;
    }
    
    
    /**
     * Get the number of unique words for the word
     * count size specified
     * @param integer $wordCount
     * @return integer 
     */
    public function getSortedWordCountFrequency($wordCountIndex){ 
        
        if(!isset($this->_keywordDensity[$wordCountIndex])) {
            return 0;
        }
        arsort($this->_keywordDensity[$wordCountIndex], SORT_NUMERIC);
        return $this->_keywordDensity[$wordCountIndex];
    }
}


