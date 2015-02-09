<?php
namespace TextAnalysis\Documents;

use TextAnalysis\Interfaces\ITokenTransformation;

/**
 * A document that contains an array of tokens
 *
 * @author Dan Cardin (yooper)
 */
class TokensDocument extends DocumentAbstract
{
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

