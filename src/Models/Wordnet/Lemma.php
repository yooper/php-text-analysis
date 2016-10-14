<?php

namespace TextAnalysis\Models\Wordnet;

/**
 * Data model to capture wordnet's lemma ie data loads from the index.* files
 * @author dcardin
 */
class Lemma 
{
    use \TextAnalysis\Traits\WordnetPointerSymbolMap;
    
    /**
     * @string lower case ASCII text of word or collocation. Collocations are formed by joining individual words with an underscore (_ ) character.
    */
    protected $word;
    
    
    /**
     *
     * @var int Number of synsets that lemma is in. This is the number of senses 
     * of the word in WordNet. See Sense Numbers below for a discussion of how 
     * sense numbers are assigned and the order of synset_offset s in the index files.
     */
    protected $synsetCnt = 0;
    
    /**
     *
     * @var int Number of different pointers that lemma has in all synsets containing it.
     */
    protected $pCnt = 0;

    
    /**
     * @var int Byte offset in data.pos file of a synset containing lemma . Each 
     * synset_offset in the list corresponds to a different sense of lemma in 
     * WordNet. synset_offset is an 8 digit, zero-filled decimal integer t
     * hat can be used with fseek(3) to read a synset from the data file
     */
    protected $synsetOffsets = [];
    
    /**
     *
     * @var Synset[]
     */
    protected $synsets = [];
    
    /**
     * 
     * @param string $word
     * @param string $pos
     * @param int $synsetCnt
     * @param int $pCnt
     * @param array $ptrSymbols
     * @param array $synsetOffsets
     */
    public function __construct($word, $pos, $synsetCnt, $pCnt, array $ptrSymbols, array $synsetOffsets) 
    {
        $this->word = $word;
        $this->pos = $pos;
        $this->synsetCnt = $synsetCnt;
        $this->pCnt = $pCnt;
        $this->ptrSymbols = $ptrSymbols;        
        $this->synsetOffsets = $synsetOffsets;
    }
    
    /**
     * 
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }
    

    
    /**
     * 
     * @return int
     */
    public function getSynsetCnt()
    {
        return $this->synsetCnt;
    }
    
    /**
     * 
     * @return int
     */
    public function getPCnt()
    {
        return $this->pCnt;
    }
    
    /**
     * 
     * @return int[]
     */
    public function getSynsetOffsets()
    {
        return $this->synsetOffsets;
    }
    
    /**
     * 
     * @return string[]
     */
    public function getPtrSymbols()
    {
        return $this->ptrSymbols;
    }
    
    /**
     * Get the synsets for this lemma
     * @return Synset[]
     */
    public function getSynsets()
    {
        return $this->synsets;
    }
    
    /**
     * 
     * @param Synset[] $synsets
     * @return \TextAnalysis\Models\Wordnet\Lemma
     */
    public function setSynsets(array $synsets)
    {
        $this->synsets = $synsets;
        return $this;
    }
    
}
