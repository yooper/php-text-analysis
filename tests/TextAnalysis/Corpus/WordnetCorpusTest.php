<?php

namespace Tests\TextAnalysis\Corpus;

use TextAnalysis\Corpus\WordnetCorpus;

/**
 * Test cases for word net
 * @author yooper
 */
class WordnetCorpusTest extends \TestBaseCase 
{
    
    public function testFilesFound()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }          
        $wn = new WordnetCorpus(get_storage_path('corpora/wordnet'));
        
        foreach($wn->getFileNames() as $fileName)
        {
            $this->assertFileExists($wn->getDir().$fileName);
        }        
    }
    
    public function testGetLexNames()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }          
        $wn = new WordnetCorpus(get_storage_path('corpora/wordnet'));
        $this->assertCount(45, $wn->getLexNames());
    }

    public function testGetLemmaFromString()
    {
        $testLines = [
            'zombie n 5 3 @ %s ; 5 1 10805638 10805932 10805783 09825519 07919165'  
        ];
        $wn = new WordnetCorpus("not_checked");
        $lemma = $wn->getLemmaFromString($testLines[0]);
        $this->assertCount(5, $lemma->getSynsetOffsets());
        $this->assertEquals('n', $lemma->getPos());
        $this->assertTrue($lemma->isHypernym());
    }

    
    public function testGetSynsetFromString()
    {
        $testLines = [
            "825519 18 n 03 automaton 1 zombi 1 zombie 1 004 @ 09606527 n 0000 + 01499999 a 0101 + 00480221 v 0101 + 00480221 v 0102 | someone who acts or responds in a mechanical or apathetic way; \only an automaton wouldn't have noticed\"" ];
        $wn = new WordnetCorpus("not_checked");
        $synset = $wn->getSynsetFromString($testLines[0]);
        $this->assertCount(3, $synset->getWords());
        $this->assertCount(4, $synset->getLinkedSynsets());
    }
    
    
    public function testGetLemmas()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }          
        $wnMock = $this->getPartialMock(WordnetCorpus::class, ['getIndexFileNames' => ['index.adj']], [get_storage_path('corpora/wordnet')]);      
        $this->assertCount(21479, $wnMock->getLemmas());
        $keys = array_keys($wnMock->getLemmas());
        $lemma = $wnMock->getLemmas()[$keys[0]];
        $this->assertEquals('.22-caliber', $lemma->getWord());
        $this->assertTrue($lemma->isPertainym());
        $this->assertFalse($lemma->isAttribute());
    }
    
    
    public function testGetSynsets()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }          
        $wn = new WordnetCorpus(get_storage_path('corpora/wordnet'));
        $synset = $wn->getSynsetByOffsetAndPos(9825519, 'n');
        $this->assertEquals(['automaton','zombi','zombie'], $synset->getWords());
        $this->assertCount(4, $synset->getLinkedSynsets());
    }
    
    public function testGetExceptionMapFromString()
    {
        $wn = new WordnetCorpus('not_used');
        
        $e1 = $wn->getExceptionMapFromString('thieves thief', 'n');
        $this->assertCount(1, $e1->getExceptionList());
        $this->assertEquals('thief', $e1->getTarget());
        $this->assertEquals('thieves', $e1->getExceptionList()[0]);
        
        $e2 = $wn->getExceptionMapFromString('ploughmen ploughman plowman', 'n');
        $this->assertCount(2, $e2->getExceptionList());
        $this->assertEquals('plowman', $e2->getTarget());
        $this->assertEquals(['ploughmen', 'ploughman'], $e2->getExceptionList());        
        
    }
    
}
