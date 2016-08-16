<?php

namespace Test\TextAnalysis\Collocations;

use TextAnalysis\Collocations\CollocationFinder;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\SpacePunctuationFilter;
use TextAnalysis\Filters\QuotesFilter;
use TestBaseCase;

/**
 * Test the collocation abilities
 * @author yooper
 */
class CollocationFinderTest extends TestBaseCase
{
    
    public function testCollocationFinder()
    {
        $stopwords = array_map('trim', file(VENDOR_DIR.'yooper/stop-words/data/stop-words_english_1_en.txt'));
        $testData = (new SpacePunctuationFilter())->transform(self::$text);
        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);        
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new PunctuationFilter([]), false)
                ->applyTransformation(new StopWordsFilter($stopwords))
                ->applyTransformation(new QuotesFilter())
                ->applyTransformation(new CharFilter());
        
        $finder = new CollocationFinder($tokenDoc->toArray());
        $this->assertArrayHasKey('injun joe', $finder->getCollocations());
    }
    
    public function testCollocationFinderTrigram()
    {
        $stopwords = array_map('trim', file(VENDOR_DIR.'yooper/stop-words/data/stop-words_english_1_en.txt'));
        $testData = (new SpacePunctuationFilter())->transform(self::$text);
        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);        
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new PunctuationFilter([]), false)
                ->applyTransformation(new StopWordsFilter($stopwords))
                ->applyTransformation(new QuotesFilter())
                ->applyTransformation(new CharFilter());
        
        $finder = new CollocationFinder($tokenDoc->toArray(), 3);
        $this->assertArrayHasKey('finn red handed', $finder->getCollocations());
    }    
    
    public function testGetCollocationsByPmi()
    {
        $testData = (new SpacePunctuationFilter())->transform(self::$text);
        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);        
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
                ->applyTransformation(new PunctuationFilter([]), false)
                ->applyTransformation(new StopWordsFilter([]))
                ->applyTransformation(new QuotesFilter())
                ->applyTransformation(new CharFilter());
        
        $finder = new CollocationFinder($tokenDoc->toArray(), 2);
        $this->assertArrayHasKey('outlying cottages', $finder->getCollocationsByPmi());
 
    }      
    
    
}
