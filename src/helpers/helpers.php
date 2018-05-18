<?php

/**
 * A set of helpers that are used repeatedly throughout this project and
 * make it easier to access core functionality
 */

if (! function_exists('normalize_tokens')) {
	/**
	 * Normalize the tokens
	 *
	 * @param array $tokens
	 * @param string|function $normalizer
	 *
	 * @return array
	 */
	function normalize_tokens( array $tokens, $normalizer = 'strtolower' ): array {
		return array_map( $normalizer, $tokens );
	}
}

if (! function_exists('tokenize')) {
	/**
	 * Tokenize text into an array of tokens
	 *
	 * @param string $text
	 * @param string $tokenizerClassName
	 *
	 * @return array
	 */
	function tokenize( string $text, string $tokenizerClassName = \TextAnalysis\Tokenizers\GeneralTokenizer::class ): array {
		return ( new $tokenizerClassName() )->tokenize( $text );
	}
}

if (! function_exists('freq_dist')) {
	/**
	 * Shortcut for getting a freq distribution instance
	 *
	 * @param array $tokens
	 *
	 * @return \TextAnalysis\Analysis\FreqDist
	 */
	function freq_dist( array $tokens ): \TextAnalysis\Analysis\FreqDist {
		return new \TextAnalysis\Analysis\FreqDist( $tokens );
	}
}

if (! function_exists('lexical_diversity')) {
	/**
	 *
	 * @param array $tokens
	 * @param type $lexicalDiversityClassName
	 *
	 * @return float
	 */
	function lexical_diversity( array $tokens, $lexicalDiversityClassName = \TextAnalysis\LexicalDiversity\Naive::class ): float {
		return ( new $lexicalDiversityClassName() )->getDiversity( $tokens );
	}
}

if (! function_exists('ngrams')) {
	/**
	 *
	 * @param array $tokens
	 * @param type $nGramSize
	 * @param type $separator
	 *
	 * @return array
	 */
	function ngrams( array $tokens, $nGramSize = 2, $separator = ' ' ): array {
		return \TextAnalysis\NGrams\NGramFactory::create( $tokens, $nGramSize, $separator );
	}
}

if (! function_exists('starts_with')) {
	/**
	 *
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	function starts_with( string $haystack, string $needle ): bool {
		return \TextAnalysis\Utilities\Text::startsWith( $haystack, $needle );
	}
}

if (! function_exists('ends_with')) {
	/**
	 * @param string $haystack
	 * @param string $needle
	 *
	 * @return bool
	 */
	function ends_with( string $haystack, string $needle ): bool {
		return \TextAnalysis\Utilities\Text::endsWith( $haystack, $needle );
	}
}

if (! function_exists('text')) {
	/**
	 * Returns an instance of the TextCorpus
	 *
	 * @param string $text
	 *
	 * @return \TextAnalysis\Corpus\TextCorpus
	 */
	function text( string $text ): \TextAnalysis\Corpus\TextCorpus {
		return new \TextAnalysis\Corpus\TextCorpus( $text );
	}
}


if (! function_exists('rake')) {
    /**
    * Returns an instance of the Rake
    *
    * @param array $tokens
    *
    * @return \TextAnalysis\Analysis\Keywords\Rake
    */
    function rake(array $tokens, int $ngramSize = 3): \TextAnalysis\Analysis\Keywords\Rake 
    {
        return new \TextAnalysis\Analysis\Keywords\Rake(new \TextAnalysis\Documents\TokensDocument($tokens), $ngramSize);
    }
}

if (! function_exists('stem')) {
    /**
    * Returns an array of stemmed tokens
    *
    * @param array $tokens
    *
    * @return \TextAnalysis\Analysis\Keywords\Rake
    */
    function stem(array $tokens, string $stemmerClassName = \TextAnalysis\Stemmers\PorterStemmer::class): array 
    {
	$stemmer = new $stemmerClassName();
        return array_map(function($token) use($stemmer){ return $stemmer->stem($token); }, $tokens);
    }
}



/**
 * Check if the given array has the given needle, using a case insensitive search. 
 * Keeps a local copy of the normalized haystack for quicker lookup on the same array
 * @staticvar array $localCopy
 * @staticvar string $checksum
 * @param string $needle
 * @param array $haystack
 * @return bool
 */
function in_arrayi(string $needle, array $haystack) : bool
{
    static $localCopy = [];
    static $checksum = null;
    
    $haystackChecksum = md5(json_encode($haystack));
    if($checksum != $haystackChecksum) {
        $checksum = $haystackChecksum;
        $localCopy = array_fill_keys(array_map('strtolower', $haystack), true);
    }    
    return isset($localCopy[strtolower($needle)]);    
}

/**
 * Get the index of the needle using a case insensitive search. Keeps a local
 * copy of the normalized haystack for quicker lookup on the same array
 * @staticvar array $localCopy
 * @staticvar string $checksum
 * @param string $needle
 * @param array $haystack
 * @return bool
 */
function array_searchi(string $needle, array $haystack)
{
    static $localCopy = [];
    static $checksum = null;
    
    $haystackChecksum = md5(json_encode($haystack));
    if($checksum != $haystackChecksum) {
        $checksum = $haystackChecksum;
        $localCopy = array_map('strtolower', $haystack);
    }    
    return array_search($needle, $localCopy); 
}


/**
 * Load a book into memory
 * @param string $filename
 * @return string
 */
function gutenberg(string $filename) : string
{
    return file_get_contents(get_storage_path("corpora/gutenberg").$filename);
}

/**
 * Return a list of books available
 * @return array
 */
function gutenberg_list() : array
{
    return scan_dir(get_storage_path("corpora/gutenberg/")); 
}

/**
 * Shortcut function for getting contents of directory
 * @param string $dir
 * @return array
 */
function scan_dir(string $dir) : array
{
    return array_diff(scandir($dir), ['..', '.']);    
}




