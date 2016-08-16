<?php

namespace Tests\TextAnalysis\Filters;

/**
 *
 * @author yooper
 */
class NumbersFilterTest extends \TestBaseCase
{
    public function testNumbersFilter()
    {
        $filter = new \TextAnalysis\Filters\NumbersFilter();
        $this->assertEquals('easy street', $filter->transform("123 easy street"));
        $this->assertEquals('easy street', $filter->transform("easy street"));
        $this->assertEquals('april th,', $filter->transform("april 25th, 1992"));
        
    }
}
