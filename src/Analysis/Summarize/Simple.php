<?php

namespace TextAnalysis\Analysis\Summarize;

use TextAnalysis\Models\ScoreKeeper;

/**
 * A simple algorithm based off of frequency counts for finding the best
 * sentence to summarize the text
 * @author yooper
 */
class Simple 
{    
    /**
     * Returns each sentenced scored. 
     * @param array $wordTokens
     * @param array $sentenceTokens
     * @return array
     */
    public function summarize(array $wordTokens, array $sentenceTokens) : array
    {
        $tokenCounts = array_count_values($wordTokens);
        $scoreKeepers = [];
        for($index = 0; $index < count($sentenceTokens); $index++)
        {
            $scoreKeepers[] = new ScoreKeeper($sentenceTokens[$index], $index);
        }
                       
        foreach($tokenCounts as $token => $freq)
        {
            foreach($scoreKeepers as $sentenceKeeper)
            {
                if(strpos($sentenceKeeper->getToken(), (string)$token) !== false) {
                    
                    $sentenceKeeper->addToScore($freq);
                }
            }
        } 
        
        usort($scoreKeepers, 'score_keeper_sort');
        return $scoreKeepers;
    }
        
}
