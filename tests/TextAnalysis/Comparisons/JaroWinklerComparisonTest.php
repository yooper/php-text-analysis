<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\JaroWinklerComparison;

/**
 *
 * @author yooper <yooper>
 */
class JaroWinklerComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testJaroWinkler()
    {
        $jw = new JaroWinklerComparison();
        $this->assertEquals('0.961', sprintf("%1.3f", $jw->similarity('MARTHA', 'MARHTA')));
        $this->assertEquals('0.840', sprintf("%1.3f", $jw->similarity('DWAYNE', 'DUANE')));
        $this->assertEquals('0.813', sprintf("%1.3f", $jw->similarity('DIXON', 'DICKSONX')));                
    }
}
