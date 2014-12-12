<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;

/**
 * Wrapper for PHP's similar_text function
 * @author Dan Cardin (yooper)
 */
class SimilarTextComparison implements ISimilarity, IDistance
{
    /**
     * Return the distance
     * @param string $obj1
     * @param string $obj2
     * @return type
     */
    public function distance($obj1, $obj2) 
    {
        return strlen($obj2) - $this->similarity($obj1, $obj2);
    }

    /**
     * Returns similar_text call
     * @param string $obj1
     * @param string $obj2
     * @return int
     */
    public function similarity($obj1, $obj2) 
    {
        return similar_text($obj1, $obj2);
    }

}
