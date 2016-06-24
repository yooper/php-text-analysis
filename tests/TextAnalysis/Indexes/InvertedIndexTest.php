<?php
namespace Tests\TextAnalysis\Indexes;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Indexes\Builders\CollectionInvertedIndexBuilder;
use TextAnalysis\Indexes\InvertedIndex;
use TextAnalysis\Adapters\ArrayDataReaderAdapter;

/**
 * Test the inverted index
 * @author yooper
 */
class InvertedIndexTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleTermSearchTermExists()
    {
        $docs = array(
            new TokensDocument(array("marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("no","tokens","michigan"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        $builder = new CollectionInvertedIndexBuilder($collection); 
        
        $adapter = new ArrayDataReaderAdapter($builder->getIndex());
        
        $invertedIndex = new InvertedIndex($adapter);
        
        $this->assertEquals(['michigan' => [0,1,2]], $invertedIndex->query("michigan"));
        $this->assertEquals(['swimming' => [0,1]], $invertedIndex->query("swimming"));
    }
    
    public function testMultiTermSearchTermExists()
    {
        $docs = array(
            new TokensDocument(array("marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("no","tokens","michigan"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        $builder = new CollectionInvertedIndexBuilder($collection); 
        
        $adapter = new ArrayDataReaderAdapter($builder->getIndex());
        
        $invertedIndex = new InvertedIndex($adapter);
        
        // the order has changed in the test cases since the php version bump
        $this->assertCount(2, $invertedIndex->query("ironwood michigan"));
        $this->assertCount(2, $invertedIndex->query("no ironwood"));
    }    
    
    public function testSingleTermSearchTermDoesNotExists()
    {
        $docs = array(
            new TokensDocument(array("no","tokens"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        $builder = new CollectionInvertedIndexBuilder($collection); 
        
        $adapter = new ArrayDataReaderAdapter($builder->getIndex());
        
        $invertedIndex = new InvertedIndex($adapter);
        
        $this->assertEquals(array(), $invertedIndex->query("none"));
        $this->assertEquals(array(), $invertedIndex->query("php"));        
    }    
    
}
