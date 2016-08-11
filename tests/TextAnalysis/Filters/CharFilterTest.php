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
        $this->assertEquals(null, $transformer->transform('A'));
        $this->assertEquals(null, $transformer->transform('!'));        
        $this->assertEquals('9', $transformer->transform('9'));                
    }
    
    public function testCharFilterWhiteList()
    {
        $transformer = new CharFilter(['A','!']);
        $this->assertEquals('A', $transformer->transform('A'));
        $this->assertEquals('!', $transformer->transform('!'));        
        $this->assertEquals('9', $transformer->transform('9'));                
    }  
    
    public function testCharFilterBlackList()
    {
        $transformer = new CharFilter([],['9']);
        $this->assertEquals(null, $transformer->transform('A'));
        $this->assertEquals(null, $transformer->transform('!'));        
        $this->assertEquals(null, $transformer->transform('9'));                
    }    
    
    
}