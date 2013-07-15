<?php

namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\SentenceTokenizer;

/**
 * @author yooper
 */
class SentenceTokenizerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSpaceTokenizer()
    {
        $tokenizer = new SentenceTokenizer(" ");
        $this->assertCount(1, $tokenizer->tokenize("This has some words."));
    }
    
    public function testLineTokenizer(){

        $tokenizer = new SentenceTokenizer(PHP_EOL);
        $this->assertCount(2, $tokenizer->tokenize("My name is Yooper. I like programming."));        
    }
}