<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove the punctuation 
 * @author yooper
 */
class PunctuationFilter implements ITokenTransformation
{
    public function transform($word)
    {
        return preg_replace("/[[:punct:]]+/", "", $word);
    }

}
