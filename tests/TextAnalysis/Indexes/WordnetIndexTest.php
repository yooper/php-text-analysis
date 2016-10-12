<?php

namespace Tests\TextAnalysis\Indexes;

use TextAnalysis\Indexes\WordnetIndex;
use TextAnalysis\Corpus\WordnetCorpus;

/**
 * Description of WordnetIndexTest
 *
 * @author yooper
 */
class WordnetIndexTest extends \PHPUnit_Framework_TestCase
{
    public function testGetLemma()
    {
        if( getenv('SKIP_TEST') || !is_dir(get_storage_path('corpora/wordnet'))) {
            return;
        }            
        $wnIdx = new WordnetIndex(new WordnetCorpus(get_storage_path('corpora/wordnet')));
        $lemmas = $wnIdx->getLemma('programmer');
        $this->assertCount(8, $lemmas[0]->getSynsets()[0]->getLinkedSynsets());
    }
    
}
