<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Removes a single character, but not a number. Allows for whitelisted and blacklisted
 * items to be passed in
 * @author yooper
 */
class CharFilter implements ITokenTransformation
{
    /**
     *
     * @var array
     */
    protected $whiteList = [];
    
    /**
     *
     * @var array
     */
    protected $blackList = [];
    
    /**
     * Set a white list or black list
     * @param array $whiteList
     * @param array $blackList
     */
    public function __construct(array $whiteList = [], array $blackList = []) 
    {
        $this->whiteList = $whiteList;
        $this->blackList = $blackList;
    }
    
    public function transform($word)
    {
        if(strlen($word) === 1) { 
            if(in_array($word, $this->whiteList)) {
                return $word;
            } elseif(in_array($word, $this->blackList)) {
                return null;
            } elseif(!is_numeric($word)) {
                return null;
            }
        }
        return $word;
    }

}
