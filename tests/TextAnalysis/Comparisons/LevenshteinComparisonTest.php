<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\LevenshteinComparison;

/**
 *
 * @author yooper
 */
class LevenshteinComparisonTest extends \PHPUnit_Framework_TestCase
{
    public function testHatCat()
    {
        $comparison = new LevenshteinComparison();
        $this->assertEquals(1, $comparison->distance('hat', 'cat'));
    }
}
