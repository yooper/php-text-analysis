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
        $stemmer = new SnowballStemmer();
        $this->assertEquals("judg", $stemmer->stem("judges"));
    }
    
    public function testSwedish()
    {       
        $stemmer = new SnowballStemmer('swedish');
        $this->assertEquals("affärschef", $stemmer->stem("affärscheferna"));
    }    
    
    /**
     * @expectedException   Exception
     */
    public function testException()
    {
        $stemmer = new SnowballStemmer('ewok');
    }
}
