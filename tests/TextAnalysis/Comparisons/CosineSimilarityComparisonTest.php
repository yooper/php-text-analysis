<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\CosineSimilarityComparison;

/**
 *
 * @author yooper
 */
class CosineSimilarityComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testIdentical()
    {
        $text1 = ["hiking" , "camping", "swimming"];
        $text2 = ["hiking" , "camping", "swimming"];        
        $compare = new CosineSimilarityComparison();
        $this->assertEquals(1.0, $compare->similarity($text1, $text2));

    }
   
    public function testDifferent()
    {
        $text1 = ["hiking" , "hiking", "camping", "swimming"];
        $text2 = ["hiking" , "biking", "camping", "swimming"];        
        $compare = new CosineSimilarityComparison();
        $this->assertEquals(0.8, round($compare->similarity($text1, $text2), 1));        
    }
    
    public function testNothingInCommon()
    {
        $text1 = ["hiking", "camping", "swimming"];
        $text2 = ["biking", "boating", "floating"];        
        $compare = new CosineSimilarityComparison();
        $this->assertEquals(0, $compare->similarity($text1, $text2));         
    }
}
