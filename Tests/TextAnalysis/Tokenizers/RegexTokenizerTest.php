<?php

namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\RegexTokenizer;

/**
 * @author yooper
 */
class RegexTokenizerTest extends \PHPUnit_Framework_TestCase
{   
    public function testDefaultRegex()
    {
        //uses default regex
        $tokenizer = new RegexTokenizer();
        $tokens = $tokenizer->tokenize("Good muffins cost $3.88\nin New York.  Please buy me\ntwo of them.\nThanks.");
        $this->assertCount(17, $tokens);
    }
    
    public function testMatchWordsOnly()
    {
        //uses default regex
        $tokenizer = new RegexTokenizer("/[A-Za-z]+/");
        $tokens = $tokenizer->tokenize("Good muffins cost $3.88\nin New York.  Please buy me\ntwo of them.\nThanks.");
        $this->assertCount(13, $tokens);
    }    

}