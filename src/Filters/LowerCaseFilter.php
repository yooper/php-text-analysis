<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Normalize the text to lower case
 * @author Dan Cardin
 */
class LowerCaseFilter implements ITokenTransformation
{
    
    /**
     * Lower case the word and return it
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        return mb_strtolower($word);
    }
}

