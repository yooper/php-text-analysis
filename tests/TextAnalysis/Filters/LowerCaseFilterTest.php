<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\LowerCaseFilter;

/**
 * @author yooper (yooper)
 */
class LowerCaseFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testRemoveLeadingVersal()
    {
        $transformer = new LowerCaseFilter();
        $this->assertEquals("yooper's", $transformer->transform("Yooper's"));
    }
    
}