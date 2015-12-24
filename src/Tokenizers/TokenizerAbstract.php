<?php
namespace TextAnalysis\Tokenizers;

/** 
 * An Abstract Class all Tokenizers extend
 * @author yooper
 */
abstract class TokenizerAbstract 
{
    
    /**
    * Simplify Debugging
    * @var boolean 
    */
    protected $debug = false;
    
    /**
     * Used by sub classes to do any setup  
     */
    protected function init(){}
    
    /**
     * Return an array of tokens
     * @param string $string
     * @param string|null $tokenExpr 
     * @return array
     */
    abstract function tokenize($string);
        
}
