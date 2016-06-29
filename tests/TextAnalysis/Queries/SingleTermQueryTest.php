<?php

namespace Tests\TextAnalysis\Queries;

use TestBaseCase;

/**
 * Test Single Term
 * @author yooper
 */
class SingleTermQueryTest extends TestBaseCase
{
    public function testSingleTerm()
    {      
        $this->assertEquals(['michigan' => [0,1,2]], $this->getInvertedIndex()->query("michigan"));
        $this->assertEquals(['swimming' => [0,1]], $this->getInvertedIndex()->query("swimming"));       
    }
    
    public function testSingleTermNotFound()
    {        
        $this->assertEquals([], $this->getInvertedIndex()->query("none"));
        $this->assertEquals([], $this->getInvertedIndex()->query("php"));         
    }
}
