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
        $word = preg_replace("/(^\s+)|(\s+$)/us", "", preg_replace( '/\s+/', ' ', $word ));
        if(empty($word)) {
            return null;
        }
        return $word;
    }

}
