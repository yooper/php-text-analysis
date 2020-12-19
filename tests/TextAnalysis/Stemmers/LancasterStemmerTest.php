<?php
declare(strict_types = 1);
namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\LancasterStemmer;
/**
 * Description of LancasterStemmerTest
 *
 * @author yooper
 */
class LancasterStemmerTest extends \PHPUnit\Framework\TestCase
{    
    public function testLancasterStemmper()
    {
        $stemmer = new LancasterStemmer();
        $this->assertEquals('maxim', $stemmer->stem('maximum'));
        $this->assertEquals('presum', $stemmer->stem('presumably'));       
        $this->assertEquals('multiply', $stemmer->stem('multiply'));     
        $this->assertEquals('provid', $stemmer->stem('provision'));  
        $this->assertEquals('ow', $stemmer->stem('owed'));            
        $this->assertEquals('ear', $stemmer->stem('ear'));           
        $this->assertEquals('say', $stemmer->stem('saying'));      
        $this->assertEquals('cry', $stemmer->stem('crying'));
        $this->assertEquals('string', $stemmer->stem('string'));
        $this->assertEquals('meant', $stemmer->stem('meant')); 
        $this->assertEquals('cem', $stemmer->stem('cement')); 
        $this->assertEquals( null, $stemmer->stem(' ')); 
        
    }    
}

