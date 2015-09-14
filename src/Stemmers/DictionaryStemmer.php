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
     * 
     * @param ISpelling $spell
     * @param IStemmer $stemmer
     */
    public function __construct(ISpelling $spell, IStemmer $stemmer)
    {
        $this->stemmer = $stemmer;
        $this->spell = $spell;
    }
    
    /**
     * Stem and then look up the word
     * @param string $token
     */
    public function stem($token) 
    {
        return $this->spell->suggest( $this->stemmer->stem($token) )[0];
    }
}
