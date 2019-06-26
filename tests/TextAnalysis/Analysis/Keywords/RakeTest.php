<?php

namespace Tests\TextAnalysis\Analysis\Keywords;

use TextAnalysis\Analysis\Keywords\Rake;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\SpacePunctuationFilter;

/**
 * Test Rake algorithm
 * @author yooper
 */
class RakeTest extends \PHPUnit\Framework\TestCase
{
    public function testRake()
    {
        $stopwords = array_map('trim', file(VENDOR_DIR.'yooper/stop-words/data/stop-words_english_1_en.txt'));
        // all punctuation must be moved 1 over. Fixes issues with sentences
        $testData = (new SpacePunctuationFilter([':','\/']))->transform($this->getTestData());
        //rake MUST be split on whitespace and new lines only
        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);        
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new StopWordsFilter($stopwords), false)
                ->applyTransformation(new PunctuationFilter(['@',':','\/']), false)
                ->applyTransformation(new CharFilter(), false);
                
        $rake = new Rake($tokenDoc, 3);
        $results = $rake->getKeywordScores();
        $this->assertArrayHasKey('minimal generating sets', $results); 
        $this->assertArrayHasKey('8/8/2016 5:51 pm', $results);         
    }
    
    public function testSimplifiedRake()
    {
        $stopwords = array_map('trim', file(VENDOR_DIR.'yooper/stop-words/data/stop-words_english_1_en.txt'));
        // all punctuation must be moved 1 over. Fixes issues with sentences
        $testData = (new SpacePunctuationFilter([':','\/']))->transform($this->getTestData());
        //rake MUST be split on whitespace and new lines only
        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);        
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new StopWordsFilter($stopwords), false)
                ->applyTransformation(new PunctuationFilter(['@',':','\/']), false)
                ->applyTransformation(new CharFilter(), false);

        $rake = rake($tokenDoc->toArray(), 3);
        $results = $rake->getKeywordScores();
        $this->assertArrayHasKey('minimal generating sets', $results); 
        $this->assertArrayHasKey('8/8/2016 5:51 pm', $results);                 
    }
    
    /**
     * Sample test data 
     * @return string
     */
    public function getTestData()
    {
        return <<<DATA
Compatibility of systems of linear constraints over the set of natural numbers.
Criteria of compatibility of a system of linear Diophantine equations, strict inequations,
and nonstrict inequations are considered. Upper bounds for components of a minimal set
of solutions and algorithms of construction of minimal generating sets of solutions for all
types of systems are given. These criteria and the corresponding algorithms for
constructing a minimal supporting set of solutions can be used in solving all the
considered types of systems and systems of mixed types.  Published 8/8/2016 5:51 pm      
DATA;
    }
}