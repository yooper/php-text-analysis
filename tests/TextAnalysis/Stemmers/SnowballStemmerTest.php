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
        $stemmer = new SnowballStemmer('English');
        $this->assertEquals("judg", $stemmer->stem("judges"));
        $this->assertEquals('ski', $stemmer->stem('skis'));
        $this->assertEquals('univers', $stemmer->stem('universities'));
        $this->assertEquals('news', $stemmer->stem('news'));                        
    }
    
    public function testSwedish()
    {       
        $stemmer = new SnowballStemmer('Swedish');
        $this->assertEquals("affärschef", $stemmer->stem("affärscheferna"));
    }    
    
    public function testException()
    {     
        $this->setExpectedException('Exception');
        $stemmer = new SnowballStemmer('Wookie');
    }
}