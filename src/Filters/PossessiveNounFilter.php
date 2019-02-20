<?php

namespace TextAnalysis\Filters;


use TextAnalysis\Interfaces\ITokenTransformation;

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
        return preg_replace("/\'s/", '', $word);
    }

}
