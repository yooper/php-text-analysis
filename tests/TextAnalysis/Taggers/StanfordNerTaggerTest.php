<?php

namespace Tests\TextAnalysis\Taggers;

use TextAnalysis\Taggers\StanfordNerTagger;
use TextAnalysis\Tokenizers\WhitespaceTokenizer;
use Mockery;
use TextAnalysis\Documents\TokensDocument;


/**
 * Tests the stanford ner tagger
 * @author yooper
 */
class StanfordNerTaggerTest extends \PHPUnit\Framework\TestCase
{
    protected $nerPath = 'taggers/stanford-ner-2015-12-09';

    protected $text = "Marquette County is a county located in the Upper Peninsula of the US state of Michigan. As of the 2010 census, the population was 67,077.";
            
    public function testJarNotFound()
    {
        $tagger = new StanfordNerTagger("not_available.jar", "not available");
        $this->expectException('RuntimeException', 'Jar not found not_available.jar');
        $tagger->tag([]);
    }
    
    public function testClassiferNotFound()
    {
        if( getenv('SKIP_TEST') || !getenv('JAVA_HOME')) {
            return;
        }           
        
        $tagger = new StanfordNerTagger(get_storage_path($this->nerPath).'stanford-ner.jar', "classifier.gz");
        $this->expectException('RuntimeException', 'Classifier not found classifier.gz');
        $tagger->tag([]);        
    }
    
    public function testTempCreatedFile()
    {
        $mockTagger = Mockery::mock('TextAnalysis\Taggers\StanfordNerTagger[exec,verify]', ['bogus.jar', 'bogus.classifier'])
                ->shouldAllowMockingProtectedMethods();
        
        $mockTagger->shouldReceive('exec')
                ->andReturnNull()
                ->shouldReceive('verify')
                ->andReturnNull();
        
        $mockTagger->tag((new WhitespaceTokenizer())->tokenize($this->text));
        $this->assertFileExists($mockTagger->getTmpFilePath());
        $this->assertEquals(138, filesize($mockTagger->getTmpFilePath()));
    }

    public function testStanfordNer()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
    
        $document = new TokensDocument((new WhitespaceTokenizer())->tokenize($this->text));
        $tagger = new StanfordNerTagger();
        $output = $tagger->tag($document->getDocumentData());
        
        $this->assertFileExists($tagger->getTmpFilePath());        
        $this->assertEquals(138, filesize($tagger->getTmpFilePath())); 
        $this->assertEquals(['Michigan','LOCATION'], $output[15], "Did you set JAVA_HOME env variable?");        
    }    
    
}
