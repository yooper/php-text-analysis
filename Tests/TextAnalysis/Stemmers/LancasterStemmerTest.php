<?php
namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\LancasterStemmer;
/**
 * Description of LancasterStemmerTest
 *
 * @author dcardin
 */
class LancasterStemmerTest extends \PHPUnit_Framework_TestCase
{
    public function testLancasterHasVowel()
    {
        $stemmer = new LancasterStemmer();
        
        $this->assertTrue($stemmer->hasVowelAtIndex("program", 2));
        $this->assertFalse($stemmer->hasVowelAtIndex("program", 0));
        
    }
    
    public function testLancasterStemmper()
    {
        $stemmer = new LancasterStemmer();
        $this->assertEquals('maxim', $stemmer->stem('maximum'));
        $this->assertEquals('presum', $stemmer->stem('presumably')); # Don't remove "-um" when word is not intac       
        $this->assertEquals('multiply', $stemmer->stem('multiply'));    # No action taken if word ends with "-ply"       
        $this->assertEquals('provid', $stemmer->stem('provision'));  # Replace "-sion" with "-j" to trigger "j" set of rules
        $this->assertEquals('ow', $stemmer->stem('owed'));       # Word starting with vowel must contain at least 2 letters      
        $this->assertEquals('ear', $stemmer->stem('ear'));        # ditto       
        $this->assertEquals('say', $stemmer->stem('saying'));      # Words starting with consonant must contain at least 3
        $this->assertEquals('cry', $stemmer->stem('crying'));      #     letters and one of those letters must be a vowel
        $this->assertEquals('string', $stemmer->stem('string'));      # ditto
        $this->assertEquals('meant', $stemmer->stem('meant'));      # ditto
        $this->assertEquals('cem', $stemmer->stem('cement'));      # ditto
  
        
    }    
}

