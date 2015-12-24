<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\StopWordsFilter;

/**
 * Stop word filter test
 * @author yooper (yooper)
 */
class StopWordsFilterTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Load an array of stop words
     * @return array
     */
    protected function loadStopwords()
    {
        return array_map('trim', file(VENDOR_DIR.'yooper/stop-words/data/stop-words_english_1_en.txt'));
    }
    
    public function testIsStopWord()
    {
        $stopWord = new StopWordsFilter($this->loadStopwords());
        $this->assertNull($stopWord->transform("again"));
    }
    
    public function testIsNotStopWord()
    {
        $stopWord = new StopWordsFilter($this->loadStopwords());
        $this->assertEquals("peninsula", $stopWord->transform("peninsula"));
    }
    
    public function testIsStopWord2()
    {
        $stopWord = new StopWordsFilter($this->loadStopwords());
        $this->assertNull($stopWord->transform("as"));
    }    
}