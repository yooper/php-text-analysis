<?php
namespace Tests\TextAnalysis\Filters;

use TextAnalysis\Filters\LambdaFilter;

/**
 * @author yooper (yooper)
 */
class LambdaFilterTest extends \PHPUnit\Framework\TestCase
{
    public function testLambdaPregFilter()
    {
        $lambda = function($word){
            return preg_filter("/bob/", "tom", $word);
        };
        $transformer = new LambdaFilter($lambda);
        $this->assertEquals("tomtom", $transformer->transform("bobbob"));
    }
        
    public function testLambdaStrReplace()
    {
        $lambda = function($word){
            return str_replace("bob", "tom", $word);
        };
        $transformer = new LambdaFilter($lambda);
        $this->assertEquals("tomtom", $transformer->transform("bobbob"));
    }    
    
}

