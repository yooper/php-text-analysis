<?php
namespace TextAnalysis\Tokenizers\Regexp;
use TextAnalysis\Tokenizers\TokenizerAbstract;
/**
 * Regex GeneralTokenizer
 *
 * @author dcardin
 */
class GeneralTokenizer extends TokenizerAbstract 
{
    protected $pattern = null;
    protected $flags = 0;
    protected $offset = 0;
    
    public function __construct($pattern, $flags = 0, $offset = 0)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
        $this->offset = $offset;
    }
    
    
    public function tokenize($string)
    {
        
    }
}
