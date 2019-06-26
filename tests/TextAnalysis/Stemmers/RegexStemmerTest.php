<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\RegexStemmer;

/**
 * RegexStemmerTest
 * @author yooper
 */
class RegexStemmerTest extends \PHPUnit\Framework\TestCase
{
    
    public function testRegexStemmer()
    {
        $stemmer = new RegexStemmer('ing$|s$|e$', 4);
        $this->assertEquals("car", $stemmer->stem("car"));
        $this->assertEquals("mas", $stemmer->stem("mass"));
        $this->assertEquals("was", $stemmer->stem("was"));
        $this->assertEquals("bee", $stemmer->stem("bee"));
        $this->assertEquals("comput", $stemmer->stem("compute"));
                
    }
    
    
}
