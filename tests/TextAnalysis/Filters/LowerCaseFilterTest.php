<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\LowerCaseFilter;

/**
 * @author yooper (yooper)
 */
class LowerCaseFilterilterTest extends \PHPUnit\Framework\TestCase
{
    public function testRemoveSingleQuote()
    {
        $transformer = new LowerCaseFilter();
        $this->assertEquals("yooper's", $transformer->transform("Yooper's"));
    }
    
}