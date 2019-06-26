<?php

namespace Tests\TextAnalysis\Indexes;

use TextAnalysis\Indexes\WordnetIndex;
use TextAnalysis\Corpus\WordnetCorpus;

/**
 * Description of WordnetIndexTest
 *
 * @author yooper
 */
class WordnetIndexTest extends \PHPUnit\Framework\TestCase
{
    
    /**
     *
     * @var WordnetIndex
     */
    protected $wordnetIdx = null;
    
    /**
     * 
     * @return WordnetIndex
     */
    public function getWordnetIndex()
    {
        if(!$this->wordnetIdx) {
            $this->wordnetIdx = new WordnetIndex(new WordnetCorpus(get_storage_path('corpora/wordnet')));
        }
        return $this->wordnetIdx;
    }
    
    public function testGetLemma()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }            
        
        $lemmas = $this->getWordnetIndex()->getLemma('programmer');
        $this->assertCount(8, $lemmas[0]->getSynsets()[0]->getLinkedSynsets());
    }
    
    public function testGetMorph()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }           
        $this->assertEquals('play', $this->getWordnetIndex()->getMorph('playing'));
        $this->assertEquals('dog', $this->getWordnetIndex()->getMorph('dogs'));
        $this->assertEquals('church', $this->getWordnetIndex()->getMorph('churches'));
        $this->assertEquals('aardwolf', $this->getWordnetIndex()->getMorph('aardwolves'));
        $this->assertEquals('abacus', $this->getWordnetIndex()->getMorph('abaci'));
        $this->assertEquals('book', $this->getWordnetIndex()->getMorph('books'));             
    }
    
}
