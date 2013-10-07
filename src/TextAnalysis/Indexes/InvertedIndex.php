<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Collections\DocumentArrayCollection;

/**
 * A implementation of the inverted document index that is popular for use in 
 * mapping tokens to documents
 * @author Dan Cardin (yooper)
 */
class InvertedIndex
{       
    protected $index = array();
    
    
    public function __construct(ICollection $collection)
    {
        $this->buildIndex($collection);
    }
    
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
     * return an array  
     */
    public function query($token);
    
}

