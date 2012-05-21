<?php
namespace Test\Text\Analysis;

/**
 * Description of KeywordDensityTest
 *
 * @author yooper
 */
class KeywordDensityTest extends \Test\BaseUnitTest{
    public function testKeywordDensityLorem(){ 
        
        $kd = new \Text\Analysis\KeywordDensity();
        $text = file_get_contents(TESTS_PATH.'data'.DS.'Text'.DS.'Analysis'.DS.'text.txt');
        
        $this->assertEmpty($kd->getKeyWordDensityTable());
        $kd->analyzeString($text, 2);
        $this->assertNotEmpty($kd->getKeyWordDensityTable());
        
        $wordDensity = $kd->getKeyWordDensityTable();

        $this->assertEquals(1, $wordDensity[1]['lorem ipsum']);
        
    }
}

