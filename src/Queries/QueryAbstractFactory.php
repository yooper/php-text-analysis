<?php
namespace TextAnalysis\Queries;
use TextAnalysis\Tokenizers\GeneralTokenizer;



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
        
        if(count($tokens) === 1) { 
            return new SingleTermQuery($queryString);
        } else {
            return new MultiTermQuery($queryString);
        }
    }
    
    /**
     * @return array
     */
    public abstract function getQuery();
}


