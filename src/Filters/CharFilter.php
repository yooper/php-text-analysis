<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Removes a single character, but not a number. 
 * @author yooper
 */
class CharFilter implements ITokenTransformation
{ 
    public function transform($word)
    {
        return preg_replace("/ \D /", " ", $word);
    }

}
