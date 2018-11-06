<?php

namespace TextAnalysis\Classifiers;

/**
 * Implementation of Naive Bayes algorithm, borrowed heavily from 
 * https://github.com/fieg/bayes
 * @author yooper
 */
class NaiveBayes implements \TextAnalysis\Interfaces\IClassifier
{        
    /**
     * Track token and counts for a given label
     * @var array
     */
    protected $labels = [];
                
    /**
     * Track the number of docs with the given label
     * @var array[int]
     */
    protected $labelCount = [];
    
    /**
     * Track the token counts
     * @var int[]
     */
    protected $tokenCount = [];
            
    public function train(string $label, array $tokens)
    {
        $freqDist = array_count_values($tokens);        
        if(!isset($this->labels[$label])) {
            $this->labels[$label] = [];
            $this->labelCount[$label] = 0;            
        }
        
        $this->labelCount[$label]++;          
        foreach($freqDist as $token => $count)
        {
            isset($this->tokenCount[$token]) ? $this->tokenCount[$token] += $count : $this->tokenCount[$token] = $count;            
            isset($this->labels[$label][$token]) ? $this->labels[$label][$token] += $count : $this->labels[$label][$token] = $count;
        }         
    }
    
    public function predict(array $tokens) 
    {
        $totalDocs = $this->getDocCount();
        $scores = [];
        
        foreach ($this->labelCount as $label => $docCount) 
        {
            $sum = 0;
            $inversedDocCount = $totalDocs - $docCount;
            $docCountReciprocal = 1 / $docCount;
            $inversedDocCountReciprocal = 1 / $inversedDocCount;
            
            foreach ($tokens as $token) 
            {
                $totalTokenCount = $this->tokenCount[$token] ?? 1; // prevent division by zero
                $tokenCount = $this->labels[$label][$token] ?? 0;
                $inversedTokenCount = $totalTokenCount - $tokenCount;
                $tokenProbabilityPositive = $tokenCount * $docCountReciprocal;
                $tokenProbabilityNegative = $inversedTokenCount * $inversedDocCountReciprocal;
                $probability = $tokenProbabilityPositive / ($tokenProbabilityPositive + $tokenProbabilityNegative);
                $probability = (0.5 + ($totalTokenCount * $probability)) / (1 + $totalTokenCount);
                $sum += log(1 - $probability) - log($probability);
            }
            $scores[$label] = 1 / (1 + exp($sum));
        }
        arsort($scores, SORT_NUMERIC);
        return $scores;                
    }
    
    public function getDocCount() : int
    {
        return array_sum( array_values( $this->labelCount)) ?? 0;
    }
    
    public function __destruct() 
    {
        unset($this->labelCount);
        unset($this->labels);
        unset($this->tokenCount);
    }
    
   
}
