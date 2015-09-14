<?php

namespace TextAnalysis\Adapters;

use TextAnalysis\Interfaces\ISpelling;

/**
 * Use the enchant api to change words after they are stemmed
 * @author yooper
 */
class EnchantAdapter implements ISpelling
{
    protected $enchantBroker = null;
    
    public function __construct($language = 'en_US')
    {
        $r = enchant_broker_init();
        $this->enchantBroker = enchant_broker_request_dict($r, $language);        
    }
        
    /**
     * Use enchant to get word suggestions
     * @param string $word
     * @return array
     */
    public function suggest($word) 
    {
        if(!enchant_dict_check($this->enchantBroker, $word)) {
            return enchant_dict_suggest($this->enchantBroker, $word);
        } else {
            return [$word];
        }        
    }
    
    public function __destruct() 
    {
        unset($this->enchantBroker);
    }
}