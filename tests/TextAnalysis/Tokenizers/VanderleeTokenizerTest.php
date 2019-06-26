<?php
namespace Tests\TextAnalysis\Tokenizers;

use TextAnalysis\Tokenizers\VanderleeTokenizer;

/**
 *
 * @author yooper
 */
class VanderleeTokenizerTest extends \PHPUnit\Framework\TestCase
{
    public function testTokenizer()
    {
        $tokenizer = new VanderleeTokenizer();
        $sentences = $tokenizer->tokenize($this->getText());
        $this->assertCount(5, $sentences);
    }
    
    protected function getText()
    {
        return <<<TEXT
Hello there, Mr. Smith. What're you doing today... Smith, my friend?\n\nI hope it's good. This last sentence will cost you $2.50! Just kidding :)
TEXT;
    }
}
