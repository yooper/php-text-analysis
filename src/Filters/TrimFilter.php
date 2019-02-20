<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;


/**
 * Trims leading and trailing whitespace from the text
 * @author yooper
 */
class TrimFilter implements ITokenTransformation
{
    public function transform($word)
    {
        return trim($word);
    }

}
