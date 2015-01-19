<?php

namespace Tests;

/**
 * 
 * @author yooper
 */
class Base extends \PHPUnit_Framework_TestCase
{
    /**
     * The default text that is used with a lot of test cases
     * @var string 
     */
    static protected $text = null;
    
    /**
     * Preload and cache a corpus for testing purposes 
     */
    public function setUp()
    {
        parent::setUp();
        //load the text file
        if(is_null(self::$text)) { 
            self::$text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');
        }        
    }
    
    /**
     * Eliminates annoying error message about base not having any tests. 
     */
    public function testIsEmpty()
    {}
}


