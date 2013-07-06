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
class ConcordanceAggregateTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneralTokenizerConcordance(){    

        $text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');

        $tokenizer = new GeneralTokenizer();
        $aggregator = new TokenMetaAggregator($text, $tokenizer->tokenize($text));
        
        $tokenAggregates = $aggregator->getAggregate();
              
        $concordance = new ConcordanceAggregate($text,$tokenAggregates);
        
        $this->assertCount(2, $concordance->getAggregate("mansion"));
        
        $this->assertCount(517, $concordance->getAggregate("Tom"));
    }
    
    public function testPennBankTreeTokenizerConcordance(){    

        $text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');

        $tokenizer = new PennTreeBankTokenizer();
        $aggregator = new TokenMetaAggregator($text, $tokenizer->tokenize($text));
        
        $tokenAggregates = $aggregator->getAggregate();
              
        $concordance = new ConcordanceAggregate($text,$tokenAggregates);
        
        $this->assertCount(2, $concordance->getAggregate("mansion"));
        
        $this->assertCount(624, $concordance->getAggregate("Tom"));
    }    
}
