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
     * Remove the email address. The matching rule was intentionally left simplistic
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        return preg_replace("/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/", "", $word);
    }
}

