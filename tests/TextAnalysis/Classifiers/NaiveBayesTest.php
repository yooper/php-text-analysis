<?php

namespace Tests\TextAnalysis\Classifiers;

use TextAnalysis\Classifiers\NaiveBayes;

/**
 * Description of NaiveBayesTest
 *
 * @author yooper
 */
class NaiveBayesTest extends \PHPUnit_Framework_TestCase
{
    public function testNaiveBayes()
    {
        $classifier = new NaiveBayes();
        
        $examples = [
            ['mexican', 'taco nacho enchilada burrito'],
            ['americian', 'hamburger burger fries pop']
        ];
                        
       foreach($examples as $row)
       {
           $classifier->train($row[0], tokenize($row[1]));
       }
        
       $this->assertEquals('mexican', $classifier->predict(tokenize('my favorite food is a burrito')));
        
    }
    
    public function testBayes()
    {
        $samples = [[5, 1, 1], [1, 5, 1], [1, 1, 5]];
        $labels = ['a', 'b', 'c'];
        $classifier = new NaiveBayes();
        $classifier->train($labels[0], $samples[0]);
        $classifier->train($labels[1], $samples[1]);
        $classifier->train($labels[2], $samples[2]);
        
        
        $this->assertEquals('a', $classifier->predict([3, 1, 1]));
        $this->assertEquals('b', $classifier->predict([1, 4, 1]));
        $this->assertEquals('c', $classifier->predict([1, 1, 6]));        
    }
}
