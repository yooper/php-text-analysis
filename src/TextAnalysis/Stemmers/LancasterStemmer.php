<?php
namespace TextAnalysis\Stemmers;
use TextAnalysis\Interfaces\IStemmer;
use TextAnalysis\Interfaces\IDataReader;
use TextAnalysis\Adapters\JsonDataAdapter;
use TextAnalysis\Utilities\Vowel;
/**
 * A word stemmer based on the Lancaster stemming algorithm.
 * Paice, Chris D. "Another Stemmer." ACM SIGIR Forum 24.3 (1990): 56-61.
 *
 * @author Dan Cardin
 */
class LancasterStemmer implements IStemmer
{
    /**
     * Constants used to make accessing the indexed array easier 
     */
    const ENDING_STRING = 'ending_string';
    const LOOKUP_CHAR = 'lookup_char';
    const INTACT_FLAG = 'intact_flag';
    const REMOVE_TOTAL = 'remove_total';
    const APPEND_STRING = 'append_string';
    const CONTINUE_FLAG = 'continue_flag';
    
    /**
    * Keep a copy of the original token
    * @var string 
    */
    protected $originalToken = null;
        
    /**
     * The indexed rule set provided
     * @var array
     */
    protected $indexedRules = array();
    
    /**
     * if reader is null it loads the default lancaster json rule set  
     */
    public function __construct(IDataReader $reader = null)
    {
        if(!$reader) { 
            $reader = new JsonDataAdapter(file_get_contents(dirname(__DIR__).'/Files/Stemmers/lancaster.json'));
        }

        $this->indexRules($reader->read());        
    }
    
    /**
     * Creates an chained hashtable using the lookup char as the key
     * @param array $rules 
     */
    protected function indexRules($rules)
    {
        $this->indexedRules = array();
        
        foreach($rules as $rule){
            if(isset($this->indexedRules[$rule[self::LOOKUP_CHAR]])){
                $this->indexedRules[$rule[self::LOOKUP_CHAR]][] = $rule;
            } else {
                $this->indexedRules[$rule[self::LOOKUP_CHAR]] = array($rule);
            }
        }       
    }
    
    public function stem($token)
    {
        $this->originalToken = $token;
        
        //only iterate out loop if a rule is applied        
        do {
            $ruleApplied = false;
            $lookupChar = $token[strlen($token)-1];

            //check that the last character is in the index, if not return the origin token
            if(!array_key_exists($lookupChar, $this->indexedRules)){
                return $token;
            }
            foreach($this->indexedRules[$lookupChar] as $rule)
            {
                if(strrpos($token, substr($rule[self::ENDING_STRING],-1)) === 
                        (strlen($token)-strlen($rule[self::ENDING_STRING]))){

                    
                    if(!empty($rule[self::INTACT_FLAG])){ 
                        
                        if($this->originalToken == $token && 
                            $this->isAcceptable($token, (int)$rule[self::REMOVE_TOTAL])){

                            $token = $this->applyRule($token, $rule);
                            $ruleApplied = true;
                            if($rule[self::CONTINUE_FLAG] === '.'){
                                return $token;
                            } 
                            break;
                        }
                    } elseif($this->isAcceptable($token, (int)$rule[self::REMOVE_TOTAL])){
                        $token = $this->applyRule($token, $rule);
                        $ruleApplied = true;
                        if($rule[self::CONTINUE_FLAG] === '.'){
                            return $token;
                        }
                        break;
                    }
                } else {
                    $ruleApplied = false;
                }
            }
        } while($ruleApplied);
        
        return $token;
                        
    }
    
    /**
     * Apply the lancaster rule and return the altered string. 
     * @param string $token
     * @param array $rule 
     */
    protected function applyRule($token, $rule)
    {
        return substr_replace($token, $rule[self::APPEND_STRING], strlen($token) - $rule[self::REMOVE_TOTAL]);        
    }
    
    /**
     * Check if a word is acceptable
     * @param string $token
     * @param int $removeTotal
     * @return boolean 
     */
    protected function isAcceptable($token, $removeTotal)
    {
        $length =  strlen($token) - $removeTotal;
        if(Vowel::isVowel($token, 0)&& $length >= 2){
            return true;
        } elseif($length >= 3 && 
                (Vowel::isVowel($token, 1) || Vowel::isVowel($token, 2))) {
            return true;
        }
        return false;
    }
        
}

