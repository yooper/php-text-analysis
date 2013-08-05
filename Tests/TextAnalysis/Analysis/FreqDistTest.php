<?php
namespace Tests\TextAnalysis\Analysis;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Aggregates\TokenMetaAggregator;
/**
 * Test cases for FreqDist
 * @author yooper
 */
class FreqDistTest extends \Tests\Base
{
    
    public function testFreqDist()
    { 
        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize(self::$text);
        $tokenMetaAggregator = new TokenMetaAggregator(self::$text, $tokens);
        $freqDist = new FreqDist($tokenMetaAggregator->getAggregate());
        $this->assertTrue(count($freqDist->getHapaxes()) > 1);
        $this->assertTrue(count($freqDist->getKeys()) > 1);
    }
    
    public function testSimpleFreqDist()
    { 
        $text = "Time flies like an arrow.";
        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize($text);
        $tokenMetaAggregator = new TokenMetaAggregator($text, $tokens);
        $freqDist = new FreqDist($tokenMetaAggregator->getAggregate());
        $this->assertTrue(count($freqDist->getHapaxes()) > 1);
        
        $this->assertEquals(5, count($freqDist->getKeys()));
    }    
    
}
   
