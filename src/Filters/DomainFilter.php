<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove domain names 
 * @author yooper
 */
class DomainFilter implements ITokenTransformation
{ 
    /**
     * @param sting $word
     * @return null|string
     */
    public function transform($word) 
    {
        if(filter_var('example@'.$word, FILTER_VALIDATE_EMAIL)) {
            return null;
        }
        return $word;

    }    
}
