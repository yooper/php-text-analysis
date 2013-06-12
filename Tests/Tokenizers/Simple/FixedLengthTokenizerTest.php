<?php

namespace Test\Tokenizers\Simple;

use Tokenizers\Simple\FixedLengthTokenizer;

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
        $this->assertEquals("bb", end($tokens));
    }
    
}

