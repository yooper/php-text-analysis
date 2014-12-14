<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Utilities\String;
use TextAnalysis\Interfaces\IDistance;

/** 
 * Implementation of hamming distance using PHP's native function 
 * 
 * @author Dan Cardin <yooper>
 */
class HammingDistanceComparison implements IDistance
{        
    /**
     * Return the hamming distance, expects the strings to be equal length
     * @param string $obj1
     * @param string $obj2
     * @return int
     */
    public function distance($obj1, $obj2)
    {
        $distance = 0;
        $strLength = strlen($obj1);
        for($index = 0; $index < $strLength; $index++)
        {
            if($obj1[$index] != $obj2[$index]) { 
                $distance++;
            }
        }
        return $distance;
    }
}
