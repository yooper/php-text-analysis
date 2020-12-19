<?php
declare(strict_types = 1);

namespace Tests\TextAnalysis\LexicalDiversity;

/**
 * Description of Yule I Test
 *
 * @author yooper
 */
class YuleKTest extends \TestBaseCase
{
    public function testDiversity()
    {
        $result = lexical_diversity( tokenize( $this->getText() ), \TextAnalysis\LexicalDiversity\YuleK::class);
        $this->assertEqualsWithDelta(73.5712, $result, 0.0001);
    }
}
