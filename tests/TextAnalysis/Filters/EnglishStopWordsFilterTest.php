<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\EnglishStopWordsFilter;

/**
 * Stop word filter test
 * @author yooper (yooper)
 */
class EnglishStopWordsFilterTest extends \PHPUnit_Framework_TestCase
{
    
    public function testIsStopWord()
    {
        $stopWord = new EnglishStopWordsFilter();
        $this->assertNull($stopWord->transform("again"));
    }
    
    public function testIsNotStopWord()
    {
        $stopWord = new EnglishStopWordsFilter();
        $this->assertEquals("Peninsula", $stopWord->transform("Peninsula"));
    }
    
    public function testIsStopWord2()
    {
        $stopWord = new EnglishStopWordsFilter();
        $this->assertNull($stopWord->transform("as"));
    }    
}