<?php

/**
 * Short cut functions calls
 */

if (! function_exists('bigrams')) {
	/**
	 *
	 * @param array $tokens
	 * @param string $separator
	 *
	 * @return array
	 */
	function bigrams( array $tokens, $separator = ' ' ) {
		return \TextAnalysis\NGrams\NGramFactory::create( $tokens, 2, $separator );
	}
}

if (! function_exists('trigrams')) {
	/**
	 *
	 * @param array $tokens
	 * @param string $separator
	 *
	 * @return array
	 */
	function trigrams( array $tokens, $separator = ' ' ) {
		return \TextAnalysis\NGrams\NGramFactory::create( $tokens, 3, $separator );
	}
}

