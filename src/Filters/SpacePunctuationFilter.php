<?php

namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Used to put in spaces when punctuation is used. 
 * @author yooper
 */
class SpacePunctuationFilter implements ITokenTransformation
{
    protected $searchFor = [
        '!','#','$','%','&','\(','\)','*','+',"\'",',',
        '\\','-','\.','\\/',':',';','<','=','>','?','@',
        '^','_','`','{','|','}','~','\[','\]'                
    ];
        
    protected $regex = "";
    
    /**
     * 
     * @param array $whiteList
     * @param array $blackList
     */
    public function __construct(array $whiteList = [], array $blackList = [])
    {
        // add elements from the white list
        $this->searchFor = array_diff($this->searchFor, $whiteList);        
        $this->searchFor = array_merge($this->searchFor, $blackList);                
        $this->regex = "/([".implode("", $this->searchFor)."])/";
    }
    
    /**
     * 
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }
    
    /**
     * 
     * @return array returns an array of characters that are punctuation
     */
    public function getSearchFor()
    {
        return $this->searchFor;
    }
   

    /**
     * 
     * @param string $word
     * @return string
     */
    public function transform($word) 
    {
        return preg_replace($this->getRegex(), ' $1 ', $word);
    }
    
    public function __destruct() 
    {
        unset($this->regex);
        unset($this->searchFor);
    }

}
