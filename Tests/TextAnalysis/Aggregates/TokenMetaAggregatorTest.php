<?php

namespace Tests\TextAnalysis\Aggregates;
use TextAnalysis\Aggregates\TokenMetaAggregator;
use \ArrayIterator;
use TextAnalysis\Token;
use TextAnalysis\Tokenizers\GeneralTokenizer;

/**
 * Test the TokenMetaAggregator
 * @author yooper
 */
class TokenMetaAggregatorTest extends \PHPUnit_Framework_TestCase
{
    const TEXT_1 = "Well, old Macdonald had a farm, ee-i-ee-i-o\n
        And on his farm he had a cow, ee-i-ee-i-o\n
        With a moo-moo here, and a moo-moo there\n
        Here a moo, there a moo, everywhere a moo-moo\n
        Old Macdonald had a farm, ee-i-ee-i-o\n";
    
    public function testSimpleAggregator()
    {
        $tokenizer = new GeneralTokenizer();
        $aggregator = new TokenMetaAggregator(self::TEXT_1, $tokenizer->tokenize(self::TEXT_1));
        
        $data = $aggregator->getAggregate();
        
        $this->assertCount(21, $data);
        $this->assertCount(3, $data->offsetGet('ee-i-ee-i-o')->getPositions());
        $this->assertCount(3, $data->offsetGet('moo-moo')->getPositions());
        
    }
    
}


