<?php

namespace Tests\TextAnalysis\Extracts;

use TextAnalysis\Extracts\DateExtract;

/**
 * Test if date extraction is working
 * @author yooper
 */
class DateExtractTest extends \PHPUnit\Framework\TestCase
{
    public function testDate()
    {
        $extract = new DateExtract();
        $this->assertFalse($extract->filter("no date in jan. set"));
        $this->assertInstanceOf('DateTime', $extract->filter('jan. 12th 1999'));
    }
    
}
