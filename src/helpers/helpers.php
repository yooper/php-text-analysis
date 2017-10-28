<?php

/**
 * A set of helpers that are used repeatedly throughout this project and
 * make it easier to access core functionality
 */


/**
 * Normalize the tokens
 * @param array $tokens
 * @param string|function $normalizer
 * @return array
 */
function normalize_tokens(array $tokens, $normalizer = 'strtolower') : array
{
    return array_map($normalizer, $tokens);
}

/**
 * Tokenize text into an array of tokens
 * @param string $text
 * @param string $tokenizerClassName
 * @return array
 */
function tokenize(string $text, string $tokenizerClassName = \TextAnalysis\Tokenizers\GeneralTokenizer::class) : array
{
    return (new $tokenizerClassName())->tokenize($text);
}

/**
 * Shortcut for getting a freq distribution instance 
 * @param array $tokens
 * @return \TextAnalysis\Analysis\FreqDist
 */
function freq_dist(array $tokens) : \TextAnalysis\Analysis\FreqDist
{
    return new \TextAnalysis\Analysis\FreqDist($tokens);
}

/**
 * 
 * @param array $tokens
 * @param type $lexicalDiversityClassName
 * @return float
 */
function lexical_diversity(array $tokens, $lexicalDiversityClassName = \TextAnalysis\LexicalDiversity\Naive::class) : float
{
    return (new $lexicalDiversityClassName())->getDiversity($tokens);
}

/**
 * 
 * @param array $tokens
 * @param type $nGramSize
 * @param type $separator
 * @return array
 */
function ngrams(array $tokens, $nGramSize = 2, $separator = ' ') : array
{
    return \TextAnalysis\NGrams\NGramFactory::create($tokens, $nGramSize, $separator);
}

/**
 * 
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function starts_with(string $haystack, string $needle) : bool
{
    return \TextAnalysis\Utilities\Text::startsWith($haystack, $needle);
}

/**
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function ends_with(string $haystack, string $needle) : bool
{
    return \TextAnalysis\Utilities\Text::endsWith($haystack, $needle);
}

