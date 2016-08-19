<?php

namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\CharFilter;

/**
 * Description of CharFilterTest
 *
 * @author yooper
 */
class CharFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testCharFilterDefaults()
    {
        $transformer = new CharFilter();
        
        $this->assertEquals(' ', $transformer->transform(' A '));
        $this->assertEquals(' ', $transformer->transform(' ! '));
        $this->assertEquals(' 9 ', $transformer->transform(' 9 '));                
        
        $this->assertEquals('A', $transformer->transform('A'));
        $this->assertEquals('!', $transformer->transform('!'));        
        $this->assertEquals('9', $transformer->transform('9'));                
    }
    
  
    
    
}