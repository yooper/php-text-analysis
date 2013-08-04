<?php
namespace TextAnalysis;

/**
 * Token contains the word and the start position(s) in the original text
 * this class holds meta data about the token
 * @package TextAnalysis\Token
 * @author dcardin
 */
class Token 
{
    protected $word = null;
    
    /**
     * The positions in the original text the word
     * was found
     * @var array 
     */
    protected $positions = array();
    
    public function __construct($word)
    {
        $this->word = $word;
    }

    /** 
     */
    public function __destruct()
    {
        unset($this->positions);
    }
    
    public function addPosition($position)
    {
        $this->positions[] = $position;
    }
    
    /**
     * Return the positions in the raw text
     * @return array 
     */
    public function getPositions()
    {
        return $this->positions;
    }
    
    /**
     * Get the number of positions this token is mentions
     * @return int
     */
    public function getPositionCount()
    {
        return count($this->positions);
    }
    
    /**
     * Returns the length of the word token
     * @return int
     */
    public function length()
    {
        return strlen($this->word);
    }
    
    /**
     * Get the word token
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }
    
    /**
     * Return the word
     * @return string 
     */
    public function __toString()
    {
        return $this->getWord();
    }
}
