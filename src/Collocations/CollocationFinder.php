<?php

namespace TextAnalysis\Collocations;

use TextAnalysis\NGrams\NGramFactory;
use TextAnalysis\Analysis\FreqDist;

/**
 * Helps find popular phrases in the given set of tokens
 * @author yooper
 */
class CollocationFinder 
{
    protected $nGramSize = 2;
    
    protected $tokens = [];
    
    public function __construct(array $tokens, $nGramSize = 2) 
    {
        $this->tokens = $tokens;
        $this->nGramSize = $nGramSize;
    }
    
    public function getCollocations()
    {
        $nGramTokens = NGramFactory::create($this->tokens, $this->nGramSize);
        return (new FreqDist($nGramTokens))->getKeyValuesByFrequency();
    }
}
