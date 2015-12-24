<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;

/**
 * Implementation of the Jaro Winker algorithm
 * @author yooper <yooper>
 */
class JaroWinklerComparison implements ISimilarity
{
    /**
     * The minimum prefix length
     * @var int
     */
    protected $minPrefixLength;
    
    public function __construct($minPrefixLength = 4)
    {
        $this->minPrefixLength = $minPrefixLength;
    }
    
    /**
     * Return the similarity using the JaroWinkler algorithm
     * @param string $text1
     * @param string $text2
     * @return real
     */
    public function similarity($text1, $text2)
    {
        if($text1 === $text2) {
            return 1.0;
        }
        
        // ensure that s1 is shorter than or same length as s2
        if (strlen($text1) > strlen($text2)) {
            $tmp = $text1;
            $text1 = $text2;
            $text2 = $tmp;
        }

        $strLen1 = strlen($text1);
        $strLen2 = strlen($text2);
        
        $maxDistance = (int)$strLen2 / 2;
        $commonCounter = 0; // count of common characters
        $transpositionCounter = 0; // count of transpositions
        $prevPosition = -1;
        for ($index = 0; $index < $strLen1; $index++) 
        {
            $char = $text1[$index];
            // init inner loop 
            $jindex = max(0, $index - $maxDistance);
            while($jindex < min($strLen2, $index + $maxDistance))
            {
                if ($char === $text2[$jindex]) {
                    $commonCounter++; // common char found
                    if ($prevPosition != -1 && $jindex < $prevPosition) {
                        $transpositionCounter++; 
                    }
                  $prevPosition = $jindex;
                  break;
                }
                
                $jindex++;
            }
        }
        // no common characters between strings
        if($commonCounter === 0) {
            return 0.0;
        }
        
        // first compute the score
        $score = (
                ($commonCounter / $strLen1) +
                ($commonCounter / $strLen2) +
               (($commonCounter - $transpositionCounter) / $commonCounter)) / 3.0;

        //init values
        $prefixLength = 0; // length of prefix
        $last = min($this->minPrefixLength, $strLen1);        
        while($prefixLength < $last && $text1[$prefixLength] == $text2[$prefixLength])
        {
            $prefixLength++;
        }
        
        return $score + (($prefixLength * (1 - $score)) / 10);     
    }    

}
