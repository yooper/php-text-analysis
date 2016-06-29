<?php
namespace TextAnalysis\Queries;

use TextAnalysis\Tokenizers\GeneralTokenizer;
use TextAnalysis\Indexes\InvertedIndex;


/**
 * Examines the string and determines the type of query analyzer to use
 * @author yooper (yooper)
 */
abstract class QueryAbstractFactory 
{
    /**
     * The original string passed into factory method
     * @param string $queryString 
     */
    protected $queryString;
    
    /**
     * The query string passed in
     * @param string $queryString 
     */
    protected function __construct($queryString)
    {
        $this->queryString = $queryString;
    }
    
    /**
     * 
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }
    
    /**
     *
     * @param string $queryString
     * @return QueryAbstractFactory
     */
    public static function factory($queryString)
    {
        $tokenizer = new GeneralTokenizer();
        $tokens = $tokenizer->tokenize($queryString);
        
        if(in_array($queryString[0], ['"',"'"]) && in_array($queryString[strlen($queryString)-1], ['"',"'"])) {
            return new QuotedQuery($queryString);
        } elseif(count($tokens) === 1) { 
            return new SingleTermQuery($queryString);
        } else {
            return new MultiTermQuery($queryString);
        }
    }
    
    /**
     * Each query type is going to interact with the inverted index in its own way
     */
    public abstract function queryIndex(InvertedIndex $invertedIndex);
    
    /**
     * @return array
     */
    public abstract function getQuery();
}


