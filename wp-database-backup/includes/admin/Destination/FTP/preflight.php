<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Destination file.
 *
 * @package wpdbbkp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Error checking.
 *
 * @param string $wpdbbkp_trouble - Trouble response.
 */
function wpdbbkp_preflight_problem( $wpdbbkp_trouble ) {
	$error_log = $wpdbbkp_trouble;
}

// set up variables.
$wpdbbkp_host   = get_option( 'backupbreeze_ftp_host' );
$wpdbbkp_user   = get_option( 'backupbreeze_ftp_user' );
$wpdbbkp_pass   = get_option( 'backupbreeze_ftp_pass' );
$wpdbbkp_subdir = get_option( 'backupbreeze_ftp_subdir' );
if ( '' === $wpdbbkp_subdir ) {
	$wpdbbkp_subdir = '/';
}

if ( $wpdbbkp_host ) {
	// If in WP Dashboard or Admin Panels.
	if ( is_admin() ) {
		// If user has WP manage options permissions.
		if ( current_user_can( 'manage_options' ) ) {
			// Connect to host ONLY if the 2 security conditions are valid / met.
			$wpdbbkp_conn = ftp_connect( $wpdbbkp_host );
			if ( ! $wpdbbkp_conn ) {
				$wpdbbkp_trouble = esc_html__( 'I could not connect to your FTP server..', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check your FTP Host settings and try again (leave FTP Host BLANK for local backups).', 'wpdbbkp' );
				wpdbbkp_preflight_problem( $wpdbbkp_trouble );
			}
			$wpdbbkp_result = ftp_login( $wpdbbkp_conn, $wpdbbkp_user, $wpdbbkp_pass );
			if ( ! $wpdbbkp_result ) {
				$wpdbbkp_trouble = esc_html__( 'I could not log in to your FTP server.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check your FTP Username and Password, then try again.', 'wpdbbkp' ) . '<br />' . esc_html__( 'For local backups, please leave the FTP Host option BLANK.', 'wpdbbkp' );
				wpdbbkp_preflight_problem( $wpdbbkp_trouble );
			}
			$wpdbbkp_success = ftp_chdir( $wpdbbkp_conn, $wpdbbkp_subdir );
			if ( ! $wpdbbkp_success ) {
				$wpdbbkp_trouble = esc_html__( 'I cannot change into the FTP subdirectory you specified. Does it exist?', 'wpdbbkp' ) . '<br />' . esc_html__( 'You must create it first using an FTP client like FileZilla.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check and try again.', 'wpdbbkp' );
				wpdbbkp_preflight_problem( $wpdbbkp_trouble );
			}
		}
	}
}
