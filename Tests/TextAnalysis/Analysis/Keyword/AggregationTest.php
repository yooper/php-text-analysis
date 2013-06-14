<?php
namespace Tests\TextAnalysis\Analysis\Keyword;

/** *
 * @author yooper
 */
class AggregationTest extends \Test\BaseUnitTest{
    public function testKeywordDensityAggregationLorem(){ 
        
        $kd = new \TextAnalysis\Analysis\Keyword\Density();
        $text = file_get_contents(TESTS_PATH.'data'.DS.'Text'.DS.'Analysis'.DS.'text.txt');        
        $kd->analyzeString($text, 2);
        $wordDensity = $kd->getKeyWordDensityTable();
        $aggregation = new \TextAnalysis\Analysis\Keyword\Aggregation($wordDensity);        
        $sortedFrequencies = $aggregation->getSortedWordCountFrequency(0);
        $this->assertEquals(3, $sortedFrequencies['in']);        
        $this->assertEquals(63, count($sortedFrequencies));
        $this->assertEquals(0, $aggregation->getSortedWordCountFrequency(100));
        
    }
    
    public function testKeywordDensityAggregationLength(){ 
        
        $kd = new \TextAnalysis\Analysis\Keyword\Density();
        $text = file_get_contents(TESTS_PATH.'data'.DS.'Text'.DS.'Analysis'.DS.'text.txt');        
        $kd->analyzeString($text, 2);
        $wordDensity = $kd->getKeyWordDensityTable();
        $aggregation = new \TextAnalysis\Analysis\Keyword\Aggregation($wordDensity);        
        $sortedLengths = $aggregation->getSortedByWordLength(1);
        
        $this->assertEquals(23, current($sortedLengths));        
        
        
    }    
    
}

