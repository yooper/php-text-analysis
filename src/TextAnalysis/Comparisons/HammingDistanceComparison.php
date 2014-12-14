<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Utilities\String;
use TextAnalysis\Interfaces\IDistance;
use Exception;

/** 
 * Implementation of hamming distance using PHP's native function 
 * 
 * @author Dan Cardin <yooper>
 */
class HammingDistanceComparison implements IDistance
{
    public function __construct() {
        if(!extension_loaded('gmp') ) {
            throw new Exception("PECL GMP module is required");
        }
    }
        
    /**
     * Return the hamming distance, expects the strings to be equal length
     * @param string $obj1
     * @param string $obj2
     * @return int
     */
    public function distance($obj1, $obj2)
    {
        return gmp_hamdist( 
                gmp_init( String::convertBinaryToString($obj1), 2), 
                gmp_init( String::convertBinaryToString($obj2), 2));
    }
}
