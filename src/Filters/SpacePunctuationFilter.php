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
        '!','#','$','%','&','(',')','*','+',"'",',',
        '\\','-','.','/',':',';','<','=','>','?','@',
        '^','_','`','{','|','}','~'                
    ];
    
    protected $replacements = [];
    
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
        
        foreach($this->getSearchFor() as $punct)
        {
            $this->replacements[] = " $punct ";
        }
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
     * @return array
     */
    public function getReplacements()
    {
        return $this->replacements;
    }
       

    /**
     * 
     * @param string $word
     * @return string
     */
    public function transform($word) 
    {
        return str_replace($this->getSearchFor(), $this->getReplacements(), $word);
    }

}
