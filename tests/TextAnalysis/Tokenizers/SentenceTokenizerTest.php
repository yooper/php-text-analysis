<?php

namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\SentenceTokenizer;

/**
 * @author yooper
 */
class SentenceTokenizerTest extends \PHPUnit\Framework\TestCase
{
    public function testSentenceTokenizer()
    {
        $tokenizer = new SentenceTokenizer();
        $this->assertCount(2, $tokenizer->tokenize("This has some words. Why only 4 words?"));
        $this->assertCount(2, $tokenizer->tokenize("My name is Yooper. I like programming!"));        
        $this->assertCount(2, $tokenizer->tokenize("My name is Yooper!? I like programming!! !!"));                
        $this->assertCount(3, $tokenizer->tokenize($this->getArticle()));
        $this->assertCount(1, $tokenizer->tokenize("The U.S.A. recently dropped out of the T.P.P."));
    }
    
    private function getArticle()
    {
        return <<<TEXT
The Freshwater is the new incarnation of the well-known Terrace Restaurant which, along with the Terrace Bay Inn, were recently purchased by Jarred and Jen Drown.

Located in the Terrace Bay Hotel between Escanaba and Gladstone, the restaurant offers comfort food with a twist. The menu is the creation of head chef Ken Coates.        
TEXT;
    }
 
}