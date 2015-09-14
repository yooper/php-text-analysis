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
        return preg_split('/[\pZ\pC]+/u', $string, null, PREG_SPLIT_NO_EMPTY);
    }
}


