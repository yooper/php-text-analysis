<?php
declare(strict_types = 1);

namespace Tests\TextAnalysis\LexicalDiversity;

/**
 * Description of NaiveTest
 *
 * @author yooper
 */
class NaiveTest extends \TestBaseCase
{
    public function testDiversity()
    {
        $result = lexical_diversity( tokenize( $this->getText() ));
        $this->assertEqualsWithDelta(0.03461, $result, 0.0001);
    }
}
