<?php

namespace Tests\Utilities;

use TextAnalysis\Utilities\Text;

/**
 * Description of TextTest
 *
 * @author yooper
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testAllSubstrings()
    {
        $text = 'abc';
        $expected = ['a','ab','abc','b','bc','c'];
        
        $substrings = Text::getAllSubStrings($text);
        $this->assertCount(6, $substrings);
        $this->assertEquals($expected, $substrings);
    }
}
