<?php

namespace Tests\TextAnalysis\Comparisons\Phonetics;

/**
 * Native wrapper for PHP's metaphone function
 * @author Dan Cardin (yooper)
 */
class MetaphonePhonetic 
{
    /**
     * Return the metaphone algorithm
     * @param string $text
     * @return string
     */
    public function compute($text)
    {
        return metaphone($text);
    }
}
