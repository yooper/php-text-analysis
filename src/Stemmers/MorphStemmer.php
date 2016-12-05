<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Indexes\WordnetIndex;
use TextAnalysis\Corpus\WordnetCorpus;
use TextAnalysis\Interfaces\IStemmer;

/**
 * 
 * Use a morph stemmer to stem to the base word
 * @author dcardin
 */
class MorphStemmer implements IStemmer
{
    /**
     *
     * @var array
     */
    protected $cache = [];
   
    /**
     *
     * @var WordnetIndex
     */
    protected $wordnetIndex = null;
    
    public function __construct() 
    {
        $this->wordnetIndex = new WordnetIndex(new WordnetCorpus(get_storage_path('corpora/wordnet')));
    }
    
    /**
     * 
     * @return WordnetIndex
     */
    public function getWordnetIndex()
    {
        return $this->wordnetIndex;
    }
    
    /**
     * 
     * @param string $token
     * @return string
     */
    public function stem($token) 
    {
        if(!isset($this->cache[$token])) {
            if(mb_strlen($token) < 3){ 
                $this->cache[$token] = $token;
            } else {
                $this->cache[$token] = $this->getWordnetIndex()->getMorph($token);
            }            
        }
        return $this->cache[$token];        
    }
    
    public function __destruct() 
    {
        unset($this->cache);
        unset($this->wordnetIndex);
    }
   

}
