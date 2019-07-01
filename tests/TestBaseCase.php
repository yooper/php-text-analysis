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
class TestBaseCase extends \PHPUnit\Framework\TestCase
{
    /**
     * The default text that is used with a lot of test cases
     * @var string 
     */
    static protected $text = null;
    static protected $text_ptbr = null;

    /**
     *
     * @var InvertedIndex
     */
    protected $invertedIndex = null;
    
    /**
     * Preload and cache a corpus for testing purposes 
     */
    public function setUp() : void
    {
        parent::setUp();
        //load the text file
        if(is_null(self::$text)) { 
            self::$text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');
            self::$text_ptbr = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'/ptbr/Dom_Casmurro.txt');
        }        
    }
    
    public function getText(string $language = 'en') : string
    {
        switch($language) {
            case 'ptbr':
                return self::$text_ptbr;
                break;
            case 'en':
                return self::$text;
                break;
            default:
                return self::$text;
                break;
        }
    }

    /**
     * 
     * @param string $className
     * @param array $params
     * @param array $constructorArgs
     * @return \Mockery\Mock
     */
    public function getPartialMock($className,array $params, array $constructorArgs = [])
    {
        $partialMethods = implode(",", array_keys($params));
        $partialMockStr = "{$className}[{$partialMethods}]";
        $mock = Mockery::mock($partialMockStr, $constructorArgs);
        foreach($params as $methodName => $returnValue)
        {
            $mock->shouldReceive($methodName)
                    ->andReturn($returnValue);
        }
        return $mock;
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
            $collection = new DocumentArrayCollection($docs);
            $builder = new CollectionInvertedIndexBuilder($collection); 
            $dataReader = new ArrayDataReaderAdapter($builder->getIndex());
            $this->invertedIndex = new InvertedIndex($dataReader);  
        }
        return $this->invertedIndex;
    }
}


