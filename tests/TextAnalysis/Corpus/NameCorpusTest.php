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
class NameCorpusTest extends \PHPUnit_Framework_TestCase
{
    public function testFirstNames()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }
        
        $corpus = new NameCorpus();
        $this->assertTrue($corpus->isFirstName('Dan'));
        $this->assertFalse($corpus->isFirstName('very'));
        
    }
    
    public function testLastNames()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }    
        
        $corpus = new NameCorpus();
        $this->assertTrue($corpus->isLastName('Williamson'));
        $this->assertFalse($corpus->isLastName('baggins'));                          
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
