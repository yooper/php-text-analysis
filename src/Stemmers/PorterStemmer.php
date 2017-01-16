<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;
use Porter;

/**
 * Wraps an existing library that provides the porter stemmer
 *
 * @author yooper
 */
class PorterStemmer implements IStemmer
{
    /**
     *
     * @var Porter
     */
    protected $porterStemmer = null;
    
    public function __construct() 
    {
        $this->porterStemmer = new Porter();
    }
    
    /**
     * 
     * @param string $token
     * @return string
     */
    public function stem($token) 
    {
        return $this->porterStemmer->Stem($token);
    }
}
