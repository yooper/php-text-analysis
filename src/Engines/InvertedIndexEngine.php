<?php

namespace TextAnalysis\Engines;

use TextAnalysis\Indexes\InvertedIndex;
use TextAnalysis\Adapters\JsonDataAdapter;
use RuntimeException;
use TextAnalysis\Documents\TokensDocument;

/**
 * Offers a simple inverted index search engine
 * @author yooper
 */
class InvertedIndexEngine 
{
    const INDEX_FILE = 'index.json';
    const METADATA_FILE = 'metadata.json';
    
    
    /**
     *
     * @var string The directory where all the data and metadata files are stored
     */
    protected $dataDir = null;
        
    /**
     *
     * @var InvertedIndex
     */
    protected $invertedIndex = null;
    
    /**
     *
     * @var array
     */
    protected $metadata = [];
    
    /**
     *
     * @var boolean tracks if the index or metadata has been altered
     * and requires persisting to the database
     */
    protected $isDirty = false;
    
    public function __construct($dataDir) 
    {
        $this->dataDir = $dataDir;
    }
    
     
    /**
     * @return InvertedIndex
     * @throws RuntimeException
     */
    public function getInvertedIndex()
    {
        if(empty($this->invertedIndex) && file_exists($this->dataDir.self::INDEX_FILE)) {                       
            $this->invertedIndex = new InvertedIndex(
                new JsonDataAdapter(file_get_contents($this->dataDir.self::INDEX_FILE)));
        } elseif(!file_exists($this->dataDir.self::INDEX_FILE)) {
            throw new RuntimeException("Inverted index data file does not exist");
        }
        return $this->invertedIndex;
    }
    
    /**
     * @param string $queryStr
     * @return array
     */
    public function query($queryStr)
    {
        return $this->getInvertedIndex()->query($queryStr);
    }
    
    /**
     * Added a document to the index, default overwrites the existing
     * @param TokensDocument $document
     */
    public function addDocument(TokensDocument $document)
    {
        $this->isDirty = true;
        $this->removeDocument($document);
        $this->getInvertedIndex()->addDocument($document);
        $this->getMetadata()[$document->getId()] = $document->getMetadata();
    }
    
    /**
     * Removes the document from the index
     * @param TokensDocument $document
     */
    public function removeDocument(TokensDocument $document)
    {
        $this->isDirty = true;
        $this->getInvertedIndex()->removeDocument($document->getId());
        unset($this->metadata[$document->getId()]);
    }
    
    /**
     * returns if the index/metadata has been updated since loaded into memory
     * @return boolean
     */
    public function getIsDirty()
    {
        return $this->isDirty;
    }
    
    /**
     * key value array, key is the document id
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    
    /**
     * Persists the data back into the data dir, if it has been changed
     */
    public function persist()
    {
        if($this->getIsDirty()) {
            file_put_contents(
                    $this->dataDir.self::INDEX_FILE, json_encode($this->getInvertedIndex()->getIndex()));
            file_put_contents($this->dataDir.self::METADATA_FILE, json_encode($this->getMetadata()) );
        }
    }
    
    public function __destruct() 
    {
        $this->persist();
        unset($this->invertedIndex);
        unset($this->isDirty);
        unset($this->metadata);
    }
    
}
