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
class StanfordNerTaggerTest extends \PHPUnit_Framework_TestCase
{
    protected $text = "Marquette County is a county located in the Upper Peninsula of the US state of Michigan. As of the 2010 census, the population was 67,077.";
            
    public function testJarNotFound()
    {
        $tagger = new StanfordNerTagger("not_available.jar", "not available");
        $this->setExpectedException('RuntimeException', 'Jar not found not_available.jar');
        $tagger->tag([]);
    }
    
    public function testClassiferNotFound()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }           
        
        $tagger = new StanfordNerTagger(get_storage_path('ner').'stanford-ner.jar', "classifier.gz");
        $this->setExpectedException('RuntimeException', 'Classifier not found classifier.gz');
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
        
        $jarPath = get_storage_path('ner').'stanford-ner.jar';
        $classiferPath = get_storage_path('ner'.DIRECTORY_SEPARATOR."classifiers")."english.all.3class.distsim.crf.ser.gz";
        
        $tagger = new StanfordNerTagger($jarPath, $classiferPath);
        $output = $tagger->tag($document->getDocumentData());
        
        $this->assertFileExists($tagger->getTmpFilePath());        
        $this->assertEquals(138, filesize($tagger->getTmpFilePath()));        
        $this->assertEquals(['LOCATION','Michigan'], $output[15]);        
    }    
    
}
