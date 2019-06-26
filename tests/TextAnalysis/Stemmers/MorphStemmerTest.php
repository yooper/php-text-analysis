<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\MorphStemmer;

/**
 * Description of MorphStemmerTest
 *
 * @author yooper
 */
class MorphStemmerTest extends \PHPUnit\Framework\TestCase
{
    public function testMorphStemmer()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
        $stemmer = new MorphStemmer();
        $this->assertEquals('university', $stemmer->stem('universities'));
            
    }
}
