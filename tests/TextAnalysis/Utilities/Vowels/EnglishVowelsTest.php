<?php
namespace Tests\TextAnalysis\Utilities\Vowels;
use TextAnalysis\Utilities\Vowels\VowelsAbstractFactory;

/**
 *
 * @author yooper
 */
class EnglishVowelsTest extends \PHPUnit_Framework_TestCase
{
    public function testIsVowel()
    {       
        $vowelChecker = VowelsAbstractFactory::factory("English");
        $this->assertTrue($vowelChecker->isVowel("man", 1));
    }
    
    public function testYIsVowel()
    {
        $vowelChecker = VowelsAbstractFactory::factory("English");
        $this->assertTrue($vowelChecker->isVowel("try", 2));
    }
}


