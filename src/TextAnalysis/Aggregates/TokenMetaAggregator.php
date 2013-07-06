<?php

namespace TextAnalysis\Aggregates;
use \ArrayIterator;
use TextAnalysis\Token;

/**
 * Using the text and the tokens this class generates the meta data on the tokens
 * @author dcardin
 */
class TokenMetaAggregator 
{
    /**
     * Iterator over the collection more easily
     * @var ArrayIterator
     */
    protected $collection;
    
    /**
     * Raw text
     * @var string 
     */
    protected $text = null;
    
    /**
     *
     * @param string $text
     * @param array $tokens 
     */
    public function __construct($text, array $tokens = array())
    {
        $this->text = $text;
        $this->collection = new ArrayIterator($tokens);
    }
    
    /**
     * 
     * @return ArrayIterator 
     */
    public function getAggregate()
    {
        /* @var ArrayIterator $aggregateTokens */
        $aggregateTokens = new ArrayIterator(array());
        
        foreach($this->collection as $word) { 
            
            /* @var Token $token */
            $token = null;
            if(!$aggregateTokens->offsetExists($word))
            {
                $token = new Token($word);
                $aggregateTokens->offsetSet($word, $token);
            } else { 
                $token = $aggregateTokens->offsetGet($word);
            } 
            $token->addPosition($this->getNextTokenPosition($token));
        }
        
        return $aggregateTokens;
    }
    
    /**
     * Returns the next position/offset value
     * @param Token $token 
     * @return int
     */
    protected function getNextTokenPosition(Token $token) { 
        
        $offset = $this->getLastTokenPosition($token);
        return strpos($this->text, $token->getWord(), $offset);
    }
    
    
    /**
     * Return the last position the token was used
     * @param Token $token
     * @return int 
     */
    protected function getLastTokenPosition(Token $token) { 
        if(count($token->getPositions()) === 0) { 
            return 0;
        } else { 
            $positions = $token->getPositions();
            return end($positions)+1;
        }
    }
    
}


