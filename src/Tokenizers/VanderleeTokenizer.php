<?php

namespace TextAnalysis\Tokenizers;

use Sentence;

/**
 * A wrapper around the sentence tokenizer written by 
 * vanderlee/php-sentence
 * @author yooper
 */
class VanderleeTokenizer extends TokenizerAbstract
{
    /**
     *
     * @var Sentence
     */
    protected $sentence = null;
    
    public function __construct() 
    {
        $this->sentence = new Sentence;
    }

    /**
     * Split the text into sentences
     * @param string $string
     * @return array
     */
    public function tokenize($string): array 
    {
        return filter_empty( $this->sentence->split($string));
    }
    
    public function __destruct() 
    {
        unset($this->sentence);
    }

}
