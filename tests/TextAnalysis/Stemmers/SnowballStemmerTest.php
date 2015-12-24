<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\SnowballStemmer;

/**
 *
 * @author yooper
 */
class SnowballStemmerTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultEnglish()
    {       
        if( getenv('SKIP_TEST')) {
            return;
        }
        $stemmer = new SnowballStemmer();
        $this->assertEquals("judg", $stemmer->stem("judges"));
    }
    
    public function testSwedish()
    {       
        if( getenv('SKIP_TEST')) {
            return;
        }
        $stemmer = new SnowballStemmer('swedish');
        $this->assertEquals("affärschef", $stemmer->stem("affärscheferna"));
    }    
    
    public function testException()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }      
        $this->setExpectedException('Exception');
        $stemmer = new SnowballStemmer('ewok');
    }
}
