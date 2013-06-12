<?php

namespace Tokenizers\Simple;

use Tokenizers\TokenizerAbstract;

/**
 * 
 * FixedLength Tokenizer, wraps substr
 * @package Tokenizers\Simple\FixedLengthTokenizer
 * @author dcardin
 */
class FixedLengthTokenizer extends TokenizerAbstract
{
    protected $startPosition = 0;
    protected $stopPosition = 0;
    
    /**
     * @param int $startPosition
     * @parma int $stopPosition 
     */
    public function __construct($startPosition, $stopPosition)
    {
        $this->startPosition = $startPosition;
        $this->stopPosition = $stopPosition;
    }
    
    /**
     * Return array with single element
     * @param string $string
     * @return array 
     */
    public function tokenize($string)
    {
        return array(substr($string, $this->startPosition, $this->stopPosition - $this->startPosition));
    }
    
}

