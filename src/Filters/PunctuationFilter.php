<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove the punctuation 
 * @author yooper
 */
class PunctuationFilter extends SpacePunctuationFilter implements ITokenTransformation
{  
    public function transform($word)
    {
        return preg_replace($this->getRegex(), '', $word);
    }

}
