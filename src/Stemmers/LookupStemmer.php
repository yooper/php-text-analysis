<?php
namespace TextAnalysis\Stemmers;
use TextAnalysis\Interfaces\IStemmer;
use TextAnalysis\Interfaces\IDataReader;
/**
 * A dictionary based lookup stemmer. Depends upon input file
 * @author yooper
 */
class LookupStemmer implements IStemmer
{
    /**
     * A dictionary for looking up stemmed words
     * @var array 
     */
    protected $dictionary = array();
    
    public function __construct(IDataReader $reader)
    {
        $this->dictionary = $reader->read();
    }
    
    /**
     * Returns a token's stemmed root
     * @param string $token
     * @return string 
     */
    public function stem($token) 
    {
        if(array_key_exists($token, $this->dictionary)){
            return $this->dictionary[$token];
        }
        return $token;
    }
}
