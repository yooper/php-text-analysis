<?php

namespace TextAnalysis\Collocations;

/**
 * Helps find popular phrases in the given set of tokens
 * @author yooper
 */
class CollocationFinder 
{
    /**
     * The ngram size 
     * @var int
     */
    protected $nGramSize = 2;
    
    /**
     *
     * @var array
     */
    protected $tokens = [];
    
    public function __construct(array $tokens, int $nGramSize = 2) 
    {
        $this->tokens = $tokens;
        $this->nGramSize = $nGramSize;
    }
    
    /**
     * Returns a naive implementation of collocations
     * @return array
     */
    public function getCollocations()
    {
        $nGramTokens = ngrams($this->tokens, $this->nGramSize);
        return freq_dist($nGramTokens)->getKeyValuesByFrequency();
    }
    
    /**
     * Compute the Pointwise Mutual Information on the collocations
     * @return array
     */
    public function getCollocationsByPmi()
    {
        $nGramFreqDist = freq_dist(ngrams($this->tokens, $this->nGramSize));
        $unigramsFreqDist = freq_dist($this->tokens);
        
        $dataSet = [];
        foreach($nGramFreqDist->getKeys() as $nGramToken)
        {            
            $tokens = explode(" ", $nGramToken);
            $tally = 1;
            foreach($tokens as $unigramToken)
            {      
                $tally *= $unigramsFreqDist->getKeyValuesByWeight()[$unigramToken];
            }        
            
            // get probabilities of all tokens
            $dataSet[$nGramToken] = log($nGramFreqDist->getKeyValuesByWeight()[$nGramToken] / $tally );            
        }
        arsort($dataSet);
        return $dataSet;
    }
}
