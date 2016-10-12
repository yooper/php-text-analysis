<?php

namespace TextAnalysis\Indexes;

use TextAnalysis\Corpus\WordnetCorpus;
use TextAnalysis\Models\Wordnet\Synset;
use TextAnalysis\Models\Wordnet\Lemma;

/*
 * WordnetIndex is a facade for accessing the wordnet data
 * @author yooper
 */
class WordnetIndex 
{
    /**
     *
     * @var WordnetCorpus
     */
    protected $wordnetCorpus = null;
    
    /**
     *
     * @var array
     */
    protected $lemmasIdx = [];
    
    
    /**
     * 
     * @param WordnetCorpus $wordnetCorpus
     */
    public function __construct(WordnetCorpus $wordnetCorpus) 
    {
        $this->wordnetCorpus = $wordnetCorpus;
    }
    
    /**
     * @return WordnetCorpus
     */
    public function getWordnetCorpus()
    {
        return $this->wordnetCorpus;
    }
    
    /**
     * Return the lemmas that are linked to the given word, provide a pos to
     * filter down the results
     * @param string $word
     * @param string $pos
     * @return \TextAnalysis\Models\Wordnet\Lemma[]
     */
    public function getLemma($word, $pos = '')
    {
        if(empty($this->lemmasIdx)) {
            foreach($this->getWordnetCorpus()->getLemmas() as &$lemma) {
                $this->lemmasIdx["{$lemma->getWord()}.{$lemma->getPos()}"] = $lemma;
            }            
            // sort the keys for faster lookup
            ksort($this->lemmasIdx);
        }
        
        $found = [];
        
        // found 1
        if(isset($this->lemmasIdx["{$word}.{$pos}"])) {
            $found[] = $this->lemmasIdx["{$word}.{$pos}"];
        } else {
            foreach($this->getWordnetCorpus()->getPosFileMaps() as $key => $value)
            {
                if(isset($this->lemmasIdx["{$word}.{$key}"])) {
                    $found[] = $this->lemmasIdx["{$word}.{$key}"];
                }
            }
        }

        //attach the synsets for the lemmas
        foreach($found as $lemma)
        {
            if(empty($lemma->getSynsets())) {
                $synsets = [];
                foreach($lemma->getSynsetOffsets() as $fileOffset)
                {
                    $synsets[] = $this->getWordnetCorpus()->getSynsetByOffsetAndPos((int)$fileOffset, $lemma->getPos());
                }
                $lemma->setSynsets($synsets);
            }
        }       
        return $found;
    }
    
    public function __destruct() 
    {
        unset($this->wordnetCorpus);
        unset($this->lemmasIdx);
    }
}
