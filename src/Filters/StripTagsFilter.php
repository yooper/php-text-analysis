<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove html from the text;
 * @author dcardin
 */
class StripTagsFilter implements ITokenTransformation
{
    public function transform($word) 
    {
        return strip_tags($word);
    }

}
