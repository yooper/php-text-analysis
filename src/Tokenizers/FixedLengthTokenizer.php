<?php

namespace TextAnalysis\Tokenizers;

/**
 * 
 * FixedLength Tokenizer, wraps substr
 * @package Tokenizers\Simple\FixedLengthTokenizer
 * @author yooper
 */
class FixedLengthTokenizer extends TokenizerAbstract
{
    protected $startPosition = 0;
    protected $length = null;
    
    /**
     * @param int $startPosition
     * @parma int $length 
     */
    public function __construct($startPosition, $length = null)
    {
        $this->startPosition = $startPosition;
        $this->length = $length;
    }
    
    /**
     * Return array with single element
     * @param string $string
     * @return array 
     */
    public function tokenize($string)
    {
        if(!$this->length) {
            return array(substr($string, $this->startPosition));
        } else {
            return array(substr($string, $this->startPosition, $this->length));
        }
    }
    
}

