<?php

namespace Tests\TextAnalysis\Adapters;

use TextAnalysis\Adapters\PspellAdapter;

/**
 * Description of PspellAdapterTest
 *
 * @author yooper
 */
class PspellAdapterTest extends \PHPUnit\Framework\TestCase
{
    public function testSpelling()
    {
        if( getenv('SKIP_TEST')) {
            return;
        }        
        $adapter = new PspellAdapter();
        $this->assertEquals('run', $adapter->suggest("runn")[0]);                
        $this->assertEquals('Cooper', $adapter->suggest("yooper")[0]); 
        $this->assertEquals('flute', $adapter->suggest("flute")[0]);         
    }
}
