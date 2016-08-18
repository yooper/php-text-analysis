<?php

namespace TextAnalysis\Filters;


use TextAnalysis\Interfaces\ITokenTransformation;
use TextAnalysis\Utilities\Text;

/**
 * Remove 's from tokens
 * @author dcardin
 */
class PossessiveNounFilter implements ITokenTransformation
{
    /**
     * remove the possive nouns
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        return preg_replace("/\'s/", "", $word);
    }
   
}
