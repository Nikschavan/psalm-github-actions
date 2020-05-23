<?php

require_once 'vendor/autoload.php';

$psalm_versions      = json_decode( Requests::get( 'https://repo.packagist.org/p/vimeo/psalm.json' )->body, true )[ 'packages' ][ 'vimeo/psalm' ];
$docker_template     = file_get_contents( 'Dockerfile.template' );
$entrypoint_template = file_get_contents( 'entrypoint.sh.template' );

foreach ( $psalm_versions as $version => $package ) {
    // Track only versions 3.0 and above
    if ( version_compare( $version, '3.0', '>=') ) {
        shell_exec( 'mkdir -p images/' . $version );

        $dockerfile = str_replace( '%%PSALM_VERSION%%', ':' . $version, $docker_template );

        write_file( 'Dockerfile', 'images/' . $version . '/' . $dockerfile );
        write_file( 'entrypoint.sh', 'images/' . $version . '/' . $entrypoint_template );
    }
}

/**
 * Write file.
 *
 * @param string $file     File path.
 * @param string $contents File contents.
 */
function write_file( $file, $contents ) {
	$fh = fopen( $file, 'w' );
	fwrite( $fh, $contents );
	fclose( $fh );
}