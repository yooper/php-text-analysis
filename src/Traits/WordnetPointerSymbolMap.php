<?php

namespace TextAnalysis\Traits;

trait WordnetPointerSymbolMap
{
    /**
     *
     * @var string[]
     */
    protected $ptrSymbols = [];

    /**
     * Part of speech, should be a single character, Syntactic category: n for 
     * noun files, v for verb files, a for adjective files, r for adverb files.
     * @var string
     */
    protected $pos;
    
    public function setPos($pos)
    {
        $this->pos = $pos;
    }
    
    /**
     * Returns single character
     * @return string
     */
    public function getPos()
    {
        return $this->pos;
    }
    
    /**
     * 
     * @param string[] $ptrSymbols
     */
    public function setPtrSymbols(array $ptrSymbols)       
    {
        $this->ptrSymbols = $ptrSymbols;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getPtrSymbols()
    {
        return $this->ptrSymbols;
    }
    
    public function isAntonym()
    {
        return $this->isA('!');
    }
    
    public function isHypernym()
    {
        return $this->isA('@');
    }
    
    public function isInstanceHypernym()
    {
        return $this->isA('@!');
    }
    
    public function isHyponym()
    {
        return $this->isA('~');
    }
    
    public function isInstanceHyponym()
    {
        return $this->isA('~i');
    }
    
    public function isMemberHolonym()
    {
        return $this->isA('#m');
    }

    public function isSubstanceHolonym()
    {
        return $this->isA('#s');
    }

    public function isPartHolonym()
    {
        return $this->isA('#p');
    }

    public function isMemberMeronym()
    {
        return $this->isA('%m');
    }

    public function isSubstanceMeronym()
    {
        return $this->isA('%s');
    }

    public function isPartMeronym()
    {
        return $this->isA('%p');
    }
    
    public function isAttribute()
    {
        return $this->isA('=');
    }    
    
    public function isDerivation()
    {
        return $this->isA('+');
    }
    
    public function isEntailment()
    {
        return $this->isA('*');
    }

    public function isCause()
    {
        return $this->isA('>');
    }

    public function isSeeAlso()
    {
        return $this->isA('>');
    }

    public function isVerbGroup()
    {
        return $this->isA('$');
    }    
    
    public function isSimilarTo()
    {
        return $this->isA('$');
    }  
    
    public function isParticipleOfVerb()
    {
        return $this->isA('<');
    }  

    public function isPertainym()
    {
        return $this->isA('\\');
    }   
    
    public function isDerivedFromAdjective()
    {
        return $this->isA('\\');
    }
    
    /**
     * 
     * @param string $symbol
     * @return boolean
     */
    protected function isA($symbol)
    {
        return in_array($symbol, $this->getPtrSymbols());
    }  
    
}

