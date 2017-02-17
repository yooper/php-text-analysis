<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;

/**
 * A wrapper around PHP native snowball implementation
 * @author yooper
 */
class SnowballStemmer implements IStemmer
{
    const BASE_NAMESPACE = '\\Wamania\\Snowball\\';
    
    /**
     *
     * @var \Wamania\Snowball\Stem
     */
    protected $stemmer;
    
    public function __construct($stemmerType = 'English') 
    {
        $className = self::BASE_NAMESPACE.$stemmerType;
        if(!class_exists($className)) { 
            throw new \RuntimeException("Class {$stemmerType} does not exist");
        }
        $this->stemmer = new $className();
    }
    
    public function stem($token) 
    {
        return $this->stemmer->stem($token);
    }

}