<?php

    /**
    * Base function for getting the storage path to the different directories.
    * @return string|false
    */
    function get_storage_path( $subDirName = null ) 
    {            
        $path = 'storage' . DIRECTORY_SEPARATOR;

        if ( ! empty( $path ) ) {
            $path .= $subDirName;
        }

        if ( ! is_dir( $path ) ) {
            throw new Exception( "Path {$path} does not exist" );
        }

        return realpath( $path ) . DIRECTORY_SEPARATOR;
    }


