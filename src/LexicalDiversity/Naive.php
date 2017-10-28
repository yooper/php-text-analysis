<?php

namespace TextAnalysis\LexicalDiversity;

/**
 * A very simple algorithm for measuring lexical diversity;
 *
 * @author yooper
 */
class Naive implements \TextAnalysis\Interfaces\ILexicalDiversity
{
    public function getDiversity(array $tokens) : float
    {
        
        return count(array_unique( $tokens )) / array_sum( array_map( 'strlen', $tokens) );
    }
}
