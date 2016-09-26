<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\IDistance;
use TextAnalysis\Interfaces\ISimilarity;

/**
 * Implements cosine similarity algorithm for comparing two sets of arrays
 *
 * @author yooper
 */
class CosineSimilarityComparison implements IDistance, ISimilarity 
{
    /**
     * 
     * @param array $text1 an array of tokens
     * @param array $text2 an array of tokens
     */
    public function similarity($text1, $text2) 
    {
        $text1Freq = array_count_values($text1);
        $text2Freq = array_count_values($text2);
        $product = 0.0;
               
        // always choose the smaller document
        if(count($text1Freq) < count($text2Freq)) {
            $iterateTokens =& $text1Freq;
        } else {
            $iterateTokens =& $text2Freq;
        }
        
        foreach($iterateTokens as $term => $freq)
        {
            if (isset($text1Freq[$term]) && isset($text2Freq[$term])) {
                $product += $text1Freq[$term] * $text2Freq[$term];
            }                     
        }
        
        $productFunc = function($carry, $freq) 
        {
            $carry += pow($freq, 2);
            return $carry;
        };
           
        $text1VectorSum = sqrt(array_reduce(array_values($text1Freq), $productFunc, 0));
        $text2VectorSum = sqrt(array_reduce(array_values($text2Freq), $productFunc, 0));
        return $product / ($text1VectorSum * $text2VectorSum);
        
    }

    /**
     * 
     * @param array $text1
     * @param array $text2
     * @return float
     */
    public function distance($text1, $text2) 
    {
        return 1 - $this->similarity($text1, $text2);
    }

}
