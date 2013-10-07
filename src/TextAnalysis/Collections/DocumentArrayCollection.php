<?php

namespace TextAnalysis\Collections;
use TextAnalysis\Interfaces\ICollection;
use TextAnalysis\Interfaces\ITokenTransformation;
use TextAnalysis\Documents\DocumentAbstract;

/**
 */
class DocumentArrayCollection implements ICollection
{
    const CLASS_AS_KEY = 1;
    const OFFSET_AS_KEY = 2;

    /**
     * An array of documents
     * @var array
     */
    protected $documents; // The documents container

    
    /**
     * When looped through this is the current document 
     * @var DocumentAbstract
     */
    protected $currentDocument = null;

    public function __construct(array $documents)
    {
        $this->documents = $documents;
        $this->currentDocument = reset($this->documents);
    }

    public function __destruct()
    {
        unset($this->documents);
        $this->currentDocument = null;
    }
    
    /**
     * @param array $transformations An array of 
     * Apply the collection of token transformers to the documents
     */
    public function applyTransformations(array $transformations)
    {
        /** @var DocumentAbstract $document **/
        foreach($this->documents as $document) { 
            /** @var ITokenTransformation $transformation **/
            foreach($transformations as $transformation){
                $document->applyTransformation($transformation);
            }
        }        
    }
    

    // ====== Implementation of \Iterator interface =========
    public function rewind()
    {
        reset($this->documents);
        $this->currentDocument = current($this->documents);
    }
    public function next()
    {
        $this->currentDocument = next($this->documents);
    }
    public function valid()
    {
        return $this->currentDocument === null;
    }
    public function current()
    {
        return $this->currentDocument;
    }

    
    public function offsetSet($key, $value)
    {
        if(!isset($key)) { 
            $this->documents[] = $value;
            return true;
        }
        
        $this->documents[$key] = $value;
        return $value;
         
        
    }
    
    public function offsetUnset($key)
    {
        if (isset($this->documents[$key])) {
            $deleted = $this->documents[$key];
            unset($this->documents[$key]);
             
             return $deleted;
         }
          return null;
    }
    public function offsetGet($key)
    {
        return $this->documents[$key];
    }
    public function offsetExists($key)
    {
        return isset($this->documents[$key]);
    }

    
    public function count()
    {
        return count($this->documents);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->documents);
    }
        
}