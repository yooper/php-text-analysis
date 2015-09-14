<?php

namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\SentenceTokenizer;

/**
 * @author yooper
 */
class SentenceTokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testSentenceTokenizer()
    {
        $tokenizer = new SentenceTokenizer();
        $this->assertCount(2, $tokenizer->tokenize("This has some words. Why only 4 words?"));
        $this->assertCount(2, $tokenizer->tokenize("My name is Yooper. I like programming!"));        
        $this->assertCount(2, $tokenizer->tokenize("My name is Yooper!? I like programming!! !!"));                
    }
 
}