<?php
namespace Tests\TextAnalysis\Indexes;

use TestBaseCase;
use TextAnalysis\Documents\TokensDocument;

/**
 * Test the inverted index
 * @author yooper
 */
class InvertedIndexTest extends TestBaseCase
{
    public function tearDown()
    {
        // reset the inverted index
        $this->invertedIndex = null;
    }
    
    public function testRemoveDocFound()
    {
        $docId = 1;
        // remove all doc's with id 1
        $this->assertCount(7, $this->getInvertedIndex()->getTermsByDocumentId($docId));
        $this->assertTrue($this->getInvertedIndex()->removeDocument($docId));        
        $this->assertCount(0, $this->getInvertedIndex()->getTermsByDocumentId($docId));
    } 
    
    public function testAddDoc()
    {
        $docId = 4;
        $doc = new TokensDocument(['canada','usa','mexico','uk','poland'], $docId);        
        $this->getInvertedIndex()->addDocument($doc);        
        $this->assertCount(5, $this->getInvertedIndex()->getTermsByDocumentId($docId));
    } 
    
    public function testGetTermsByDocumentId()
    {
        $expected = ["marquette", "michigan", "hiking" , "camping", "swimming"];
        sort($expected);
        $actual = $this->getInvertedIndex()->getTermsByDocumentId(0);
        sort($actual);
        $this->assertEquals($expected, $actual);        
    }
    
}
