<?php

namespace Tests\TextAnalysis\Stemmers;
use TextAnalysis\Stemmers\LookupStemmer;
use TextAnalysis\Adapters\JsonDataAdapter;
/**
 * Lookup Stemmer Test
 * @author yooper
 */
class LookupStemmerTest extends \PHPUnit\Framework\TestCase
{
    
    public function testLookupStemmer()
    {
        $jsonStr = '{ "ended":"end", "ending": "end"}';

        $jsonReader = new JsonDataAdapter($jsonStr);        
        $stemmer = new LookupStemmer($jsonReader);
        $this->assertEquals("end", $stemmer->stem("ending"));
        $this->assertEquals("end", $stemmer->stem("ended"));
               
    }
    
    
}
