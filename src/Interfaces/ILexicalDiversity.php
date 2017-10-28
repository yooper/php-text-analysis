<?php

namespace TextAnalysis\Interfaces;

/**
 * Lexical Diversity Interface
 * @author developer
 */
interface ILexicalDiversity 
{
    public function getDiversity(array $tokens) : float;
}
