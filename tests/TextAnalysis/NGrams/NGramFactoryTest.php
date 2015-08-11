<?php

namespace Tests\TextAnalysis\NGrams;

use TextAnalysis\NGrams\NGramFactory;

/**
 * Description of NGramFactoryTest
 *
 * @author yooper <yooper>
 */
class NGramFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBiGram()
    {
        $tokens = ["one","two","three"];
        $expected = ["one two","two three"];
        $bigrams = NGramFactory::create($tokens);
        $this->assertEquals($expected, $bigrams);        
    }
    
    public function testTriGram()
    {
        $tokens = ["one","two","three","four"];
        $expected = ["one two three","two three four"];
        $bigrams = NGramFactory::create($tokens, NGramFactory::TRIGRAM);
        $this->assertEquals($expected, $bigrams);        
    }    
}
