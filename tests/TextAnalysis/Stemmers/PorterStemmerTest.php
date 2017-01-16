<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\PorterStemmer;


/**
 * Description of PorterStemmerTest
 *
 * @author yooper
 */
class PorterStemmerTest extends \PHPUnit_Framework_TestCase
{
    public function testStemmer()
    {
        $stemmer = new PorterStemmer();
        $this->assertEquals('univers', $stemmer->stem('universities'));
        $this->assertEquals('judg',$stemmer->stem('judges'));
    }
}
