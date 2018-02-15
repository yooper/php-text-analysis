<?php

namespace TextAnalysis\Classifiers;

use Phpml\Classification\NaiveBayes as NB;

/**
 * Uses the PHP ml's library to provide an naive bayes classifier
 *
 * @author developer
 */
class NaiveBayes 
{
    /**
     *
     * @var NB
     */
    protected $classifier = null;
    
    /**
     * Map the tokens to ints to work with PHP ML
     * @var array
     */
    protected $tokenMap = [];
    
    /**
     * Stores the samples until its time to predict
     * @var array
     */
    protected $buffer = [];
    
    /**
     *
     * @var int
     */
    protected $maxTokens = 0;
        
    protected function mapToken(string $token)
    {
        if(!isset($this->tokenMap[$token])) {
            $this->tokenMap[$token] = random_int(PHP_INT_MIN , PHP_INT_MAX);
        }      
        return $this->tokenMap[$token];
    }
    
    protected function getMappedTokens(array $tokens)
    {
        return array_map([$this,'mapToken'], $tokens);
    }
    
    
    public function train(string $label, array $tokens)
    {                
        $this->maxTokens = max($this->maxTokens, count($tokens));
        $this->buffer[] = [$label, $tokens];              
    }
    
    
    protected function trainModel()
    {
        if(empty($this->buffer)) {
            return;
        }
       
        foreach($this->buffer as $row)
        {
            $tokens = $row[1];
            if(count($tokens) < $this->maxTokens) {
                $partial = array_fill($this->maxTokens-1, $this->maxTokens, function(){ return random_int(PHP_INT_MIN , PHP_INT_MAX); });
                
            }
            $this->getClassifier()->train([$this->getMappedTokens($row[1])], [$row[0]]);        
        }                    
    }
    
    
    public function predict(array $tokens)
    {
        if(!$this->getIsTrained())
        {

        }
        return $this->getClassifier()->predict($this->getMappedTokens($tokens));
    }
    
    /**
     * 
     * @return NB
     */
    public function getClassifier() : NB
    {
        if(!$this->classifier) {
            $this->classifier = new NB();
        }
        return $this->classifier;
    }
    
    public function __destruct() 
    {
        unset($this->buffer);
        unset($this->classifier);
        unset($this->tokenMap);
        unset($this->isTrained);
    }
}
