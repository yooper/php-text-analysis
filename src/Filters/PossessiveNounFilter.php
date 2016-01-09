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
     * Lower case the word and return it
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        if(Text::endsWith($word, "'s")) { 
            return substr($word, 0, -2);
        } else {
            return $word;
        }
    }
   
}
