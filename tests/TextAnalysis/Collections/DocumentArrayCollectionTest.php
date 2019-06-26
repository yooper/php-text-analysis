<?php
namespace Tests\TextAnalysis\Collections;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Documents\TokensDocument;

use TextAnalysis\Filters\QuotesFilter;
use TextAnalysis\Filters\LowerCaseFilter;

/**
 * Test building an index works with collections 
 *
 * @author yooper
 */
class CollectionInvertedBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testSimpleCollectionIndex()
    {
        $docs = array(
            new TokensDocument(array("marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("no","tokens"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        
        $this->assertTrue($collection->count() === 3);
        
        $this->assertEquals($collection[2]->getDocumentData(), array("no","tokens"));
    }
    
    public function testFiltersOnCollection()
    {
        $docs = array(
            new TokensDocument(array("Marquette", "Michigan's", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("Ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("No","Tokens"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        
        $filters = array(
            new LowerCaseFilter(),
            new QuotesFilter()
        );
        
        $collection->applyTransformations($filters);
        
        
        $this->assertTrue($collection->count() === 3);
        
        $this->assertEquals(array("marquette", "michigans", "hiking", "hiking", "hiking" , "camping", "swimming"), $collection[0]->getDocumentData());
        $this->assertEquals(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette"),$collection[1]->getDocumentData());
        $this->assertEquals(array("no","tokens"), $collection[2]->getDocumentData());
        
    }
}

