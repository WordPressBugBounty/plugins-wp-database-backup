<?php
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
 * @param string $trouble - Trouble response.
 */

require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

function wpdbbkp_preflight_problem( $trouble ) {
	$error_log = $trouble;
}
$wpdbbkp_sftp_details = get_option( 'wp_db_backup_sftp_details', array() );

$wpdbbkp_host      = isset( $wpdbbkp_sftp_details['host'] ) ? $wpdbbkp_sftp_details['host'] : '';
$wpdbbkp_port      = isset( $wpdbbkp_sftp_details['port'] ) ? $wpdbbkp_sftp_details['port'] : 22;
$wpdbbkp_user      = isset( $wpdbbkp_sftp_details['username'] ) ? $wpdbbkp_sftp_details['username'] : '';
$wpdbbkp_pass      = isset( $wpdbbkp_sftp_details['password'] ) ? $wpdbbkp_sftp_details['password'] : '';
$wpdbbkp_pkey      = isset( $wpdbbkp_sftp_details['sftp_key'] ) ? base64_decode( $wpdbbkp_sftp_details['sftp_key'] ) : '';
$wpdbbkp_key_pass  = isset( $wpdbbkp_sftp_details['key_password'] ) ? $wpdbbkp_sftp_details['key_password'] : false;
$wpdbbkp_directory = isset( $wpdbbkp_sftp_details['directory'] ) ? $wpdbbkp_sftp_details['directory'] : '';
$wpdbbkp_auth_type = isset( $wpdbbkp_sftp_details['auth_type'] ) ? $wpdbbkp_sftp_details['auth_type'] : 'password';
$wpdbbkp_sftp      = false;
if ( '' === $wpdbbkp_directory ) {
	$wpdbbkp_directory = '/';
}
if ( $wpdbbkp_host ) {
	// If in WP Dashboard or Admin Panels.
	if ( is_admin() ) {
		// If user has WP manage options permissions.
		if ( current_user_can( 'manage_options' ) ) {
			// Connect to host ONLY if the 2 security conditions are valid / met.
			$wpdbbkp_sftp = new SFTP( $wpdbbkp_host, $wpdbbkp_port );
			if ( ! $wpdbbkp_sftp ) {
				return esc_html__( 'Could not connect to your SFTP server.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check your SFTP Host settings and try again (leave FTP Host BLANK for local backups).', 'wpdbbkp' );
			}
			if ( 'key' === $wpdbbkp_auth_type ) {
				$wpdbbkp_key    = PublicKeyLoader::load( $wpdbbkp_pkey, $wpdbbkp_key_pass );
				$wpdbbkp_result = $wpdbbkp_sftp->login( $wpdbbkp_user, $wpdbbkp_key );
			} else {
				$wpdbbkp_result = $wpdbbkp_sftp->login( $wpdbbkp_user, $wpdbbkp_pass );
			}
			if ( ! $wpdbbkp_result ) {
				return esc_html__( 'Could not log in to your FTP server.', 'wpdbbkp' ) . '<br />' . esc_html__( 'Please check your SFTP Username and Password, then try again.', 'wpdbbkp' ) . '<br />' . esc_html__( 'For local backups, please leave the FTP Host option BLANK.', 'wpdbbkp' );
			}
		}
	}
}
