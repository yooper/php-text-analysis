<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;

/**
 * Implents the Jaccard Index algorithm
 * @author Dan Cardin <yooper>
 */
class JaccardIndexComparison implements ISimilarity
{
    /**
     * Returns the Jaccard Index
     * @param string|array $obj1
     * @param string|array $obj2
     * @return float
     */
    public function similarity($obj1, $obj2) 
    {
        if(is_string($obj1) && is_string($obj2)) {
            $obj1 = str_split($obj1);
            $obj2 = str_split($obj2);
        }         
        $inter = array_intersect( $obj1, $obj2 );
        $union = array_unique( ($obj1 + $obj2) );
        return count($inter) / count($union);                
    }
}
