<?php

namespace TextAnalysis\NGrams;

/**
 * Generate ngrams
 *
 * @author yooper <yooper>
 */
class NGramFactory
{
    const BIGRAM = 2;
    const TRIGRAM = 3;

    /**
    * Protect the constructor
    */
    protected function __construct(){}

        /**
        * Generate Ngrams from the tokens
        * @param array $tokens
        * @param int $nGramSize
        * @return  array return an array of the ngrams
        */
        static public function create(array $tokens, $nGramSize = self::BIGRAM, $separator = ' ') : array
        {
            $freq = freq_dist($tokens)->getKeyValuesByFrequency();
            $separatorLength = strlen($separator);
            $length = count($tokens) - $nGramSize + 1;
            $lengthToken = count($tokens);

            if($length < 1) {
                return [];
            }
            $ngrams = array(); // initialize the array

            for($index = 0; $index < $length; $index++)
            {
                $ngram_string = '';
                $ngram = array();
                for($jindex = 0; $jindex < $nGramSize; $jindex++)
                {
                    $ngram_string .= $tokens[$index + $jindex];

                    // checks if is the first token and act accordingly
                    if($tokens[$index + $jindex] === $tokens[0] && $jindex == 1) {
                        $ngram[$jindex] = $freq[$tokens[$index + $jindex]] - 1;
                    } elseif($tokens[$index + $jindex] === $tokens[$lengthToken-1] && $jindex == 0) {
                        $ngram[$jindex] = $freq[$tokens[$index + $jindex]] - 1;
                    } else {
                        $ngram[$jindex]   = $freq[$tokens[$index + $jindex]]; // adds the token frequency
                    }

                    if($jindex < $nGramSize - $separatorLength) {
                        $ngram_string .= $separator;
                    } else {
                        if(isset($ngrams[$ngram_string][$nGramSize]) || array_key_exists($ngram_string, $ngrams)) { // checks if it was already counted
                            $ngrams[$ngram_string][$nGramSize]++;
                        }else {
                            $ngram[$nGramSize] = 1;
                            $ngrams[$ngram_string] = $ngram;
                        }
                    }
                }
            }

            return $ngrams;
        }
}
