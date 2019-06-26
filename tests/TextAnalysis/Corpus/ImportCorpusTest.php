<?php

namespace Tests\TextAnalysis\Corpus;

use Mockery;
use TextAnalysis\Corpus\ImportCorpus;

/**
 * Test the import corpus on the one book we have as test data
 * @author yooper
 */
class ImportCorpusTest extends \PHPUnit\Framework\TestCase
{
    public function testBook()
    {
        $mockPackage = Mockery::mock('TextAnalysis\Utilities\Nltk\Download\Package[getInstallationPath]', [null, null, null, null, null, null]);
        
        $mockPackage->shouldReceive('getInstallationPath')
                ->andReturn(TEST_DATA_DIR.DS.'books'.DS);
                
        $mockImportCorpus = Mockery::mock('TextAnalysis\Corpus\ImportCorpus[getPackage,getFileIds]', [null, null, null, null, null, null])
                ->shouldAllowMockingProtectedMethods();
        
        $mockImportCorpus->shouldReceive('getPackage')
                ->andReturn($mockPackage);

        $mockImportCorpus->shouldReceive('getFileIds')
                ->andReturn(['tom_sawyer.txt']);
                        
        $this->assertEquals(['tom_sawyer.txt'], $mockImportCorpus->getFileIds());
        $this->assertCount(76057, $mockImportCorpus->getWords());
        $this->assertCount(1, $mockImportCorpus->getRaw());
        // sentence tokenizer is too slow
        ///var_dump($mockImportCorpus->getSentences());
        //$this->assertCount(5227, $mockImportCorpus->getSentences());        
    }
}
