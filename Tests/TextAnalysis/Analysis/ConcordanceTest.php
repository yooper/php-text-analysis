<?php
namespace Tests\TextAnalysis\Analysis;

use TextAnalysis\Analysis\Concordance;
/**
 *
 * @author yooper
 */
class DensityTest extends \Test\BaseUnitTest
{
    public function testConcordance(){ 
        
        $text = file_get_contents(TESTS_PATH.DS.'data'.DS.'books'.DS.'tom_sawyer.txt');
        $concordance = new Concordance($text, false);
        $concordance->addSearchText("mansion");
        $concordance->addSearchText('Tom');
        
        $results = $concordance->execute()->getResults();
        
        $this->assertEquals(2, count($results['mansion']));
        
        $this->assertEquals(854, count($results['Tom']));
    }
}