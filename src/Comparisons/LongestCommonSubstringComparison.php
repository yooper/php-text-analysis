<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;
use TextAnalysis\Utilities\Text;

/**
 * Find the longest common substring/subsequence (LCS) between two strings
 * @author yooper <yooper>
 */
class LongestCommonSubstringComparison implements ISimilarity, IDistance
{
    /**
     * Returns the string length of the longest common substring (LCS)
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function distance($text1, $text2) 
    {
        return strlen($this->similarity($text1, $text2));
    }

    /**
     * Returns the Longest common substring
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public function similarity($text1, $text2) 
    {
        $intersection = array_intersect( Text::getAllSubStrings($text1), Text::getAllSubStrings($text2));
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
