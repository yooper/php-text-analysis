<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove extract whitespaces
 * @author dcardin
 */
class WhitespaceFilter implements ITokenTransformation
{
    public function transform($word) 
    {
        return preg_replace("/\s[[:space:]]+/", " ", str_replace(["\r", "\n"], ' ', $word));
    }

}
