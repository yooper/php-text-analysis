<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\SpacePunctuationFilter;

/**
 *
 * @author yooper
 */
class SpacePunctuationFilterTest  extends \PHPUnit\Framework\TestCase
{
    public function testDefaults()
    {
        $filter = new SpacePunctuationFilter();
        $this->assertEquals('P . B . R . ', $filter->transform('P.B.R.'));
        $this->assertEquals('8 / 8 / 2016 5 : 51 PM', $filter->transform('8/8/2016 5:51 PM'));        
    }
    
    public function testWhiteList()
    {
        $filter = new SpacePunctuationFilter([],['O','E']);
        $this->assertEquals('H O M E R', $filter->transform('HOMER'));
    }  
    
    public function testBlackList()
    {
        $filter = new SpacePunctuationFilter(['\/',':']);
        $this->assertEquals('8/8/2016 5:51 PM', $filter->transform('8/8/2016 5:51 PM'));
    }    
    
}
