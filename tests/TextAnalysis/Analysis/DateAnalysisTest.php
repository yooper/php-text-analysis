<?php

namespace Tests\TextAnalysis\Analysis;

use TextAnalysis\Analysis\DateAnalysis;

/**
 *
 * @author yooper
 */
class DateAnalysisTest extends \PHPUnit\Framework\TestCase
{
    public function testDateAnalysis()
    {
        $text = "I went for a walk in Sep. I think it was 1st and the year was 2015";
        $dateAnalysis = new DateAnalysis($text);
        $this->assertEquals("2015-09-01", $dateAnalysis->getDates()[0]->format('Y-m-d'));
    }
}
