<?php

namespace Tests\TextAnalysis\Sentiment;

use TextAnalysis\Sentiment\Vader;

/**
 *
 * @author yooper
 */
class VaderTest extends \PHPUnit\Framework\TestCase
{
    
    public function testGetLexicon()
    {       
        if( getenv('SKIP_TEST')) {
            return;
        }
        
        $vader = new Vader;
        $this->assertCount(7503, $vader->getLexicon());        
    }
    
    public function testAllCapDifferential()
    {
        $vader = new Vader;
        $this->assertFalse($vader->allCapDifferential(['alright', 'no', 'caps']));
        $this->assertFalse($vader->allCapDifferential(['ALL', 'CAPS']));
        $this->assertTrue($vader->allCapDifferential(['some', 'CAPS']));        
    }
    
    public function testIsNegated()
    {
        $vader = new Vader;
        $this->assertFalse($vader->isNegated(['that','did','it']));
        $this->assertTrue($vader->isNegated(['that', 'didn\'t', 'it']));
        $this->assertTrue($vader->isNegated(['that', 'winn\'t', 'it']));                
    }
    
    public function testBoostExclamationPoints()
    {
        $vader = new Vader;        
        $this->assertEquals(0, $vader->boostExclamationPoints(['empty']));
        $this->assertEquals(0.292, $vader->boostExclamationPoints(array_fill(0,1,'!')));
        $this->assertEquals(0.584, $vader->boostExclamationPoints(array_fill(0,2,'!')));
        $this->assertEquals(0.876, $vader->boostExclamationPoints(array_fill(0,3,'!')));
        $this->assertEquals(1.168, $vader->boostExclamationPoints(array_fill(0,4,'!')));        
        $this->assertEquals(1.168, $vader->boostExclamationPoints(array_fill(0,5,'!')));               
    }
    
    
    public function testBoostQuestionMarks()
    {
        $vader = new Vader;        
        $this->assertEquals(0, $vader->boostQuestionMarks(['empty']));
        $this->assertEquals(0, $vader->boostQuestionMarks(array_fill(0,1,'?')));
        $this->assertEquals(0.36, $vader->boostQuestionMarks(array_fill(0,2,'?')));
        $this->assertEquals(0.54, $vader->boostQuestionMarks(array_fill(0,3,'?')));
        $this->assertEquals(0.96, $vader->boostQuestionMarks(array_fill(0,4,'?')));        
        $this->assertEquals(0.96, $vader->boostQuestionMarks(array_fill(0,5,'?')));               
    }
    
    public function testButCheck()
    {
        // 
        $tokens = ['lets', 'go', 'but', 'not', 'now'];
        $sentiments = array_fill(0, count($tokens), 1);
        
        $results = (new Vader())->butCheck($tokens, $sentiments);
        $this->assertEquals([1.5, 1.5, 1, 0.5, 0.5], $results);    
        
        // no buts ...
        $this->assertEquals(array_fill(0, 5, 1), (new Vader())->butCheck(array_fill(0, 5, 'token'), array_fill(0, 5, 1)) );        
    }
    
    
    public function testGetPolarityScores()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
        
        // taken from https://github.com/JWHennessey/phpInsight/blob/master/examples/demo.php
        $examples = [];
        
        $examples[] = ['sent' => "VADER is smart, handsome, and funny.", 'neg'=> 0.0, 'neu'=> 0.254, 'pos'=> 0.746, 'compound'=> 0.8316];
        $examples[] = ['sent' => "VADER is not smart, handsome, nor funny.", 'neg'=> 0.646, 'neu'=> 0.354, 'pos'=> 0.0, 'compound'=> -0.7424];
        $examples[] = ['sent' => "VADER is smart, handsome, and funny!", 'neg'=> 0.0, 'neu'=> 0.248, 'pos'=> 0.752, 'compound'=> 0.8439];
        $examples[] = ['sent' => "VADER is very smart, handsome, and funny.", 'neg'=> 0.0, 'neu'=> 0.299, 'pos'=> 0.701, 'compound'=> 0.8545];
        $examples[] = ['sent' => "VADER is VERY SMART, handsome, and FUNNY.", 'neg'=> 0.0, 'neu'=> 0.246, 'pos'=> 0.754, 'compound'=> 0.9227];
        $examples[] = ['sent' => "VADER is VERY SMART, handsome, and FUNNY!!!", 'neg'=> 0.0, 'neu'=> 0.233, 'pos'=> 0.767, 'compound'=> 0.9342];
        $examples[] = ['sent' => "VADER is VERY SMART, uber handsome, and FRIGGIN FUNNY!!!", 'neg'=> 0.0, 'neu'=> 0.294, 'pos'=> 0.706, 'compound'=> 0.9469];
        $examples[] = ['sent' => "The book was good.", 'neg'=> 0.0, 'neg'=> 0.0, 'neu'=> 0.508, 'pos'=> 0.492, 'compound'=> 0.4404];
        $examples[] = ['sent' => "The book was kind of good.", 'neg'=> 0.0, 'neu'=> 0.657, 'pos'=> 0.343, 'compound'=> 0.3832];
        $examples[] = ['sent' => "The plot was good, but the characters are uncompelling and the dialog is not great.", 'neg'=> 0.327, 'neu'=> 0.579, 'pos'=> 0.094, 'compound'=> -0.7042];
        $examples[] = ['sent' => "At least it isn't a horrible book.", 'neg'=> 0.0, 'neu'=> 0.637, 'pos'=> 0.363, 'compound'=> 0.431];
        $examples[] = ['sent' => "Make sure you :) or :D today!", 'neg'=> 0.0, 'neu'=> 0.294, 'pos'=> 0.706, 'compound'=> 0.8633];
        $examples[] = ['sent' => "Today SUX!", 'neg'=> 0.0, 'neg'=> 0.779, 'neu'=> 0.221, 'pos'=> 0.0, 'compound'=> -0.5461];
        $examples[] = ['sent' => "Today only kinda sux! But I'll get by, lol", 'neg'=> 0.179, 'neu'=> 0.569, 'pos'=> 0.251, 'compound'=> 0.2228];
    
        $vader = new Vader;
        
        foreach($examples as $test)
        {
            $result = $vader->getPolarityScores(tokenize($test['sent']));
        }
        
    }
    
    public function testIssue44OffsetError()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }
	    
	$vader = new Vader;
        $result = $vader->getPolarityScores([ 'great', 'for', 'the', 'jawbone']);
        $this->assertEquals(0.577, $result['pos']);
    }
        
}
