<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\IDistance;

/**
 * Wrapper for native php's levenshtein 
 *
 * @author Dan Cardin (yooper)
 */
class LevenshteinComparison implements IDistance
{
    public function distance($obj1, $obj2)
    {
        return levenshtein($obj1, $obj2);
    }
}
