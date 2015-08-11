<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\QuotesFilter;

/**
 * @author yooper (yooper)
 */
class QoutesFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveSingleQuote()
    {
        $transformer = new QuotesFilter();
        $this->assertEquals('Yoopers', $transformer->transform("Yooper's"));
    }
    
    public function testRemoveDoubleQuote()
    {
        $transformer = new QuotesFilter();
        $this->assertEquals("Peninsula", $transformer->transform('"Peninsula"'));
    }
}