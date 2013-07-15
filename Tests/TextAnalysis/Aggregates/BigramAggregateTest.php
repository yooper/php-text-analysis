<?php
namespace Tests\TextAnalysis\Aggregates;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use \ArrayIterator;


/** *
 * @author yooper
 */
class BigramAggregateTest extends \PHPUnit_Framework_TestCase
{
    
    public function testBigram()
    {    
        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize("July 7th 2013 was a rainy, stormy day");
        $bigramInstance = new \TextAnalysis\Aggregates\BigramAggregate($tokens);
        $aggregate = $bigramInstance->getAggregateTokens();        
        $this->assertCount(7, $aggregate);
        $this->assertCount(7, $bigramInstance->getAggregateStrings());
    }
     
        
}
