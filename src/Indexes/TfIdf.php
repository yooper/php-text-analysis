<?php
namespace TextAnalysis\Indexes;

use TextAnalysis\Analysis\FreqDist;
use TextAnalysis\Interfaces\ICollection;
use TextAnalysis\Documents\DocumentAbstract;

/**
 * An implementation of the TF Idf Algorithm
 * @author yooper (yooper)
 */
class TfIdf 
{
    /**
     * Default mode of weighting uses frequency 
     */
    const FREQUENCY_MODE = 1;
    const BOOLEAN_MODE = 2;
    const LOGARITHMIC_MODE = 3;
    const AUGMENTED_MODE = 4;
    
    
    /**
     * Store the idf for each token
     * @var array of floats
     */
    protected $idf = array();
    
    /**
     * @param ICollection $collection The collection of documents to use for computing the tfidf
     */
    public function __construct(ICollection $collection)
    {
        $this->buildIndex($collection);
    }
        
    protected function buildIndex(ICollection $collection)
    {                
        foreach($collection as $id => $document){
            $freqDist = freq_dist($document->getDocumentData());
            foreach($freqDist->getKeyValuesByFrequency() as $key => $freq) { 
                if(!isset($this->idf[$key])) { 
                    $this->idf[$key] = 0;
                }
                $this->idf[$key]++;
            }
        }
        
        $count = count($collection);
        foreach($this->idf as $key => &$value) { 
            $value = log(($count)/($value));
        }
    }
    
    /**
     * If a token is provided return just the idf for that token, 
     * else return the entire idf
     * @param $token string
     * @return float|array 
     */
    public function getIdf($token = null)
    {
        if(!$token){
            return $this->idf;
        }
        return $this->idf[$token];
    }
    
    /**
     * Get the term frequency
     * @param DocumentAbstract $document - the document to evaluate
     * @param string $token The token to look for
     * @param int $mode The type of term frequency to use
     * @return int|float 
     */
    public function getTermFrequency(DocumentAbstract $document, $token, $mode = 1)
    {        
        $freqDist = new FreqDist($document->getDocumentData());
        $keyValuesByWeight = $freqDist->getKeyValuesByFrequency();
        
        //The token does not exist in the document
        if(!isset($keyValuesByWeight[$token])) {
            return 0;
        }
        
        switch($mode) { 

            case self::BOOLEAN_MODE:
                //a test was already performed if the token exists in the document
                //just return true
                return 1;
            case self::LOGARITHMIC_MODE:
                return  log($keyValuesByWeight[$token]+1);
                case self::AUGMENTED_MODE:

                    //FreqDist getKeyValuesByFrequency is already sorted
                    //in ascending order
                    $maxFrequency = current($keyValuesByWeight);
                    return 0.5 + (0.5 * $keyValuesByWeight[$token]) / $maxFrequency;
 
                case self::FREQUENCY_MODE:
                default:
                    return $keyValuesByWeight[$token];
            }        
    }
    
    /**
    * Get the term frequency
    * @param DocumentAbstract $document - the document to evaluate
    * @param string $token The token to look for
    * @param int $mode The type of term frequency to use
    * @return float 
    */
    public function getTfIdf(DocumentAbstract $document, $token, $mode = 1)
    {
        return $this->getTermFrequency($document, $token, $mode) * $this->getIdf($token);
    }
    
    
}

