<?php
namespace Tests\TextAnalysis\Aggregates;

use TextAnalysis\Aggregates\ConcordanceAggregate;
use TextAnalysis\Aggregates\TokenMetaAggregator;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Tokenizers\PennTreeBankTokenizer;


/**
 * Description of ConcordanceAggregateTest
 *
 * @author yooper
 */
class ConcordanceAggregateTest extends \Tests\Base
{    
    public function testGeneralTokenizerConcordance()
    {    

        $tokenizer = new GeneralTokenizer();
        $aggregator = new TokenMetaAggregator(self::$text, $tokenizer->tokenize(self::$text));
        
        $tokenAggregates = $aggregator->getAggregate();
              
        $concordance = new ConcordanceAggregate(self::$text,$tokenAggregates);
        
        $this->assertCount(2, $concordance->getAggregate("mansion"));
        
        $this->assertCount(517, $concordance->getAggregate("Tom"));
    }
    
    public function testPennBankTreeTokenizerConcordance()
    {    

        $tokenizer = new PennTreeBankTokenizer();
        $aggregator = new TokenMetaAggregator(self::$text, $tokenizer->tokenize(self::$text));
        
        $tokenAggregates = $aggregator->getAggregate();
              
        $concordance = new ConcordanceAggregate(self::$text,$tokenAggregates);
        
        $this->assertCount(2, $concordance->getAggregate("mansion"));
        
        $this->assertCount(624, $concordance->getAggregate("Tom"));
    }    
    
    
    
}
