<?php
declare(strict_types = 1);

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\LevenshteinComparison;

/**
 *
 * @author yooper
 */
class LevenshteinComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testHatCat()
    {
        $comparison = new LevenshteinComparison();
        $this->assertEquals(1, $comparison->distance('hat', 'cat'));
    }
}
