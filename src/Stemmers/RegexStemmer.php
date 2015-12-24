<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;
/**
 * Implements RegexStemmer
 *
 * @author yooper
 */
class RegexStemmer implements IStemmer
{
    protected $minimumTokenLength = null;
    protected $regexExpression = null;
    
    public function __construct($regexExpression, $minimumTokenLength = 4)
    {
        $this->regexExpression = $regexExpression;
        $this->minimumTokenLength = $minimumTokenLength;
    }
    
    /**
     * Return a stemmed word
     * @param string $token
     * @return string
     */
    public function stem($token) 
    {
        if(strlen($token) < $this->minimumTokenLength) {
            return $token;
        }        
        return preg_replace("/".$this->regexExpression."/i", '', $token);        
    }
}
