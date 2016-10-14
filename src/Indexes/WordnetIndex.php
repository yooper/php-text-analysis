<?php

namespace TextAnalysis\Indexes;

use TextAnalysis\Corpus\WordnetCorpus;
use TextAnalysis\Models\Wordnet\Synset;
use TextAnalysis\Models\Wordnet\Lemma;
use TextAnalysis\Models\Wordnet\ExceptionMap;
use TextAnalysis\Utilities\Text;

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
    
    /**
    * Concept taken from nltk 
    * Find a possible base form for the given form, with the given
    * part of speech, by checking WordNet's list of exceptional
    * forms, and by recursively stripping affixes for this part of
    * speech until a form in WordNet is found.
    * @todo improve the algorithm, it is really slow
    * @param string $word
    * @param string|null $pos
    * @return string return the base word 
    */
    public function getMorph($word, $pos = '')
    {
        if(mb_strlen($word) < 3) {
            return "";
        }
        
        $searchForFuncWithPos = function(ExceptionMap $exceptionMap) use($word, $pos)
        {
            return $exceptionMap->getPos() === $pos && in_array($word, $exceptionMap->getExceptionList());
        };
        
        $searchForFuncWithoutPos = function(ExceptionMap $exceptionMap) use($word)
        {
            return in_array($word, $exceptionMap->getExceptionList());
        };        

        $found = [];
        
        
        if(!empty($pos)) {
            $found = array_filter($this->getWordnetCorpus()->getExceptionsMap(), $searchForFuncWithPos);
        } else {
            $found = array_filter($this->getWordnetCorpus()->getExceptionsMap(), $searchForFuncWithoutPos);
        }
        
        // found a match in the exceptions data
        if(!empty($found)) { 
            return array_values($found)[0]->getTarget();
        }
        
        foreach($this->getMorphilogicalSubstitutions() as $keyPos => $keyValues)
        {            
            foreach($keyValues as $key => $value) 
            {
                if(Text::endsWith($word, $key)) {   
                    $morphedWord = substr($word, 0, -strlen($key)).$value;
                    $r = $this->getLemma($morphedWord, $keyPos);
                    if(!empty($r)) { 
                        $found += array_map(function($lemma){ return $lemma->getWord();}, $r);
                        return $found[0];
                    }
                }
            }
        }
        if(empty($found)) {
            return "";
        }
        
        return $found[0];
    }
    
    /**
     * 
     * @return array
     */
    public function getMorphilogicalSubstitutions()
    {
        return [
            WordnetCorpus::NOUN => [
                's' => '',
                'ses' => 's',
                'ves' => 'f',
                'xes' => 'x',
                'zes' => 'z',
                'ches' => 'ch',
                'shes' => 'sh',
                'men' => 'man',
                'ies' => 'y',
                
            ],
            WordnetCorpus::VERB => [
                's'=> '', 
                'ies'=> 'y', 
                'es'=> 'e', 
                'es'=> '',
                'ed'=> 'e', 
                'ed'=> '', 
                'ing'=> 'e', 
                'ing'=> '' 
            ],
            WordnetCorpus::ADJECTIVE => [
                'er' => '',
                'est' => '',
                'er' => 'e',
                'est' => 'e'   
            ]
        ];
    }
    
    
    public function __destruct() 
    {
        unset($this->wordnetCorpus);
        unset($this->lemmasIdx);
    }
}
