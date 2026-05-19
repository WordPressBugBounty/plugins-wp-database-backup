<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Destination ftp
 *
 * @package wpdbbkp
 */

// Set up variables.
$wpdbbkp_host          = get_option( 'backupbreeze_ftp_host' );
$wpdbbkp_user          = get_option( 'backupbreeze_ftp_user' );
$wpdbbkp_pass          = get_option( 'backupbreeze_ftp_pass' );
$wpdbbkp_subdir        = get_option( 'backupbreeze_ftp_subdir' );
$wpdbbkp_upload_dir    = wp_upload_dir();
$wpdbbkp_upload_dir['basedir'] = str_replace( '\\', '/', $wpdbbkp_upload_dir['basedir'] );
$wpdbbkp_remotefile    = $wpdbbkp_subdir . '/' . $wpdbbkp_filename;
$wpdbbkp_localfile     = trailingslashit( $wpdbbkp_upload_dir['basedir'] . '/db-backup' ) . $wpdbbkp_filename;
if ( isset( $wpdbbkp_host ) && ! empty( $wpdbbkp_host ) && isset( $wpdbbkp_user ) && ! empty( $wpdbbkp_user ) && isset( $wpdbbkp_pass ) && ! empty( $wpdbbkp_pass ) ) {
	// See if port option is blank and set it to 21 if it isn't.
	if ( ! get_option( 'backupbreeze_ftp_port' ) ) {
		$wpdbbkp_port = '21';
	} else {
		$wpdbbkp_port = get_option( 'backupbreeze_ftp_port' );
	}
	$wpdbbkp_conn = ftp_connect( $wpdbbkp_host, $wpdbbkp_port );
	if ( $wpdbbkp_conn ) {
		$wpdbbkp_result = ftp_login( $wpdbbkp_conn, $wpdbbkp_user, $wpdbbkp_pass );
		if ( $wpdbbkp_result ) {
			// Switch to passive mode.
			ftp_pasv( $wpdbbkp_conn, true );
			// Upload file.
			$wpdbbkp_success = ftp_put( $wpdbbkp_conn, $wpdbbkp_remotefile, $wpdbbkp_localfile, FTP_BINARY );
			if ( $wpdbbkp_success ) {
				$wpdbbkp_args[2] = $wpdbbkp_args[2] . '<br> ' . esc_html__( 'Upload Database Backup on FTP ', 'wpdbbkp' ) . $wpdbbkp_host;
				$wpdbbkp_args[4] .= 'FTP, ';
			}
		}
	}
	// Close connection to host.
	if ( ! is_bool( $wpdbbkp_conn ) ) {
		ftp_quit( $wpdbbkp_conn );
	}
}
