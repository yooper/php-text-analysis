<?php
namespace TextAnalysis\Aggregates;

use TextAnalysis\Token;

/**
 * Compute the concordance
 * @author yooper
 */
class ConcordanceAggregate 
{    
    /**
     * The number of characters to each side of the 
     * of the keyword that are displayed
     * @var int 
     */
    static public $contextSize = 20;
    
    protected $tokenMetaCollection;
    
    protected $text;
    
    public function __construct($text, \ArrayIterator $tokenMetaCollection) 
    {
        $this->text = $text;
        $this->tokenMetaCollection = $tokenMetaCollection;
    }
  
    /**
     * Provide a keyword that you want the context to
     * 
     * @param string $keyword 
     * @return \ArrayIterator
     */
    public function getAggregate($keyword)
    { 
        /* @var $token Token */
         
        $token = $this->tokenMetaCollection->offsetGet($keyword);
        $positions = $token->getPositions();
        
        $sizeOf = count($positions);
        
        $sizeOfText = strlen($this->text);
        
        $aggregate = new \ArrayIterator();
        
        for($index = 0; $index < $sizeOf; $index++) {
            $leftContextStart = max($positions[$index] - self::$contextSize, 0);
            $rightContextStart = min($positions[$index] + $token->length() + self::$contextSize, $sizeOfText);                                     
            $aggregate->append(substr($this->text, $leftContextStart, $rightContextStart - $leftContextStart));
        }        
        return $aggregate;
    }
    
}

