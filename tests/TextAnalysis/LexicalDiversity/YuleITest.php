<?php
declare(strict_types = 1);

namespace Tests\TextAnalysis\LexicalDiversity;

/**
 * Description of Yule I Test
 *
 * @author yooper
 */
class YuleITest extends \TestBaseCase
{
    public function testDiversity()
    {
        $result = lexical_diversity( tokenize( $this->getText() ), \TextAnalysis\LexicalDiversity\YuleI::class);
        $this->assertEqualsWithDelta(135.9226, $result, 0.0001);
    }
}
