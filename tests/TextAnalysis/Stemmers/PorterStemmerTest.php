<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\PorterStemmer;


/**
 * Description of PorterStemmerTest
 *
 * @author yooper
 */
class PorterStemmerTest extends \PHPUnit\Framework\TestCase
{
    public function testStemmer()
    {
        $stemmer = new PorterStemmer();
        $this->assertEquals('univers', $stemmer->stem('universities'));
        $this->assertEquals('judg',$stemmer->stem('judges'));
    }
    
    public function testSimplifiedStemmer()
    {
        $this->assertEquals(['univers','judg'], stem(['universities', 'judges']));
    }    
    
}
