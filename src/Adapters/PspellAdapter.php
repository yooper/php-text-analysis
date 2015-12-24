<?php

namespace TextAnalysis\Adapters;

use TextAnalysis\Interfaces\ISpelling;

/**
 * Use the pspell dictionary to change words after they are stemmed
 * @author yooper
 */
class PspellAdapter implements ISpelling
{
    protected $pSpell = null;
    
    public function __construct($language = 'en', $spelling = "", $jargon = "", $encoding = "", $mode = PSPELL_BAD_SPELLERS )
    {
        $this->pSpell = pspell_new($language, $spelling, $jargon, $encoding, $mode);        
    }
        
    /**
     * Use pspell to get word suggestions
     * @param string $word
     * @return array
     */
    public function suggest($word) 
    {
        if (!pspell_check($this->pSpell, $word)) {
            return pspell_suggest($this->pSpell, $word);
        }
        else {
            return [$word];
        }
    }
    
    public function __destruct() 
    {
        unset($this->pSpell);
    }
}
