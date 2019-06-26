<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\EmailFilter;

/**
 *
 * @author yooper
 */
class EmailFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testEmailFilter()
    {
        $filter = new EmailFilter();        
        $this->assertEquals(null, $filter->transform("yooper@example.com"));
        $this->assertEquals(' , ' , $filter->transform("yooper.mqt@example.sub.dub.edu , yooper@example.com"));
        
    }
}
