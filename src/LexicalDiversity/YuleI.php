<?php

namespace TextAnalysis\LexicalDiversity;

/**
 * Implementation of Yule's I algorithm
 * @author yooper
 */
class YuleI
{
    public function getDiversity(array $tokens) : float
    {
        $freq = array_count_values($tokens);        
        $m1 = array_sum( array_values( $freq));
        $m2 = array_sum( array_map( function($value){ return $value ** 2; }, array_values($freq)));
        return ($m1*$m1) / ($m2-$m1);        
    }   
}
