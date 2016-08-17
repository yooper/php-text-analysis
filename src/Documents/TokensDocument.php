<?php
namespace TextAnalysis\Documents;

use TextAnalysis\Interfaces\ITokenTransformation;
use TextAnalysis\Interfaces\IStemmer;
use TextAnalysis\Interfaces\IExtractStrategy;
use DateTime;

/**
 * A document that contains an array of tokens with a doc id
 *
 * @author yooper (yooper)
 */
class TokensDocument extends DocumentAbstract
{
    
    /**
     * An array of tokens that all Documents have
     * @var type 
     */
    protected $tokens = array();

    /**
     *
     * @var DateTime time doc was created defaults to now
     */
    protected $createdOn = null;
    
    static protected $counter = 0;
    
    /**
     *
     * @var mixed
     */
    protected $id = null;
    
    /**
     * Stores an array of metadata about the document
     * @var array
     */
    protected $metadata = [];
    
    
    /**
     * 
     * @param array $tokens
     * @param mixed $id
     * @param DateTime $createdOn
     * @param array $metadata
     */
    public function __construct(array $tokens, $id = null, DateTime $createdOn = null, $metadata = []) 
    {
        parent::__construct($tokens, null);
        $this->id = $id ?: ++self::$counter;
        $this->createdOn = $createdOn ?: new DateTime();
        $this->metadata = $metadata;
    }

    /**
     * 
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Apply a stemmer
     * @param IStemmer $stemmer
     * @param boolean $removeNulls
     * @return \TextAnalysis\Documents\TokensDocument
     */
    
    public function applyStemmer(IStemmer $stemmer, $removeNulls = true) 
    {        
        foreach($this->tokens as &$token) 
        { 
            $token = $stemmer->stem($token);
        }
        
        if($removeNulls) {
            //filter null tokens and re-index
            $this->tokens = array_values(array_filter($this->tokens));            
        }
        return $this;
    }

    /**
     * Apply the transformation
     * @param ITokenTransformation $transformer
     * @param boolean Remove nulls, we need nulls to indictate where stop words are
     * @return \TextAnalysis\Documents\TokensDocument
     */
    public function applyTransformation(ITokenTransformation $transformer, $removeNulls = true) 
    {        
        foreach($this->tokens as &$token) 
        { 
            $token = $transformer->transform($token);
        }
        //filter null tokens and re-index
        if($removeNulls) {
            $this->tokens = array_values(array_filter($this->tokens));
        }
        return $this;
    } 
    
    /**
     * Apply an extract filter and return the results after filter
     * all the documents in the collection
     * @param IExtractStrategy $extract
     * @return array
     */
    public function applyExtract(IExtractStrategy $extract)
    {
        $found = [];
        foreach($this->tokens as $token) 
        { 
            if($extract->filter($token)) {
                $found[] = $token;
            }
        }
        return $found;
    }

    /**
     * 
     * @param array $metadata
     * @return \TextAnalysis\Documents\TokensDocument
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    
    /**
     * 
     * @param \TextAnalysis\Documents\DateTime $createdOn
     * @return \TextAnalysis\Documents\TokensDocument
     */
    public function setCreatedOn(DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Return the tokens
     * @return array
     */
    public function toArray()
    {
        return $this->tokens;
    }
    
    /**
     * Return an array of tokens
     * @return array
     */
    public function getDocumentData() 
    {
        return $this->tokens;
    }
    
}

