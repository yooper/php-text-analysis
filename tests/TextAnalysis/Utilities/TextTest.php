<?php

namespace Tests\Utilities;

use TextAnalysis\Utilities\Text;

/**
 * Description of TextTest
 *
 * @author yooper
 */
class TextTest extends \PHPUnit\Framework\TestCase
{
    public function testAllSubstrings()
    {
        $text = 'abc';
        $expected = ['a','ab','abc','b','bc','c'];
        
        $substrings = Text::getAllSubStrings($text);
        $this->assertCount(6, $substrings);
        $this->assertEquals($expected, $substrings);
    }
    
    public function testEndsWith()
    {
        $this->assertTrue(Text::endsWith('lunches', 's'));        
        $this->assertTrue(Text::endsWith('lunches', 'es'));
        $this->assertTrue(Text::endsWith('lunches', 'hes'));       
        $this->assertFalse(Text::endsWith('joe', 'is'));                
    }
}
