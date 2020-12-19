<?php
declare(strict_types = 1);

namespace TextAnalysis\Interfaces;

/**
 * Lexical Diversity Interface
 * @author developer
 */
interface ILexicalDiversity 
{
    public function getDiversity(array $tokens) : float;
}
