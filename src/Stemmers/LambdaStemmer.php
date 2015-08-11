<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;
use TextAnalysis\Filters\LambdaFilter;

/**
 * Use a lamda function to stem words, just wraps the LambdaFilter class .
 *
 * @author yooper
 */
class LambdaStemmer extends LambdaFilter implements IStemmer
{
    /**
     * Returns the stemmed word based on the lambda function given
     * @param string $token
     * @return string
     */
    public function stem($token) 
    {
        return $this->transform($token);
    }

}
