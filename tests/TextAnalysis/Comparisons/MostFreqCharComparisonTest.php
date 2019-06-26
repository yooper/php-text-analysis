<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\MostFreqCharComparison;

/**
 *
 * @author yooper <yooper>
 */
class MostFreqCharComparisonTest extends \PHPUnit\Framework\TestCase 
{
    public function testComparison()
    {
        $mf = new MostFreqCharComparison();
        $this->assertEquals(4, $mf->similarity('research', 'research'));
        $this->assertEquals(2, $mf->similarity('research', 'seeking'));
        $this->assertEquals(3, $mf->similarity('significant', 'capabilities'));
        
        $this->assertEquals(4, $mf->distance('research', 'research'));
        $this->assertEquals(6, $mf->distance('research', 'seeking'));
        $this->assertEquals(9, $mf->distance('significant', 'capabilities'));        
    }
}
