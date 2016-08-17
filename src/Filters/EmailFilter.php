<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove the email address 
 * @author yooper
 */
class EmailFilter implements ITokenTransformation
{
    
    /**
     * Remove the email address
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        if(filter_var($word, FILTER_VALIDATE_EMAIL)) {
            return null;
        } 
        return $word;
    }
}

