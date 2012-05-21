<?php
namespace Text\Analysis;
/**
 * @author yooper
 * 
 */
class KeywordDensity {

    /**
     *
     * @var array 
     */
    protected $_wordDensityHashTable = array();
    /**
     * @var string  
     */
    protected $_splitPattern = "/[\s,\+]+/";
    
    /**
     *
     * @var array 
     */
    protected $_stopWords = array();
    
    
    protected $_trimCharacters = " \t\r\n.!?:\x0B\0";

    /**
     * Does nothing 
     */
    public function __construct(){}
 
    /**
     * Absolute path to a stop words file that has a stop work on each line
     * @param string $pathToStopWords 
     */
    public function loadStopWordsText($pathToStopWords) {
       $this->_stopWords = explode("\n", file_get_contents($pathToStopWords));
    }

    /**
     * Set the characters that should always be trimmed from a word
     * @param string $trimCharacters 
     */
    public function setTrimCharacters($trimCharacters){
       $this->_trimCharacters = $trimCharacters;
    }

    /**
     * Split a string into tokens using the pattern splitter
     * @param string $string
     * @return array 
     */
    protected function tokenize($string){
       return preg_split($this->_splitPattern, strtolower($string));
    }

    /**
     * Returns an array the key is the word(s) the value is the frequency
     * @return array 
     */
    public function getKeyWordDensityTable(){
       return $this->_wordDensityHashTable;
    }

    /**
     * Clear the keyword density table 
     */
    public function clearKeyWordDensityTable(){
       unset($this->_wordDensityHashTable);
       $this->_wordDensityHashTable = array();
    }

    /**
     * Set the REGEX split pattern string
     * @param string $pattern 
     */
    public function setSplitPattern($pattern){
       $this->_splitPattern = $pattern;
    }

    /**
     * Iterate through the array of words that and make an n-gram lookup table
     * @param array $arrayOfWords
     * @param integer $maxWords 
     */
    protected function _keyWordDensityAnalysis($arrayOfWords = array(), $maxWords = 2){

       for($start = 0; $start < $maxWords; $start++){

          if(!isset($this->_wordDensityHashTable[$start])){
             $this->_wordDensityHashTable[$start] = array();
          }

          for($index = $start; $index < count($arrayOfWords)+$start; $index++){

             $words = array_slice($arrayOfWords,$index - $start, $start+1);

             if(count($words) < $start+1){
                continue;
             }

             $phrase = trim( implode(" ",$words), $this->_trimCharacters);

             if(empty($this->_wordDensityHashTable[$start][$phrase])){
                $this->_wordDensityHashTable[$start][$phrase] = 1;
             }
             else{
                 $this->_wordDensityHashTable[$start][$phrase]++;
             }
         }
       }
    }

    /**
     * Analyze the text passed in
     * @param type $string
     * @param type $maxWords 
     */
    public function analyzeString($string, $maxWords){

       $this->_keyWordDensityAnalysis( $this->_filterStopWords( $this->tokenize( $string ) ), $maxWords);
    }

    /**
     * Add stop words to the stop words lookup
     * @param type $stopWords 
     */
    public function addStopWords(array $stopWords){

       if(is_array($stopWords)){
           $this->_stopWords = array_unique(array_merge($stopWords, $this->_stopWords));
       }

    }

    /**
     * Remove all stop words from the text
     * @param array $arrayOfWords
     * @return array 
     */
    protected function _filterStopWords(&$arrayOfWords){
       $nonStopWords = array();
       for($index = 0; $index < count($arrayOfWords); $index++){

           if(!$this->_isStopWord($arrayOfWords[$index])){
              $nonStopWords[] = $arrayOfWords[$index];
           }
       }

       unset($arrayOfWords);
       return $nonStopWords;
    }

    /**
     * Get the list of stop words that are loaded into memory
     * @return array 
     */
    public function getStopWords(){
       return $this->_stopWords;
    }

    /**
     * Check if word is a stop word
     * @param string $word
     * @return boolean
     */
    protected function _isStopWord($word){
       return in_array($word,$this->_stopWords);
    }

}

