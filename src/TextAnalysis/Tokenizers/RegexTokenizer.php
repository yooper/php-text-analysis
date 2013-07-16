<?php
namespace TextAnalysis\Tokenizers;
/**
 * Regex GeneralTokenizer
 *
 * @author yooper
 */
class RegexTokenizer extends TokenizerAbstract 
{
    const DEFAULT_REGEX = '/\w+|\$[\d\.]+|\S+/';
    
    protected $pattern = null;
    protected $flags = 0;
    protected $offset = 0;
    
    public function __construct($pattern = self::DEFAULT_REGEX, $flags = 0, $offset = 0)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
        $this->offset = $offset;
    }
    
    /**
     * Wraps preg_match_all
     * @param string $string
     * @return array 
     */
    public function tokenize($string)
    {
        $matches = array();
        $count = preg_match_all($this->pattern, $string, $matches, $this->flags, $this->offset);
        if($count === false) { 
            return array();
        }
        return $matches[0];
        
    }
}
