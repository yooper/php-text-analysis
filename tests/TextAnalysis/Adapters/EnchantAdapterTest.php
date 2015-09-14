<?php

namespace Tests\TextAnalysis\Adapters;

use TextAnalysis\Adapters\EnchantAdapter;

/**
 *
 * @author yooper
 */
class EnchantAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSpelling()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
        $adapter = new EnchantAdapter();
        $this->assertEquals('run', $adapter->suggest("runn")[0]);                
        $this->assertEquals('cooper', $adapter->suggest("yooper")[0]); 
        $this->assertEquals('flute', $adapter->suggest("flute")[0]);         
    }
}
