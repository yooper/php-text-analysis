<?php

use TextAnalysis\Indexes\InvertedIndex;
use TextAnalysis\Collections\DocumentArrayCollection;
use TextAnalysis\Indexes\Builders\CollectionInvertedIndexBuilder;
use TextAnalysis\Adapters\ArrayDataReaderAdapter;
use TextAnalysis\Documents\TokensDocument;

/**
 * 
 * @author yooper
 */
class TestBaseCase extends \PHPUnit_Framework_TestCase
{
    /**
     * The default text that is used with a lot of test cases
     * @var string 
     */
    static protected $text = null;

    /**
     *
     * @var InvertedIndex
     */
    protected $invertedIndex = null;
    
    /**
     * Preload and cache a corpus for testing purposes 
     */
    public function setUp()
    {
        parent::setUp();
        //load the text file
        if(is_null(self::$text)) { 
            self::$text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');
        }        
    }
    
    /**
     * 
     * @return InvertedIndex
     */
    public function getInvertedIndex()
    {
        if(!$this->invertedIndex) {
            $docs = [
                new TokensDocument(["marquette", "michigan", "hiking", "hiking", "hiking" , "camping", "swimming"]),
                new TokensDocument(["ironwood", "michigan", "hiking", "biking", "camping", "swimming","marquette"]),
                new TokensDocument(["no","tokens","michigan"])
            ];
            $builder = new CollectionInvertedIndexBuilder(new DocumentArrayCollection($docs)); 
            $this->invertedIndex = new InvertedIndex(new ArrayDataReaderAdapter($builder->getIndex()));  
        }
        return $this->invertedIndex;
    }
}


