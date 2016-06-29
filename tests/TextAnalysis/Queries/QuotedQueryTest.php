<?php

namespace Tests\TextAnalysis\Queries;

use TestBaseCase;

/**
 * Use the quoted query 
 *
 * @author yooper
 */
class QuotedQueryTest extends TestBaseCase
{
    public function testQuotes()
    {                        
        $r = $this->getInvertedIndex()->query("'ironwood michigan'");        
        $this->assertCount(2, $r);
        $this->assertEquals(['ironwood' => [1], 'michigan' => [1]], $r);        
        $this->assertCount(1, $this->getInvertedIndex()->query("'ironwood'"));        
        $this->assertEquals(['ironwood' => [1]], $this->getInvertedIndex()->query("'ironwood'"));        
    }    
    
}
