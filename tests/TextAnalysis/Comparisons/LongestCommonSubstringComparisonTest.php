<?php

namespace Tests\TextAnalysis\Comparisons;

use TextAnalysis\Comparisons\LongestCommonSubstringComparison;

/**
 *
 * @author yooper <yooper>
 */
class LongestSubstringComparisonTest extends \PHPUnit\Framework\TestCase
{
    public function testLcs()
    {
        $lcs = new LongestCommonSubstringComparison();
        
        $txt1 = "Michael";
        $txt2 = "Michelle";
        $this->assertEquals(4, $lcs->distance($txt2, $txt1));
        $this->assertEquals("Mich", $lcs->similarity($txt2, $txt1));
        
        $txt1 = "sunnyside";
        $txt2 = "hide";
        
        
        $this->assertEquals(6, $lcs->distance($txt2, $txt1));
        $this->assertEquals("ide", $lcs->similarity($txt2, $txt1));                                
    }
    
    public function testLcsWithCache()
    {
        $lcs = new LongestCommonSubstringComparison(true);        
        $txt1 = "Michael";
        $txt2 = "Michelle";
        $this->assertEquals(4, $lcs->distance($txt2, $txt1));
        $this->assertEquals("Mich", $lcs->similarity($txt2, $txt1));
        
        $txt1 = "sunnyside";
        $txt2 = "hide";
        
        $this->assertEquals(6, $lcs->distance($txt2, $txt1));
        $this->assertEquals("ide", $lcs->similarity($txt2, $txt1)); 
        
        $this->assertCount(2, $lcs->getCache());
    }
}
