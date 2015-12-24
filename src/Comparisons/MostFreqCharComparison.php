<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;

/**
 * An implementation Most frequent k characters algorithm; see
 * http://en.wikipedia.org/wiki/Most_frequent_k_characters
 * @author yooper <yooper>
 */
class MostFreqCharComparison implements ISimilarity, IDistance
{
    /**
     * The minimum number of frequency per char to count
     * @var int
     */
    protected $limit;
    
    /**
     * Set the minimum limit
     * @param int $limit
     */
    public function __construct($limit = 2) 
    {
        $this->limit = $limit;
    }
    
    
    /**
     * Returns the most frequently used letter with the same
     * frequency 
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function similarity($text1, $text2)
    {
        $similarity = 0;
        $hash1 = $this->hashString($text1);
        $hash2 = $this->hashString($text2);        
        
        $keys = array_keys(array_intersect_key($hash1, $hash2));
        foreach($keys as $key)
        {
            if($hash1[$key] === $hash2[$key] && $hash1[$key] >= $this->limit)
            {
                $similarity += $hash1[$key];
            }
        }        
        return $similarity;
    }
    
    
    
    /**
     * Returns a sorted hashed array with the frequency counts per character
     * @param string $text
     */
    public function hashString($text)
    {
        $charList = str_split($text);
        $chars = array_fill_keys( $charList, 0);
        foreach($charList as $char) { 
            $chars[$char]++;
        }
        return $chars;
    }

    /**
     * Returns the distance max string length minus similarity
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function distance($text1, $text2)
    {
        return max(strlen($text1), strlen($text2)) - $this->similarity($text1, $text2);
    }

}
