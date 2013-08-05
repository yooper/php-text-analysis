<?php
namespace Tests\TextAnalysis\Utilities;
use TextAnalysis\Utilities\Vowel;

/**
 *
 * @author Dan Cardin
 */
class VowelTest extends \PHPUnit_Framework_TestCase
{
    public function testIsVowel()
    {       
        $this->assertTrue(Vowel::isVowel("man", 1));
    }
    
    public function testYIsVowel()
    {
        $this->assertTrue(Vowel::isVowel("try", 2));
    }
}


