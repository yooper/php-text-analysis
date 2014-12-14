<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\HammingDistanceComparison;

/**
 * Description of HammingDistanceComparisonTest
 *
 * @author Dan Cardin <yooper>
 */
class HammingDistanceComparisonTest extends \PHPUnit_Framework_TestCase
{
    public function testHammingDistance()
    {
        $c = new HammingDistanceComparison();
        $this->assertEquals(2, $c->distance('1011101', '1001001'));
    }
}