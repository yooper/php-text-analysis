<?php

namespace TextAnalysis\Tokenizers;

/**
 * 
 * General Purpose Tokenizer, wraps strtok
 * @package Tokenizers\Simple\GeneralTokenizer\
 * @author yooper
 */
class GeneralTokenizer extends TokenizerAbstract
{
    protected $tokenExpression = null;
        
    /**
     * 
     * @param string $tokenExpression 
     */
    public function __construct($tokenExpression = " \n\t\r,-!?")
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
            // avoid tokenizing white spaces
            if(!empty(trim($token))) { 
                $tokens[] = $token;
            }
            $token = strtok($this->tokenExpression);        
        }
        return $tokens;
    }
}

