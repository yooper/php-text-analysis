<?php

namespace TextAnalysis\Models;

/**
 * Track metrics of tokenization
 * @author yooper
 */
class ScoreKeeper 
{
    /**
     *
     * @var string
     */
    protected $token;
    
    /**
     *
     * @var mixed
     */
    protected $score;
    
    /**
     *
     * @var mixed
     */
    protected $index;
    
    public function __construct(string $token, $index, $score = 0) 
    {
        $this->token = $token;
        $this->index = $index;
        $this->score = $score;
    }
    
    public function getToken() : string
    {
        return $this->token;
    }
    
    public function getIndex()
    {
        return $this->index;
    }
    
    public function getScore()
    {
        return $this->score;
    }
    
    public function addToScore($score)
    {
        $this->score += $score;
    }
}
