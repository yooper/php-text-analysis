<?php

namespace Tests\TextAnalysis\Extracts;

use TextAnalysis\Extracts\HashTag;

/**
 * Test if hashtag extraction is working
 * @author yooper
 */
class HashTagTest extends \PHPUnit\Framework\TestCase
{
    public function testHashTag()
    {
        $extract = new HashTag();
        $this->assertFalse($extract->filter("testing"));
        $this->assertEquals('#holiday', $extract->filter('#holiday'));
        $this->assertFalse($extract->filter('#DA'));
    }
    
    public function testMinLengthHashTag()
    {
        $extract = new HashTag(2);
        $this->assertEquals('#DA', $extract->filter('#DA'));
        $this->assertFalse($extract->filter('#1'));
    }
    
}
