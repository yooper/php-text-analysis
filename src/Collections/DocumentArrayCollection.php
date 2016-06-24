<?php

namespace TextAnalysis\Collections;
use TextAnalysis\Interfaces\ICollection;
use TextAnalysis\Interfaces\ITokenTransformation;
use TextAnalysis\Documents\DocumentAbstract;
use TextAnalysis\Interfaces\IExtractStrategy;

/**
 */
class DocumentArrayCollection implements ICollection
{
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

    /**
     * An array of DocumentAbstract objects
     * @param array $documents 
     */
    public function __construct(array $documents)
    {
        $this->documents = $documents;
        $this->currentDocument = reset($this->documents);
    }

    public function __destruct()
    {
        unset($this->documents);
        unset($this->currentDocument);
    }
    
    /**
     * @param array $transformations An array of 
     * Apply the collection of token transformers to the documents
     */
    public function applyTransformations(array $transformations)
    {
        /** @var DocumentAbstract $document **/
        foreach($this->documents as $document) 
        { 
            /** @var ITokenTransformation $transformation **/
            foreach($transformations as $transformation)
            {
                $document->applyTransformation($transformation);
            }
        }        
    }
    
    /**
     * @param array $stemmers An array of stemmers
     * Apply the collection of stem transformers to the documents
     */
    public function applyStemmers(array $stemmers)
    {
        /** @var DocumentAbstract $document **/
        foreach($this->documents as $document) 
        { 
            /** @var ITokenTransformation $transformation **/
            foreach($stemmers as $stemmer)
            {
                $document->applyStemmer($stemmer);
            }
        }        
    }   
    
    /**
     * Apply extract to the document to pull out all the points of 
     * interest
     * @param IExtractStrategy $extract
     * @return array
     */
    public function applyExtract(IExtractStrategy $extract)
    {
        /* @var $document \TextAnalysis\Documents\TokensDocument */
        $found = [];
        foreach($this->documents as $document)
        {
            $found = array_merge($document->applyExtract($extract), $found);
        }
        return $found;
    }
    

    public function rewind()
    {
        reset($this->documents);
        $this->currentDocument = current($this->documents);
    }
    
    
    public function next()
    {
        $this->currentDocument = next($this->documents);
    }
    
    /**
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->currentDocument === null;
    }
    
    /**
     *
     * @return DocumentAbstract
     */
    public function current()
    {
        return $this->currentDocument;
    }

    /**
     *
     * @param mixed $key
     * @param DocumentAbstract $value
     * @return boolean 
     */
    public function offsetSet($key, $value)
    {
        if(!isset($key)) { 
            $this->documents[] = $value;
            return true;
        }
        
        $this->documents[$key] = $value;
        return $value;
         
        
    }
    
    /**
     *
     * @param mixed $key
     * @return null 
     */
    public function offsetUnset($key)
    {
        if (isset($this->documents[$key])) {
            $deleted = $this->documents[$key];
            unset($this->documents[$key]);
             
             return $deleted;
         }
          return null;
    }
    
    /**
     *
     * @param mixed $key
     * @return DocumentAbstract 
     */
    public function offsetGet($key)
    {
        return $this->documents[$key];
    }
    
    /**
     *
     * @param mixed $key
     * @return boolean 
     */
    public function offsetExists($key)
    {
        return isset($this->documents[$key]);
    }

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->documents);
    }

    /**
     *
     * @return \ArrayIterator 
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->documents);
    }
        
}