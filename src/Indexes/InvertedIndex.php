<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Queries\QueryAbstractFactory;
use TextAnalysis\Queries\SingleTermQuery;
use TextAnalysis\Utilities\Text;

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
        
        if($queryObj instanceof SingleTermQuery && isset($this->index[$queryObj->getQuery()[0]])) { 
            return [$queryObj->getQuery()[0] => $this->index[$queryObj->getQuery()[0]][self::POSTINGS]];
        } else { 
            return $this->getPartialMatches($queryObj);                    
        }                   
    }
    
    /**
     * Returns the document ids of the matching partial terms, they key is the 
     * term that contains the query string(s)
     * @param QueryAbstractFactory $queryObj
     * @return array
     */
    public function getPartialMatches(QueryAbstractFactory $queryObj)
    {
        $terms = array_keys($this->index);        
        $found = [];
        
        foreach($terms as $term) 
        {
            foreach($queryObj->getQuery() as $queryTerm)
            {
                if(Text::contains($term, $queryTerm)) {
                    $found[$term] = $this->index[$term][self::POSTINGS];
                }
            }
        }
        return $found;
    }    
        
}

