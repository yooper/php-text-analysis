<?php

if (! function_exists('get_storage_path')) {
	/**
	 * Base function for getting the storage path to the different directories.
	 * @return string
	 */
	function get_storage_path( $subDirName = null ) {
		$path = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;

		if ( ! empty( $path ) ) {
			$path .= $subDirName;
		}

		if ( ! is_dir( $path ) ) {
			throw new Exception( "Path {$path} does not exist" );
		}

		return realpath( $path ) . DIRECTORY_SEPARATOR;

	}
}

