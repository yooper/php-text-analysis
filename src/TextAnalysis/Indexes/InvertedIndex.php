<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Interfaces\ICollection;

/**
 * A implementation of the inverted document index that is popular for use in 
 * mapping tokens to documents
 * @author Dan Cardin (yooper)
 */
class InvertedIndex
{       
    /**
     * The index
     * @var array 
     */
    protected $index = array();
    
    /**
     *
     * @param ICollection $collection 
     */
    public function __construct(ICollection $collection)
    {
        $this->buildIndex($collection);
    }
    
    /**
     * Build the index from the provided collection
     * @param ICollection $documentCollection 
     */
    protected function buildIndex(ICollection $documentCollection)
    {        
        foreach($documentCollection as $id => $document) {
            $tokens = $document->getDocumentData();
            foreach($tokens as $token) { 
                if(isset($this->index[$token])) { 
                    $this->index[$token] = array();
                }
                
                $this->index[$token][] = $id;                
            }
        }   
    }
    

    /**
     * Accepts a string a query and returns the set of documents relevant 
     * to the query
     * @param string $query
     * @return array 
     */
    public function query($query)
    {
        return array();
    }
    
}

