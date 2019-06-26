<?php

namespace Tests\TextAnalysis\Corpus;

use TextAnalysis\Corpus\NameCorpus;
use Mockery;
use TextAnalysis\Corpus\ImportCorpus;

/**
 * Test out the name corpus
 *
 * @author yooper
 */
class NameCorpusTest extends \PHPUnit\Framework\TestCase
{
    public function testFirstNames()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }
        
        $corpus = new NameCorpus();
        $this->assertTrue($corpus->isFirstName('Mike'));        
        $this->assertFalse($corpus->isFirstName('very'));        
    }

    public function testGetFirstName()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }    
        
        $corpus = new NameCorpus();
        $firstName = $corpus->getFirstName('Mike');
        $this->assertNotEmpty($firstName);
        
        $this->assertEmpty($corpus->getFirstName('very'));       
    }
    
    
    public function testLastNames()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }    
        
        $corpus = new NameCorpus();
        $this->assertTrue($corpus->isLastName('Williamson'));
        $this->assertFalse($corpus->isLastName('Baggins'));                          
    }
    
    public function testGetLastName()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }    
        
        $corpus = new NameCorpus();
        $lastName = $corpus->getLastName('Williamson');
        $this->assertEquals(245, $lastName['rank']); 

        $lastName = $corpus->getLastName('Baggins');
        $this->assertEmpty($lastName);        
    }
    
    
    public function testFullNames()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }    
        
        $corpus = new NameCorpus();
        $this->assertTrue($corpus->isFullName('Brad Von Williamson'));
        $this->assertFalse($corpus->isFullName('Jimbo'));        
        $this->assertTrue($corpus->isFullName('Bradley Thomas'));        
    }  
    
    
    
}
