<?php
namespace TextAnalysis\Tokenizers;
/**
 * Description of WhitespaceTokenizer
 *
 * @author yooper
 */
class WhitespaceTokenizer extends TokenizerAbstract
{
    public function tokenize($string)
    {
        return mb_split('\s+', $string);
    }
}


