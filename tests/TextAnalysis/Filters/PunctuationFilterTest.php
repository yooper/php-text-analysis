<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\PunctuationFilter;

/**
 * @author yooper (yooper)
 */
class PunctuationFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testPunctuation()
    {
        $transformer = new PunctuationFilter();
        $this->assertEquals("Yoopers", $transformer->transform("Yooper's!?;,"));
        $this->assertEquals("Yoopers", $transformer->transform("Yooper's!?;,"));
        
    }
    
    public function testOnDate()
    {
        $transformer = new PunctuationFilter(['\/',':'], []);
        $this->assertEquals('8/8/2016 5:51 PM', $transformer->transform('8/8/2016 5:51 PM'));
    }
    
}
