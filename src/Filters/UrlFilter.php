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
        $result = parse_url($word);
        if(!$result || !isset($result['host'])) {
            return $word;
        } 
        return null;
    }

}
