<?php
namespace Tests\TextAnalysis\Analysis\Keyword;

/**
 * Description of DensityTest
 *
 * @author yooper
 */
class DensityTest extends \Tests\Base
{
    public function testDensityLorem()
    { 
        
        $kd = new \TextAnalysis\Analysis\Keyword\Density();
        $text = file_get_contents(TESTS_PATH.'data'.DS.'Text'.DS.'Analysis'.DS.'text.txt');
        
        $this->assertEmpty($kd->getKeyWordDensityTable());
        $kd->analyzeString($text, 2);
        $this->assertNotEmpty($kd->getKeyWordDensityTable());
        
        $wordDensity = $kd->getKeyWordDensityTable();

        $this->assertEquals(1, $wordDensity[1]['lorem ipsum']);
        
    }
}

