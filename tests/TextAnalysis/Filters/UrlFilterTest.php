<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\UrlFilter;
use TextAnalysis\Tokenizers\GeneralTokenizer;

/**
 * Description of UrlFilterTest
 *
 * @author yooper
 */
class UrlFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlFilter()
    {
        $filter = new UrlFilter();        
        $this->assertEquals("google.com", $filter->transform("google.com"));
        $this->assertEquals(" , ", $filter->transform("https://github.com/yooper/php-text-analysis/wiki , https://www.facebook.com/?query=1&field=none"));
        $this->assertEquals('hello', $filter->transform("hello"));        
    }
}
