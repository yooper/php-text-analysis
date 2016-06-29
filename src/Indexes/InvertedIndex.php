<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Queries\QueryAbstractFactory;
use TextAnalysis\Interfaces\IDataReader;
use TextAnalysis\Documents\TokensDocument;

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
    protected $index = [];
    
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
        return QueryAbstractFactory::factory($queryStr)->queryIndex($this);
    }
    
    /**
     * 
     * @param string $term
     * @return array
     */
    public function getDocumentIdsByTerm($term) 
    {
        if(!isset($this->index[$term])) {
            return [];
        }
        return $this->index[$term][self::POSTINGS];
    }
    
    /**
     * Returns the key value array with the terms found in the document for the
     * given doc id
     * @param mixed $docId
     * @return array
     */
    public function getTermsByDocumentId($docId)
    {
        $termsFoundIn = [];
        foreach($this->index as $term => $row)
        {
            if(in_array($docId, $row[self::POSTINGS])) { 
                $termsFoundIn[] = $term; 
            }
        }
        return $termsFoundIn;
    }
    
    /**
     * Add a document
     * @param TokensDocument $document
     * @return void
     */
    public function addDocument(TokensDocument $document)
    {      
        foreach($document->getDocumentData() as $term)
        {
            if(isset($this->index[$term])) {
                $this->index[$term][self::FREQ]++;
                $this->index[$term][self::POSTINGS][] = $document->getId();
            } else {
                $this->index[$term] = [
                    self::FREQ => 1,
                    self::POSTINGS => [$document->getId()]
                ];
            }
        }        
    }
    
    /**
     * True if the doc was removed
     * @param mixed $docId
     * @return boolean
     */
    public function removeDocument($docId)
    {
        $terms = $this->getTermsByDocumentId($docId);
        $flag = false;
        foreach($terms as $term)
        {            
            $flag = true;
            $row = &$this->index[$term][self::POSTINGS];            
            // remove the term altogether
            if(count($row) === 1) {
                unset($this->index[$term]);
            } else {            
                $idx = array_search($docId, $row);
                unset($row[$idx]);
                $row = array_values($row); // re-index the array   
            }
        }        
        return $flag;
    }
    
    
    /**
     * Return the internal index data structure
     * @return array
     */
    public function getIndex()
    {
        return $this->index;
    }
    
    /**
     * clean up
     */
    public function __destruct() 
    {
        unset($this->index);
    }
        
}

