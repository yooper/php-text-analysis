<?php

namespace TextAnalysis\Stemmers;

use TextAnalysis\Interfaces\IStemmer;
use Exception;

/**
 * Wrapper around Pecl's snowball stemmer SnowballStemmer
 *
 * @author yooper
 */
class SnowballStemmer implements IStemmer
{
    /**
     *
     * @var string the language specific stemmer to use
     */
    protected $lang = null;
    
    /**
     * Initialize the snowball stemmer
     * @param string $lang
     * @throws Exception
     */
    public function __construct($lang = 'english')
    {
        $this->lang = $lang;
        if(!extension_loaded ('stem') ) {
            throw new Exception("pecl stem module is not loaded");
        }
        
        if(!function_exists("stem_{$this->lang}")) {
            throw new Exception("Language stemmer function stem_{$this->lang} does not exist");
        }
    }
    
    /**
     * 
     * @param string $token
     * @return string
     */
    public function stem($token)
    {
        return call_user_func("stem_{$this->lang}", $token);    
    }

}
