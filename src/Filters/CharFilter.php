<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Removes a single character, but not a number
 * @author yooper
 */
class CharFilter implements ITokenTransformation
{
    
    public function transform($word)
    {
        if(strlen($word) === 1 && !is_numeric($word)) { 
            return null;
        }
        return $word;
    }

}
