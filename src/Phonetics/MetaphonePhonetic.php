<?php

namespace Tests\TextAnalysis\Comparisons\Phonetics;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Native wrapper for PHP's metaphone function
 * @author yooper (yooper)
 */
class MetaphonePhonetic implements ITokenTransformation
{
    /**
     * Return the metaphone algorithm
     * @param string $token
     * @return string
     */
    public function transform($token)
    {
        return metaphone($token);
    }
}
