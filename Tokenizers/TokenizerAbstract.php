<?php
namespace Tokenizers;

/** 
 * An Abstract Class all Tokenizers extend
 * @author dcardin
 */
abstract class TokenizerAbstract 
{
    /**
     * Return an array of tokens
     * @param string $string
     * @param string|null $tokenExpr 
     * @return array
     */
    abstract function tokenize($string);
    
}
