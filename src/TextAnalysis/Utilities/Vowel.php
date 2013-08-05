<?php
namespace TextAnalysis\Utilities;


/**
 * Helper Vowel class
 * @author Dan Cardin
 */
class Vowel 
{
    protected function __construct(){}
    
    /**
     * 
     * @param string $word
     * @param int $index
     * @return boolean 
     */
    static public function isVowel($word, $index)
    {
        if(strpbrk($word[$index], 'aeiou') !== false) {
            return true;
        } elseif($word[$index] === 'y' && strpbrk($word[--$index], 'aeiou') === false) {
            return true;
        } 
        return false;
    }
}

