<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;

/**
 * Wrapper for PHP's similar_text function
 * @author yooper (yooper)
 */
class SimilarTextComparison implements ISimilarity, IDistance
{
    /**
     * Return the distance
     * @param string $text1
     * @param string $text2
     * @return type
     */
    public function distance($text1, $text2) 
    {
        return strlen($text2) - $this->similarity($text1, $text2);
    }

    /**
     * Returns similar_text call
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function similarity($text1, $text2) 
    {
        return similar_text($text1, $text2);
    }

}
