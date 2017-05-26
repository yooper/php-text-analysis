<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove quotes from tokenized documents
 * @author yooper
 */
class QuotesFilter implements ITokenTransformation
{
    
    /**
     * The set of chars or strings to search for
     * @var array 
     */
    protected $search = null;
    
    
    /**
     *
     * @var string
     */
    protected $regex = null;
    
    
    /**
     * Specify what chars or strings needs to be search for and replace with a empty space
     * @param array|null $search 
     */
    public function __construct(array $search = ["\'",'\"','`','“','”','’'])        
    {
        $this->search = $search;        
        $this->regex = "/([".implode("", $this->search)."])/u";
    }
    
    /**
     * 
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }
    
    /**
     * Filter the word
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        return preg_replace($this->getRegex(), '', $word);
    }
    
    public function __destruct() 
    {
        unset($this->regex);
        unset($this->search);
    }
}

