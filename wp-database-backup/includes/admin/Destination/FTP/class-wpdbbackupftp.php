<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
/**
 * Backup Filters
 *
 * @package wpdbbkp
 */

add_action( 'wpdbbkp_db_backup_completed', array( 'WPDBBackupFTP', 'wp_db_backup_completed' ) );

/**
 * WPDBBackupFTP Class.
 *
 * @class WPDBBackupFTP
 */
class WPDBBackupFTP {

	/**
	 * Run after complete backup.
	 *
	 * @param array $args - backup details.
	 */
	public static function wp_db_backup_completed( &$wpdbbkp_args ) {
		$destination_ftp = get_option( 'wp_db_backup_destination_FTP' );
		if ( isset( $destination_ftp ) && 1 === (int) $destination_ftp ) {
			update_option('wpdbbkp_backupcron_current','Processing FTP Backup', false);
			include plugin_dir_path( __FILE__ ) . 'preflight.php';
			$wpdbbkp_filename = $wpdbbkp_args[0];
			include plugin_dir_path( __FILE__ ) . 'sendaway.php';
		}
	}
}
