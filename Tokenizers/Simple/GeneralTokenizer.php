<?php

namespace Tokenizers\Simple;

use Tokenizers\TokenizerAbstract;

/**
 * 
 * General Purpose Tokenizer, wraps strtok
 * @package Tokenizers\Simple\GeneralTokenizer\
 * @author dcardin
 */
class GeneralTokenizer extends TokenizerAbstract
{
    protected $tokenExpression = null;
    
    /**
     * 
     * @param string $tokenExpression 
     */
    public function __construct($tokenExpression)
    {
        $this->tokenExpression = $tokenExpression;
    }
    
    /**
     * Return tokenized array from string
     * @param string $string
     * @return array 
     */
    public function tokenize($string)
    {
        return $this->strTokenWrapper($string);
    }
    
    /**
     * Use the php function strtok to Tokenize simple string
     * @internal
     * @return array
     */
    protected function strTokenWrapper($string)
    {
        $token = strtok($string, $this->tokenExpression);

        $tokens = array();
        while ($token !== false) {
            $tokens[] = $token;
            $token = strtok($this->tokenExpression);        
        }
        return $tokens;
    }
}

