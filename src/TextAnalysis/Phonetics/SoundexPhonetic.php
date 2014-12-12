<?php

namespace Tests\TextAnalysis\Comparisons\Phonetics;

/**
 * Wrapper for PHP's native soundex 
 * @author dcardin
 */
class SoundexPhonetic 
{
    /**
     * Return the soundex
     * @param string $text
     * @return string
     */
    public function compute($text)
    {
        return soundex($text);
    }
}
