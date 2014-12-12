<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;
use TextAnalysis\Utilities\String;

/**
 * Find the longest common substring/subsequence (LCS) between two strings
 * @author Dan Cardin <yooper>
 */
class LongestCommonSubstringComparison implements ISimilarity, IDistance
{
    /**
     * Returns the string length of the longest common substring (LCS)
     * @param string $obj1
     * @param string $obj2
     * @return int
     */
    public function distance($obj1, $obj2) 
    {
        return strlen($this->similarity($obj1, $obj2));
    }

    /**
     * Returns the Longest common substring
     * @param string $obj1
     * @param string $obj2
     * @return string
     */
    public function similarity($obj1, $obj2) 
    {
        $intersection = array_intersect( String::getAllSubStrings($obj1), String::getAllSubStrings($obj2));
        $max = 0;
        $lcs = '';
        foreach($intersection as $substr)
        {
            if( strlen($substr) > $max) {
                $max = strlen($substr);
                $lcs = $substr;
            }
        }
        return $lcs;
    }
}
