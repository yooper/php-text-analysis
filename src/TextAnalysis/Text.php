<?php

namespace TextAnalysis;

use Tokenizers\TokenizerAbstract;
use Tokenizers\GeneralTokenizer;
use \ArrayIterator;
use TextAnalysis\Aggregates\TokenMetaAggregator;
use TextAnalysis\Aggregates\ConcordanceAggregate;


/**
 * A simple text class that expects text and a token class to use for
 * tokenizing the text
 * @package TextAnalysis\Text
 * @author yooper
 */
class Text 
{
    /**
     * The text provided
     * @var string
     */
    protected $text = null;
    
    /**
     * The tokenizer used
     * @var TokenizerAbstract 
     */
    protected $tokenizer = null;        
                   
    /**
     * The collection of tokens created by the text and tokenizer
     * @var ArrayIterator 
     */
    protected $tokenCollection = null;
   
    
    /**
     * Holds the meta data for doing quicker look ups
     * @var ArrayIterator
     */
    protected $tokenMetaAggregateCollection = null;
    
    
    /**
     * 
     * @param string $text
     * @param TokenizerAbstract $tokenizer 
     */
    public function __construct($text, TokenizerAbstract $tokenizer = null, $normalize = true)
    {
        if($normalize) { 
            $this->text = strtolower($text);
        } else {
            $this->text = $text;
        }
        
        if(!$tokenizer) { 
            $tokenizer = new \TextAnalysis\Tokenizers\GeneralTokenizer();
        }
        
        //initialize as empty
        $this->tokenCollection = new ArrayIterator(array());
        
        $this->tokenizer = $tokenizer;
    }
    
    /**
     * Process the text into tokens 
     */
    public function tokenize()
    {
        $this->tokenCollection = new ArrayIterator($this->tokenizer->tokenize($this->text));    
    }
    
    /**
     * Return the token collection
     * @return ArrayIterator
     */
    public function getTokenCollection()
    {
        return $this->tokenCollection;
    }
    
    /**
     * Return the token meta data
     * @return ArrayIterator 
     */
    public function getTokenMetaAggregateCollection()
    {
        if(!$this->tokenMetaAggregateCollection) { 
            $tokenAggregator = new TokenMetaAggregator($this->text, $this->tokenCollection);
            $this->tokenMetaAggregateCollection = $tokenAggregator->getAggregate();
        }
        return $this->tokenMetaAggregateCollection;
    }
    
    /**
     * Return an array containing context fragments from the text surrounding
     * the keyword
     * @param string $keyword 
     */
    public function getConcordance($keyword)
    { 
        $concordanceAggregate = new ConcordanceAggregate($this->text, $this->getTokenMetaAggregateCollection());
        return $concordanceAggregate->getAggregate($keyword);        
    }
        
}