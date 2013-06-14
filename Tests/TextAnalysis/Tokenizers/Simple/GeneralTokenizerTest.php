<?php

namespace Tests\TextAnalysis\Tokenizers\Simple;

use TextAnalysis\Tokenizers\Simple\GeneralTokenizer;

/**
 * GeneralTokenizerTest
 * @author dcardin
 */
class GeneralTokenizerTest extends \PHPUnit_Framework_TestCase{
    
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

