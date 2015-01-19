<?php

namespace Tests\Utilities;

use TextAnalysis\Utilities\String;

/**
 * Description of StringTest
 *
 * @author dcardin
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testAllSubstrings()
    {
        $text = 'abc';
        $expected = ['a','ab','abc','b','bc','c'];
        
        $substrings = String::getAllSubStrings($text);
        $this->assertCount(6, $substrings);
        $this->assertEquals($expected, $substrings);
    }
    
    public function testTextToBin()
    {
        $this->assertEquals('011000110110000101110100', String::textToBin('cat'));
        $this->assertEquals('011000110110000101110100', String::textToBin('cat'));
        $this->assertEquals('01110100011010010110111001111001', String::textToBin('tiny'));
    }
}
