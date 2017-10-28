<?php

namespace TextAnalysis\NGrams;

/**
 * Generate ngrams
 *
 * @author yooper <yooper>
 */
class NGramFactory
{
    const BIGRAM = 2;
    const TRIGRAM = 3;
    
    /**
     * Protect the constructor
     */
    protected function __construct(){}
    
    /**
     * Generate Ngrams from the tokens
     * @param array $tokens
     * @param int $nGramSize
     * @return  array return an array of the ngrams
     */
    static public function create(array $tokens, $nGramSize = self::BIGRAM, $separator = ' ') : array
    {
        $separatorLength = strlen($separator);
        $length = count($tokens) - $nGramSize + 1;
        if($length < 1) {
            return [];
        }
        $ngrams = array_fill(0, $length, ''); // initialize the array
        
        for($index = 0; $index < $length; $index++)
        {
            for($jindex = 0; $jindex < $nGramSize; $jindex++)
            {
                $ngrams[$index] .= $tokens[$index + $jindex]; 
                if($jindex < $nGramSize - $separatorLength) {
                    $ngrams[$index] .= $separator;
                }
            }
        }
        return $ngrams;
    }
}
