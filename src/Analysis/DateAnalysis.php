<?php

namespace TextAnalysis\Analysis;

use TextAnalysis\Tokenizers\SentenceTokenizer;
use TextAnalysis\Utilities\Text;
use DateTime;

/**
 * Extract dates from the given text.
 * @author dcardin
 */
class DateAnalysis 
{  
    /**
     * An array of strings
     * @var array
     */
    protected $sentences;
    
    /**
     *
     * @var array cache of dates found
     */
    protected $dates = [];
    
    /**
     * 
     * @param type $text
     */
    public function __construct(string $text) 
    {
        $tokenizer = new SentenceTokenizer();
        $this->sentences = $tokenizer->tokenize( $this->normalize($text)) ;        
    }
    
    /**
     * Remove any periods from abbreviated month names
     * ie Mar. to March
     * @param string $text
     */
    protected function normalize(string $text) : string
    {
        $search = ['jan.','feb.','mar.','apr.','may.','jun.','jul.','aug.','sep.','oct.','nov.','dec.'];
        $replace = [
            "january",
            "february",
            "march",
            "april",
            "may",
            "june",
            "july",
            "august",
            "september",
            "october",
            "november",
            "december"
        ];        
        return str_ireplace($search, $replace, $text);
    }
    
    /**
     * @return DateTime[]
     */
    public function getDates() : array
    {        
        // return the cached copy
        if(empty($this->dates)) {
            $getDateFunc = function($sentence) 
            { 
                $date = Text::findDate($sentence);
                return new DateTime("{$date['year']}-{$date['month']}-{$date['day']}");
            };
            
            $this->dates = array_map($getDateFunc, $this->sentences);
         
            // re-index so nulls and offsets are correct. 
            $this->dates = array_values(array_filter($this->dates));                      
        }                  
        return $this->dates;
    }        
}
