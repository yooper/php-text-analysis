<?php
namespace TextAnalysis\Documents;

use TextAnalysis\Interfaces\ITokenTransformation;
use TextAnalysis\Interfaces\IStemmer;
use TextAnalysis\Interfaces\IExtractStrategy;

/**
 * A document that contains an array of tokens
 *
 * @author yooper (yooper)
 */
class TokensDocument extends DocumentAbstract
{
    /**
     * Apply a stemmer
     * @param IStemmer $stemmer
     * @return \TextAnalysis\Documents\TokensDocument
     */
    public function applyStemmer(IStemmer $stemmer) 
    {        
        foreach($this->tokens as &$token) 
        { 
            $token = $stemmer->stem($token);
        }
        //filter null tokens and re-index
        $this->tokens = array_values(array_filter($this->tokens));
        return $this;
    }

    /**
     * Apply the transformation
     * @param ITokenTransformation $transformer
     * @return \TextAnalysis\Documents\TokensDocument
     */
    public function applyTransformation(ITokenTransformation $transformer) 
    {        
        foreach($this->tokens as &$token) 
        { 
            $token = $transformer->transform($token);
        }
        //filter null tokens and re-index
        $this->tokens = array_values(array_filter($this->tokens));
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

