<?php

namespace Tests\TextAnalysis\Taggers;

use TextAnalysis\Taggers\StanfordPosTagger;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use Mockery;
use TextAnalysis\Documents\TokensDocument;


/**
 * Tests the stanford pos tagger
 * @author yooper
 */
class StanfordPosTaggerTest extends \PHPUnit\Framework\TestCase
{
    protected $text = "Marquette County is a county located in the Upper Peninsula of the US state of Michigan. As of the 2010 census, the population was 67,077.";
            
    protected $posPath = 'taggers/stanford-postagger-2015-12-09'; 

    public function testJarNotFound()
    {
        $tagger = new StanfordPosTagger("not_available.jar", "not available");
        $this->expectException('RuntimeException', 'Jar not found not_available.jar');
        $tagger->tag([]);
    }
    
    public function testClassiferNotFound()
    {
        if( getenv('SKIP_TEST') || !getenv('JAVA_HOME')) {
            return;
        }           
        
        $tagger = new StanfordPosTagger(get_storage_path($this->posPath).'stanford-postagger-3.6.0.jar', "classifier.gz");
        $this->expectException('RuntimeException', 'Classifier not found classifier.gz');
        $tagger->tag([]);        
    }
    
    public function testTempCreatedFile()
    {
        $mockTagger = Mockery::mock('TextAnalysis\Taggers\StanfordPosTagger[exec,verify]', ['bogus.jar', 'bogus.classifier'])
                ->shouldAllowMockingProtectedMethods();
        
        $mockTagger->shouldReceive('exec')
                ->andReturnNull()
                ->shouldReceive('verify')
                ->andReturnNull();
        
        $mockTagger->tag((new WhitespaceTokenizer())->tokenize($this->text));
        $this->assertFileExists($mockTagger->getTmpFilePath());
        $this->assertEquals(138, filesize($mockTagger->getTmpFilePath()));
    }

    public function testStanfordPos()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
        
        $document = new TokensDocument((new WhitespaceTokenizer())->tokenize($this->text));            
        $tagger = new StanfordPosTagger();
        $output = $tagger->tag($document->getDocumentData());
        
        $this->assertFileExists($tagger->getTmpFilePath());        
        $this->assertEquals(138, filesize($tagger->getTmpFilePath())); 
        $this->assertEquals(['Michigan','NNP'], $output[15], "Did you set JAVA_HOME env variable?");        
    }    
    
}
