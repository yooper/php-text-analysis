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
    
    public function __construct()
    {
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
