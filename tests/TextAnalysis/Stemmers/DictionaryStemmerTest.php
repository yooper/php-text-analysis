<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Adapters\PspellAdapter;
use TextAnalysis\Stemmers\DictionaryStemmer;
use TextAnalysis\Stemmers\RegexStemmer;

/**
 *
 * @author yooper
 */
class DictionaryStemmerTest extends \PHPUnit\Framework\TestCase
{
    public function testPspell()
    {       
        if( getenv('SKIP_TEST') || !extension_loaded('stem')) {
            return;
        }
        
        $stemmer = new DictionaryStemmer(new PspellAdapter(), new RegexStemmer('ing$|s$|e$', 4));           
        $this->assertEquals("judge", $stemmer->stem("judges"));
        // some times approach does not work
        $this->assertEquals('university', $stemmer->stem("universities"));         
        $this->assertEquals('hammock', $stemmer->stem("hammok")); 
    }
    
}
