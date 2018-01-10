<?php

/**
 * Some print functions to make things easier to read
 */

if (! function_exists('print_array')) {
	/**
	 * Print out the strings in the array
	 *
	 * @param array $strings
	 * @param string $prefix
	 * @param string $newline
	 */
	function print_array( array $strings, $prefix = '* ', $newline = PHP_EOL ) {
		array_walk( $strings, function ( $string ) use ( $newline, $prefix ) {
			echo $prefix . $string . $newline;
		} );
	}
}

