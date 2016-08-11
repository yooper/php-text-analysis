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
        return str_replace($this->getSearchFor(), "", $word);
    }

}
