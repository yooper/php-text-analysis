<?php
namespace Tests\TextAnalysis\Aggregates;

use TextAnalysis\Aggregates\CollocationAggregate;
use TextAnalysis\Tokenizers\GeneralTokenizer;


/** *
 * Test the collocation
 * @author yooper
 */
class CollocationAggregateTest extends \Tests\Base
{    
    
    public function testCollocation()
    {    
        $text ="Marquette Township is home to one of the most diverse shopping 
            centers in the Upper Peninsula. Many national retailers have built 
            stores in the township as it provided buildable acreage that the 
            city of Marquette no longer could provide. Marquette Township is 
            home to the U.P.'s only Target store, one of only two Menards 
            stores, as well as a Wal-Mart Supercenter and a variety of other 
            shops and restaurants.";    
                
        $tokenizer = new GeneralTokenizer();
        $collocation = new CollocationAggregate($text, $tokenizer->tokenize($text));
        $keyValues = $collocation->getAggregate();
        
        $this->assertTrue(array_key_exists("Marquette Township", $keyValues));
        
    }

       
}
