<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Removes urls from the word
 * @author dcardin
 */
class UrlFilter implements ITokenTransformation
{
    /**
     * 
     * @param string $word
     * @return string
     */
    public function transform($word) 
    {
        return preg_replace("/(https?|ftp):\/\/[^\s$.?#].[^\s]*/i", "", $word);
    }

}
