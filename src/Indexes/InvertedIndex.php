<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Queries\QueryAbstractFactory;
use TextAnalysis\Queries\SingleTermQuery;
use TextAnalysis\Queries\MultiTermQuery;

use TextAnalysis\Interfaces\IDataReader;
/**
 * A implementation of the inverted document index that is popular for use in 
 * mapping tokens to documents
 * @author yooper (yooper)
 */
class InvertedIndex
{     
    const FREQ = 'freq';
    const POSTINGS = 'postings';
    
    /**
     * The index
     * @var array 
     */
    protected $index = array();
    
    /**
     * Pass in the pre-built indexes for doing lookups
     * @param IDataReader $reader 
     */
    public function __construct(IDataReader $reader)
    {
        $this->index = $reader->read();
    }
        
    /**
     * Accepts a string a query and returns the set of documents relevant 
     * to the query
     * @param string $queryStr
     * @return array 
     */
    public function query($queryStr)
    {
        $queryObj = QueryAbstractFactory::factory($queryStr);
        
        if($queryObj instanceof SingleTermQuery && isset($this->index[$queryObj->getQuery()])) { 
            return $this->index[$queryObj->getQuery()][self::POSTINGS];
        } else if($queryObj instanceof MultiTermQuery) {
            return $this->getMultiTermResults($queryObj->getQuery());
        }
        
        //no results available
        return array();            
    }
    
    /**
     * Return the array of documents the search terms where found in
     * @param array $tokens
     * @return array 
     */
    protected function getMultiTermResults(array $terms)
    {
        $docList = array();
        foreach($terms as $term) { 
            if(isset($this->index[$term])) {
                $docList = array_merge($docList, $this->index[$term][self::POSTINGS]);
            }
        }
        //re-index and unique the array
        return array_values(array_unique($docList));
    }
        
}

