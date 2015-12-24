<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\IDistance;

/** 
 * Implementation of hamming distance using PHP's native function 
 * 
 * @author yooper <yooper>
 */
class HammingDistanceComparison implements IDistance
{        
    /**
     * Return the hamming distance, expects the strings to be equal length
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function distance($text1, $text2)
    {
        $distance = 0;
        $strLength = strlen($text1);
        for($index = 0; $index < $strLength; $index++)
        {
            if($text1[$index] != $text2[$index]) { 
                $distance++;
            }
        }
        return $distance;
    }
}
