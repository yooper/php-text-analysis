<?php

namespace TextAnalysis\NGrams;

use TextAnalysis\NGrams\Statistic2D;
use TextAnalysis\NGrams\Statistic3D;

/**
 * Generate statistic values for Ngrams. The array must contain the frequencies setup by NGramFactory::getFreq().
 * Based on The Ngram Statistics Package (Text::NSP) <http://www.d.umn.edu/~tpederse/Pubs/cicling2003-2.pdf>
 * @author Kaue Oliveira Almeida <Euak>
 */
class StatisticFacade
{
    protected function __construct(){}

    /**
    * Calculate the statistic for an ngram array
    * @param array $ngrams Array of ngrams
    * @param string $measure Name of the statistic measure
    * @param int $nGramSize Size of the ngrams
    * @return array Return the ngram array with the statistic values
    */
    public static function calculate(array $ngrams, string $measure, int $nGramSize = 2) : array
    {
        $totalNgrams = array_sum(array_column($ngrams, 0));
        return array_map( function($item) use($measure, $totalNgrams, $nGramSize) {
            if ($nGramSize == 2) {
                return Statistic2D::$measure($item, $totalNgrams);
            } elseif ($nGramSize == 3) {
                return Statistic3D::$measure($item, $totalNgrams);
            } else {
                throw new \Exception("Size of the ngram informed invalid", 1);
            }
        }, $ngrams);
    }
}
