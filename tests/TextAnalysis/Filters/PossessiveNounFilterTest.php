<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\PossessiveNounFilter;

/**
 * Description of PossessiveNounFilterTest
 *
 * @author yooper
 */
class PossessiveNounFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testPossessive()
    {
        $filter = new PossessiveNounFilter();
        $this->assertEquals("yooper", $filter->transform("yooper's"));
    }
    
    public function testNonPossessive()
    {
        $filter = new PossessiveNounFilter();
        $this->assertEquals("yooper", $filter->transform("yooper"));        
    }
}
