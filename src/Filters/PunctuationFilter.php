<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove the punctuation
 * @author yooper
 */
class PunctuationFilter extends SpacePunctuationFilter
{
    public function transform($word)
    {
        return preg_replace($this->getRegex(), '', $word);
    }

}
