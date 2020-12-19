<?php
declare(strict_types = 1);
namespace TextAnalysis\Tokenizers;
/**
 * Description of WhitespaceTokenizer
 *
 * @author yooper
 */
class WhitespaceTokenizer extends TokenizerAbstract
{
    public function tokenize(string $string): array
    {
        return mb_split('\s+', $string ?? '');
    }
}


