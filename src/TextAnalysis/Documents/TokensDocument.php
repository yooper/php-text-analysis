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
    public function applyTransformation(ITokenTransformation $transformer) {
        
        foreach($this->tokens as &$token) { 
            $token = $transformer->transform($token);
        }
        //filter null tokens and re-index
        $this->tokens = array_values(array_filter($this->tokens));
        
    }
    
    /**
     * Return an array of tokens
     * @return array
     */
    public function getDocumentData() {
        return $this->tokens;
    }
}

