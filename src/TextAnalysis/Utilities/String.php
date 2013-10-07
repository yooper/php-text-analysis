<?php
namespace TextAnalysis\Utilities;

/**
 * Additional string functions
 * @author Dan Cardin
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
}
