<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;

/**
 * Implents the Jaccard Index algorithm
 * @author yooper <yooper>
 */
class JaccardIndexComparison implements ISimilarity
{
    /**
     * Returns the Jaccard Index
     * @param string|array $text1
     * @param string|array $text2
     * @return float
     */
    public function similarity($text1, $text2) 
    {
        if(is_string($text1) && is_string($text2)) {
            $text1 = str_split($text1);
            $text2 = str_split($text2);
        }         
        $inter = array_intersect( $text1, $text2 );
        $union = array_unique( ($text1 + $text2) );
        return count($inter) / count($union);                
    }
}
