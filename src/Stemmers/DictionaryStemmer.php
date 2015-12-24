<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\ISpelling;
use TextAnalysis\Interfaces\IStemmer;

/**
 * Use a stemmer paired with a dictionary lookup service in an attempt to normalize
 * tokens
 * @author dcardin
 */
class DictionaryStemmer implements IStemmer
{
    /**
     *
     * @var IStemmer
     */
    protected $stemmer;
    
    /**
     *
     * @var ISpelling
     */
    protected $spell;
    
    /**
     * Holds an array of words that are excluded from the stemmer algorithm
     * @var array
     */
    protected $whiteList = [];
    
    
    /**
     * 
     * @param ISpelling $spell
     * @param IStemmer $stemmer
     * @param array $whiteList
     */
    public function __construct(ISpelling $spell, IStemmer $stemmer, $whiteList = [])
    {
        $this->stemmer = $stemmer;
        $this->spell = $spell;
        $this->whiteList = $whiteList;
    }
    
    /**
     * Stem and then look up the word
     * @param string $token
     */
    public function stem($token) 
    {
        if(in_array($token, $this->whiteList)) { 
            return $token;
        }
        return $this->spell->suggest( $this->stemmer->stem($token) )[0];
    }
}
