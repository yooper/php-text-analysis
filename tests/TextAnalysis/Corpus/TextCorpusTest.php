<?php

namespace Tests\TextAnalysis\Corpus;

use TextAnalysis\Corpus\TextCorpus;

/**
 * Test cases for Text Corpus
 * @todo testConcordance, testFindAll, and testDispersion 
 * should be returning the same result counts for tom sawyer, but the don't
 * @author yooper
 */
class TextCorpusTest extends \TestBaseCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(TextCorpus::class, text($this->getText()));
    }
    
    public function testConcordance()
    {
        $corpus = new TextCorpus($this->getText());
        $results = $corpus->concordance("tom sawyer");
        $this->assertCount(34, $results);
    }
    
    public function testConcordancePtBr()
    {
        $corpus = new TextCorpus($this->getText('ptbr'));
        $results = $corpus->concordance("José",20, true, 'equal');
        $this->assertCount(160, $results);
    }

    public function testTokenizer()
    {
        $corpus = new TextCorpus($this->getText());
        $results = $corpus->getTokens();
        $this->assertCount(76057, $results);
    }
    
    public function testFindAll()
    {
        $corpus = new TextCorpus($this->getText());
        $results = $corpus->findAll("tom sawyer");
        $this->assertCount(32, $results);        
    }
    
    public function testDispersion()
    {
        $corpus = new TextCorpus($this->getText());
        $results = $corpus->getDispersion(["tom sawyer", "huck finn"]);
        $this->assertCount(22, $results[0]);
        $this->assertCount(58, $results[1]);        
    }
        
}
