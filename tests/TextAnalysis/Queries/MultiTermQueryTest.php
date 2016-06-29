<?php

namespace Tests\TextAnalysis\Queries;

use TestBaseCase;

/**
 * Description of MultiTermQueryTest
 *
 * @author dcardin
 */
class MultiTermQueryTest extends TestBaseCase 
{
    public function testMultiTerm()
    {
        // the order has changed in the test cases since the php version bump
        $this->assertCount(2, $this->getInvertedIndex()->query("ironwood michigan"));
        $this->assertCount(2, $this->getInvertedIndex()->query("no ironwood"));        
    }
}
