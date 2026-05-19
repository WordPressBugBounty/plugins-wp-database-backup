<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Destination SFTP
 *
 * @package wpdbbkp
 */

require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

// Retrieve SFTP details.
$wpdbbkp_sftp_details = get_option( 'wp_db_backup_sftp_details', array() );
$wpdbbkp_host         = isset( $wpdbbkp_sftp_details['host'] ) ? $wpdbbkp_sftp_details['host'] : '';
$wpdbbkp_port         = isset( $wpdbbkp_sftp_details['port'] ) ? $wpdbbkp_sftp_details['port'] : 22;
$wpdbbkp_user         = isset( $wpdbbkp_sftp_details['username'] ) ? $wpdbbkp_sftp_details['username'] : '';
$wpdbbkp_pass         = isset( $wpdbbkp_sftp_details['password'] ) ? $wpdbbkp_sftp_details['password'] : '';
$wpdbbkp_pkey         = isset( $wpdbbkp_sftp_details['sftp_key'] ) ? base64_decode( $wpdbbkp_sftp_details['sftp_key'] ) : '';
$wpdbbkp_key_pass     = isset( $wpdbbkp_sftp_details['key_password'] ) ? $wpdbbkp_sftp_details['key_password'] : false;
$wpdbbkp_directory    = isset( $wpdbbkp_sftp_details['directory'] ) ? $wpdbbkp_sftp_details['directory'] : '';
if ( '' === $wpdbbkp_directory ) {
	$wpdbbkp_directory = '/';
}
$wpdbbkp_auth_type = isset( $wpdbbkp_sftp_details['auth_type'] ) ? $wpdbbkp_sftp_details['auth_type'] : 'password';

if ( ! empty( $wpdbbkp_host ) && ! empty( $wpdbbkp_user ) && ( ! empty( $wpdbbkp_pass ) || ( 'key' === $wpdbbkp_auth_type && ! empty( $wpdbbkp_pkey ) ) ) ) {
	$wpdbbkp_sftp = new SFTP( $wpdbbkp_host, $wpdbbkp_port );

	if ( $wpdbbkp_sftp ) {
		// Authenticate.
		if ( 'key' === $wpdbbkp_auth_type ) {
			$wpdbbkp_key    = PublicKeyLoader::load( $wpdbbkp_pkey, $wpdbbkp_key_pass );
			$wpdbbkp_result = $wpdbbkp_sftp->login( $wpdbbkp_user, $wpdbbkp_key );
		} else {
			$wpdbbkp_result = $wpdbbkp_sftp->login( $wpdbbkp_user, $wpdbbkp_pass );
		}

		// Upload file.
		if ( $wpdbbkp_result ) {
			$wpdbbkp_upload_dir            = wp_upload_dir();
			$wpdbbkp_upload_dir['basedir'] = str_replace( '\\', '/', $wpdbbkp_upload_dir['basedir'] );
			$wpdbbkp_remotefile            = $wpdbbkp_directory . '/' . $wpdbbkp_filename;
			$wpdbbkp_localfile             = trailingslashit( $wpdbbkp_upload_dir['basedir'] . '/db-backup' ) . $wpdbbkp_filename;
			$wpdbbkp_success               = $wpdbbkp_sftp->put( $wpdbbkp_remotefile, $wpdbbkp_localfile, SFTP::SOURCE_LOCAL_FILE | SFTP::RESUME_START );

			if ( $wpdbbkp_success ) {
				$wpdbbkp_args[2] = $wpdbbkp_args[2] . '<br> ' . esc_html__( 'Upload Database Backup on SFTP', 'wpdbbkp' ) . ' ' . $wpdbbkp_host;
				$wpdbbkp_args[4] .= 'SFTP, ';
			}
		}
	}

	if ( isset( $wpdbbkp_sftp ) && $wpdbbkp_sftp ) {
		$wpdbbkp_sftp->disconnect();
	}
}
