<?php
/**
 * Destination test.
 *
 * @package wpdbbkp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( ! current_user_can( 'manage_options' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Test app.
 */
function wpdbbkp_test_ftp() {

	// Now let's see if we can connect to the FTP repo.
	$wpdbbkp_host   = get_option( 'backupbreeze_ftp_host' );
	$wpdbbkp_user   = get_option( 'backupbreeze_ftp_user' );
	$wpdbbkp_pass   = get_option( 'backupbreeze_ftp_pass' );
	$wpdbbkp_subdir = get_option( 'backupbreeze_ftp_subdir' );
	if ( '' === $wpdbbkp_subdir ) {
		$wpdbbkp_subdir = '/';
	}

	$wpdbbkp_conn = false;
	if ( is_admin() ) {
		// If user has WP manage options permissions.
		if ( current_user_can( 'manage_options' ) ) {
			// Connect to host ONLY if the 2 security conditions are valid / met.
			$wpdbbkp_conn = ftp_connect( $wpdbbkp_host );
		}
	}

	if ( ! $wpdbbkp_conn ) {
		$wpdbbkp_trouble = esc_html__( 'I could not connect to your FTP server.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check your FTP Host and try again.', 'wpdbbkp' );
		return $wpdbbkp_trouble;
	}

	$wpdbbkp_result = ftp_login( $wpdbbkp_conn, $wpdbbkp_user, $wpdbbkp_pass );
	if ( ! $wpdbbkp_result ) {
		$wpdbbkp_trouble = esc_html__( 'I could connect to the FTP server but I could not log in.', 'wpdbbkp' ) . '' . esc_html__( 'Please check your credentials and try again.', 'wpdbbkp' );
		return $wpdbbkp_trouble;
	}

	$wpdbbkp_success = ftp_chdir( $wpdbbkp_conn, $wpdbbkp_subdir );
	if ( ! $wpdbbkp_success ) {
		$wpdbbkp_trouble = esc_html__( 'I can connect to the FTP server, but I cannot change into the FTP subdirectory you specified.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Is the path correct? Does the directory exist? Is it writable?', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check using an FTP client like FileZilla.', 'wpdbbkp' );
		return $wpdbbkp_trouble;
	}

	$wpdbbkp_trouble = 'OK';

	// Lose this connection.
	ftp_close( $wpdbbkp_conn );
	return $wpdbbkp_trouble;

}
