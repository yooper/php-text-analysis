<?php

namespace TextAnalysis\Comparisons;

use TextAnalysis\Interfaces\ISimilarity;
use TextAnalysis\Interfaces\IDistance;
use TextAnalysis\Utilities\Text;

/**
 * Find the longest common substring/subsequence (LCS) between two strings
 * @author yooper <yooper>
 */
class LongestCommonSubstringComparison implements ISimilarity, IDistance
{
    /**
     * Using caching to improve performance on text2 inputs 
     * @var boolean
     */
    protected $useCache = false;
    
    /**
     * Cache for holding substring arrays key/value array
     * @var array
     */
    protected $cache = [];
    
    /**
     * 
     * @param boolean $useCache
     */
    public function __construct($useCache = false) 
    {
        $this->useCache = $useCache;
    }
    
    /**
     * Returns the string length of the longest common substring (LCS)
     * @param string $text1
     * @param string $text2
     * @return int
     */
    public function distance($text1, $text2) 
    {        
        return max(mb_strlen($text1), mb_strlen($text2)) - mb_strlen($this->similarity($text1, $text2));
    }

    /**
     * Returns the Longest common substring
     * @param string $text1
     * @param string $text2
     * @return string
     */
    public function similarity($text1, $text2) 
    {
        if($this->useCache && !isset($this->cache[$text2])) {
            $this->cache[$text2] = Text::getAllSubStrings($text2);
        }
        
        $intersection = array_intersect( Text::getAllSubStrings($text1), ($this->useCache) ? $this->cache[$text2] : Text::getAllSubStrings($text2));
        $max = 0;
        $lcs = '';
        foreach($intersection as $substr)
        {
            $strlen = mb_strlen($substr);
            if( $strlen > $max) {
                $max = $strlen;
                $lcs = $substr;
            }
        }
        return $lcs;
    }
    
    /**
     * 
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }
    
    public function __destruct() 
    {
        unset($this->cache);
        unset($this->useCache);
    }
}
