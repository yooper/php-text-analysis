<?php

namespace TextAnalysis\Analysis\Keywords;

use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\NGrams\NGramFactory;
use TextAnalysis\Analysis\FreqDist;

/**
 * A PHP implementation of  Rapid Automatic Keyword Extraction algorithm (RAKE)
 * @author yooper
 */
class Rake 
{
    /**
     *
     * @var int 
     */
    protected $nGramSize = 0;
    
    /**
     * @var ContentDocument;
     */
    protected $document = null;
    
    /**
     *
     * @var array
     */
    protected $tokens = [];
           
    /**
     * 
     * @param TokensDocument $document
     * @param int $nGramSize
     */
    public function __construct(TokensDocument $document, $nGramSize = NGramFactory::BIGRAM) 
    {
        $this->document = $document;
        $this->nGramSize = $nGramSize;
    }
    
    /**
     * 
     * @return array
     */
    public function getTokens()
    {
        if(empty($this->tokens)) {
            $this->tokens = array_values(array_filter($this->getTokensDocument()->getDocumentData()));
        }
        return $this->tokens;
    }
    
    /**
     * 
     * @return TokensDocument
     */
    public function getTokensDocument()
    {
        return $this->document;
    }
    
    /**
     * Get all the possible phrases
     * @return array
     */
    public function getPhrases()
    {   
        $phrases = [];

        for($index = $this->nGramSize; $index >= 2; $index--)
        {                     
            $phrases = array_merge($phrases, NGramFactory::create($this->getTokens(), $index));                        
        }
        
        // you cannot use a phrase if it is a substring of a longer phrase
        // we must exclude all of the substring phrases        
        $add = [];
        $remove = [];
        foreach($phrases as $phrase) 
        {   
            if(isset($remove[$phrase])) {
                continue;
            } elseif (!isset($add[$phrase])) {
                $add[$phrase] = true;
                // remove the suffix word
                $remove[substr($phrase, 0, strrpos($phrase," "))] = true; 
                //remove the prefix            
                $remove[substr($phrase, strpos($phrase," ")+1)] = true;                 
            }
        }        
        return array_keys($add);        
    }
    
    /**
     * @return array returns an array with the key as the phrase and the value is the score
     * it is already sorted
     */
    public function getKeywordScores()
    {                             
        $phrases = $this->getPhrases();
        // we must filter the null values before computing the frequencies        
        $freqDist = (new FreqDist($this->getTokens()))->getKeyValuesByFrequency();
                                        
        $keywords = array_keys($freqDist);
        // track the total degrees for a token
        $degrees = array_fill_keys($keywords, 0);

        // tally the results
        foreach($phrases as $phrase)
        {
            $tokens = explode(" ", $phrase);            
            foreach($tokens as $keyword)
            {                
                $degrees[$keyword] += count($tokens);                                    
            }  
        }      
        
        $phraseScores = array_fill_keys($phrases, 0);                    
        foreach($phrases as $phrase) 
        {
            $tokens = explode(" ", $phrase);
            foreach($tokens as $token) 
            {            
                $phraseScores[$phrase] += ($degrees[$token] / $freqDist[$token]);
            }
        }
        
        arsort($phraseScores);
        return $phraseScores;
    }    
    
    public function __destruct() 
    {
        unset($this->document);
        unset($this->nGramSize);
        unset($this->tokens);
    }
    
}
