<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\SnowballStemmer;
use TextAnalysis\Adapters\PspellAdapter;
use TextAnalysis\Stemmers\DictionaryStemmer;

/**
 *
 * @author yooper
 */
class DictionaryStemmerTest extends \PHPUnit_Framework_TestCase
{
    public function testPspell()
    {       
        if( getenv('SKIP_TEST')) {
            return;
        }
        
        $stemmer = new DictionaryStemmer(new PspellAdapter(), new SnowballStemmer());           
        $this->assertEquals("judge", $stemmer->stem("judges"));
        // some times approach does not work
        $this->assertNotEquals('university', $stemmer->stem("university")); 
        
        $this->assertEquals('hammock', $stemmer->stem("hammok")); 
    }
    
}
