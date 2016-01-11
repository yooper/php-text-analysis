<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Load the stop words
 * @author yooper (yooper)
 */
class StopWordsFilter implements ITokenTransformation
{    
    /**
     * An array of stop words
     * @var array 
     */
    protected $stopWords = null;
    

    /**
     * Make sure to normalize your stop words before using this filter
     * @param array $stopWords
     */
    public function __construct(array $stopWords)
    {
        $this->stopWords = array_fill_keys($stopWords, true);
    }
    
    /**
     * Check if the stop word is in the list
     * @param string $token 
     */
    public function transform($token)
    {
        if(isset($this->stopWords[$token])) {
            return null;
        }
        return $token;
    }
    
    /**
     * release the stop words
     */
    public function __destruct()
    {
        unset($this->stopWords);
    }
}