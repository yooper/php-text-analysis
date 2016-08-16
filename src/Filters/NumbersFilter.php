<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove numbers from the token
 * @author dcardin
 */
class NumbersFilter implements ITokenTransformation
{
    /**
     * 
     * @param string $word
     * @return string
     */
    public function transform($word) 
    {
        return trim(preg_replace('/\d+/u', '', $word));
    }

}
