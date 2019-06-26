<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\JaccardIndexComparison;

/**
 * Description of JaccardIndexComparisonTest
 *
 * @author yooper <yooper>
 */
class JaccardIndexComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testJaccardIndex()
    {
        $c = new JaccardIndexComparison();
        $this->assertEquals(1, $c->similarity('a', 'a'));
        $this->assertEquals(1, $c->similarity(['a'], ['a']));
        $this->assertEquals(1, $c->similarity(['a','b'], ['b','a']));
        $this->assertEquals(.5, $c->similarity(['a','b'], ['a']));                        
    }
}
