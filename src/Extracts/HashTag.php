<?php

namespace TextAnalysis\Extracts;
use TextAnalysis\Interfaces\IExtractStrategy;

/**
 * check if the given token is a hash tag
 * @author yooper
 */
class HashTag implements IExtractStrategy
{
    /**
     *
     * @var int
     */
    protected $minLength = 3;
        
    public function __construct(int $minLength = 3) 
    {
        $this->minLength = $minLength;
    }
    
    /**
     * 
     * @param string $token
     * @return false|string
     */
    public function filter($token) 
    {
        // don't count the hash tag sign -1
        if($token[0] === '#' && strlen($token)-1 >= $this->getMinLength()) {
            return $token;
        }
        return false;
    }
    
    public function getMinLength() : int
    {
        return $this->minLength;
    }

}