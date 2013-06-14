<?php

namespace Tests\TextAnalysis\Tokenizers\Simple;

use TextAnalysis\Tokenizers\Simple\FixedLengthTokenizer;

/**
 * FixedLengthTokenizerTest
 * @author dcardin
 */
class FixedLengthTokenizerTest extends \PHPUnit_Framework_TestCase{
    
    public function testFixedLengthTokenizer()
    {
        $tokenizer = new FixedLengthTokenizer(2,4);
        $tokens = $tokenizer->tokenize("Gabby Abby");
        $this->assertCount(1, $tokens);
        $this->assertEquals("bby ", end($tokens));      
        
    }
    
    public function testFixedLengthNoLengthGiven()
    {
        $tokenizer = new FixedLengthTokenizer(0);
        $tokens = $tokenizer->tokenize("Gabby Abby");
        $this->assertCount(1, $tokens);
        $this->assertEquals("Gabby Abby", end($tokens));        
    }
    
}

