<?php
namespace Tests\TextAnalysis\Indexes\Builders;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Indexes\Builders\CollectionInvertedIndexBuilder;
use TextAnalysis\Documents\TokensDocument;

/**
 * Test building an index works with collections 
 *
 * @author Dan Cardin
 */
class CollectionInvertedBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCollectionIndex()
    {
        $docs = array(
            new TokensDocument(array("marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming")),
            new TokensDocument(array("ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette")),
            new TokensDocument(array("no","tokens","michigan"))
        );
        
        $collection = new DocumentArrayCollection($docs);
        $builder = new CollectionInvertedIndexBuilder($collection);
        $index = $builder->getIndex();
        
        $this->assertEquals($index['michigan'],array('freq'=>3,'postings'=>array(0,1,2)));
        
    }
}

