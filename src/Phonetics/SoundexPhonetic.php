<?php

namespace Tests\TextAnalysis\Comparisons\Phonetics;
use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Wrapper for PHP's native soundex 
 * @author yooper
 */
class SoundexPhonetic implements ITokenTransformation
{
    /**
     * Return the soundex
     * @param string $token
     * @return string
     */
    public function transform($token)
    {
        return soundex($token);
    }
}
