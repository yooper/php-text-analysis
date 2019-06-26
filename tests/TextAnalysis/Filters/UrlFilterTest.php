<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\UrlFilter;

/**
 * Description of UrlFilterTest
 *
 * @author yooper
 */
class UrlFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testUrlFilter()
    {
        $filter = new UrlFilter();        
        $this->assertEquals("google.com", $filter->transform("google.com"));
        $this->assertEquals(" , ", $filter->transform("https://github.com/yooper/php-text-analysis/wiki , https://www.facebook.com/?query=1&field=none"));
        $this->assertEquals('hello', $filter->transform("hello"));        
    }
}
