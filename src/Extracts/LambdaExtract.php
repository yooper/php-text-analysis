<?php

namespace TextAnalysis\Extracts;
use TextAnalysis\Interfaces\IExtractStrategy;
use TextAnalysis\Filters\LambdaFilter;

/**
 * use a lambda function for filtering, lambda MUST return false or a string
 * @author yooper
 */
class LambdaExtract extends LambdaFilter implements IExtractStrategy
{
        
    /**
     * 
     * @param string $token
     * @return false|string
     */
    public function filter($token) 
    {
        return $this->transform($token);
    }

}
