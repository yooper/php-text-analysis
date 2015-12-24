<?php

namespace Tests\TextAnalysis\Stemmers;

use TextAnalysis\Stemmers\LambdaStemmer;

/**
 *
 * @author yooper
 */
class LambdaStemmerTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleLambda()
    {
        $lambdaFunc = function($word) {
            return preg_filter("/my/i", "", $word);
        };
        
        $stemmer = new LambdaStemmer($lambdaFunc);
        $this->assertEquals("tom", $stemmer->stem("tommy"));
    }
}
