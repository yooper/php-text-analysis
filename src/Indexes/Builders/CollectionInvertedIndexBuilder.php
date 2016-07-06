<?php
namespace TextAnalysis\Indexes\Builders;
use TextAnalysis\Interfaces\ICollection;
use TextAnalysis\Analysis\FreqDist;


/**
 * A really easy way to build an inverted index from a document array collection
 * @author yooper
 */
class CollectionInvertedIndexBuilder
{
    const FREQ = 'freq';
    const POSTINGS = 'postings';
    
    /**
     * The index that gets written out
     * @var type 
     */
    protected $index = array();
    
    /**
     *
     * @var ICollection;
     */
    protected $collection;
    
    /**
     * The collection of metadata for all the documents, indexed by doc id
     * @var array
     */
    protected $metadata;
    
    /**
     * Build the index from the collection of documents
     * @param ICollection $collection 
     */
    public function __construct(ICollection &$collection)
    {
        $this->collection = $collection;
        $this->buildIndex();
    }
    
    /**
     * Builds the internal index data structure using the provided collection
     */
    protected function buildIndex()
    {
        //first pass compute frequencies and all the terms in the collection
        foreach($this->collection as $id => $document) { 
            $freqDist = new FreqDist($document->getDocumentData());
            foreach($freqDist->getKeyValuesByFrequency() as $term => $freq) { 
                if(!isset($this->index[$term])) { 
                    $this->index[$term] = array(self::FREQ => 0, self::POSTINGS => array());
                }
                $this->index[$term][self::FREQ] += $freq;
                $this->index[$term][self::POSTINGS][] = $id;
            }
            $this->metadata[$id] = $document->getMetadata();
        }          
    }
    
    /**
     * Get the computed index
     * @return array
     */
    public function getIndex()
    {
        return $this->index;
    }
    
    /**
     * @return the metadata indexed by document id
     */
    public function getMetadata()
    {
        $this->metadata;
    }
    
    
    public function __destruct() 
    {
        unset($this->index);
        unset($this->collection);
    }
}

