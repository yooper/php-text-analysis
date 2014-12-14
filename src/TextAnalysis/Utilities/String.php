<?php
namespace TextAnalysis\Utilities;

/**
 * Additional string functions
 * @author Dan Cardin (yooper)
 */
class String 
{
    protected function __construct(){}
    
    /**
     * http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
     * @param string $haystack
     * @param string $needle
     * @return boolean 
     */
    static public function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     * http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
     * @param string $haystack
     * @param string $needle
     * @return boolean 
     */
    static public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }
    
    /**
     * Takes a string and produces all possible substrings
     * @param string $text
     * @return array
     */
    static public function getAllSubStrings($text)
    {
        $splitText = str_split($text);
        $splitCount = count($splitText);
        $subStrings = [];        
        for ($i = 0; $i < $splitCount; $i++) 
        {
            for ($j = $i; $j < $splitCount; $j++) 
            {
                $subStrings[] = implode(array_slice($splitText, $i, $j - $i + 1));
            }       
        }
        return $subStrings;        
    }
    
    /**
     * Return a binary string from the passed in text
     * @param string $text Input text
     * @return string
     */
    static public function textToBin($text)
    {
        return base_convert(unpack('H*', $text)[1], 16, 2);
    }
       
}
