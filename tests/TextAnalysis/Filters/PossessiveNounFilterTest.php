<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\PossessiveNounFilter;

/**
 * Description of PossessiveNounFilterTest
 *
 * @author yooper
 */
class PossessiveNounFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testPossessive()
    {
        $filter = new PossessiveNounFilter();
        $this->assertEquals("yooper lives in Marquette west side", $filter->transform("yooper's lives in Marquette's west side"));
    }
    
    public function testNonPossessive()
    {
        $filter = new PossessiveNounFilter();
        $this->assertEquals("yooper", $filter->transform("yooper"));        
    }
}
