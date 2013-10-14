<?php
namespace TextAnalysis\Filters;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * Remove quotes from tokenized documents
 * @author Dan Cardin
 */
class QuotesFilter implements ITokenTransformation
{
    
    /**
     * The set of chars or strings to search for
     * @var array 
     */
    protected $search = null;
    
    
    /**
     * Specify what chars or strings needs to be search for and replace with a empty space
     * @param array|null $search 
     */
    public function __construct(array $search = array("'",'"','`'))        
    {
        $this->search = $search;
    }
    
    /**
     * Filter the word
     * @param string $word
     * @return string 
     */
    public function transform($word)
    {
        return str_replace($this->search, "", $word);
    }
}

