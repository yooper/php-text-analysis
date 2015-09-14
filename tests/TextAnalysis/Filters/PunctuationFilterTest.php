<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\PunctuationFilter;

/**
 * @author yooper (yooper)
 */
class PunctuationFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testPunctuation()
    {
        $transformer = new PunctuationFilter();
        $this->assertEquals("Yoopers", $transformer->transform("Yooper's!?;,"));
    }
    
}
