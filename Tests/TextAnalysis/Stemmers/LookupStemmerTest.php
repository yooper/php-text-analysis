<?php

namespace Tests\TextAnalysis\Stemmers;
use TextAnalysis\Stemmers\LookupStemmer;
use TextAnalysis\Adapters\JsonLookupDataAdapter;
/**
 * Lookup Stemmer Test
 * @author yooper
 */
class LookupStemmerTest extends \PHPUnit_Framework_TestCase
{
    
    public function testLookupStemmer()
    {
        $jsonStr = '{ "ended":"end", "ending": "end"}';

        $jsonReader = new JsonLookupDataAdapter($jsonStr);        
        $stemmer = new LookupStemmer($jsonReader);
        $this->assertEquals("end", $stemmer->stem("ending"));
        $this->assertEquals("end", $stemmer->stem("ended"));
               
    }
    
    
}
