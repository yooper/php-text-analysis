<?php

namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\GeneralTokenizer;

/**
 * GeneralTokenizerTest
 * @author yooper
 */
class GeneralTokenizerTest extends \PHPUnit\Framework\TestCase{
    
    public function testSpaceTokenizer()
    {
        $tokenizer = new GeneralTokenizer(" ");
        $this->assertCount(4, $tokenizer->tokenize("This has some words"));
    }
    
    public function testLineTokenizer(){

        $tokenizer = new GeneralTokenizer(PHP_EOL);
        $this->assertCount(4, $tokenizer->tokenize("This ".PHP_EOL." has".PHP_EOL." some".PHP_EOL." words"));        
    }
}

