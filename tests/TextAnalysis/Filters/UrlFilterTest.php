<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\UrlFilter;
use TextAnalysis\Tokenizers\GeneralTokenizer;

/**
 * Description of UrlFilterTest
 *
 * @author dcardin
 */
class UrlFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testUrlFilter()
    {
        $filter = new UrlFilter();        
        $this->assertEquals(null, $filter->transform("google.com"));
        $this->assertEquals(null, $filter->transform("https://github.com/yooper/php-text-analysis/wiki"));                   
    }
}
