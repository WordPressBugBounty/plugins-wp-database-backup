<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Destination ftp
 *
 * @package wpdbbkp
 */

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

?>
<p><strong><?php esc_html_e( 'Here\'s a list of BackupBreeze in your repository:', 'wpdbbkp' ); ?> </strong></p>
<?php
/**
 * Set up variables
 *
 * @package wpdbbkp
 */
$wpdbbkp_host   = get_option( 'snapshot_ftp_host' );
$wpdbbkp_user   = get_option( 'snapshot_ftp_user' );
$wpdbbkp_pass   = get_option( 'snapshot_ftp_pass' );
$wpdbbkp_subdir = get_option( 'snapshot_ftp_subdir' );
if ( '' === $wpdbbkp_subdir ) {
	$wpdbbkp_subdir = '/';
}

// If in WP Dashboard or Admin Panels.
if ( is_admin() ) {
	// If user has WP manage options permissions.
	if ( current_user_can( 'manage_options' ) ) {
		$wpdbbkp_conn_id = ftp_connect( $wpdbbkp_host );
	}
}

// Login with username and password.
$wpdbbkp_login_result = ftp_login( $wpdbbkp_conn_id, $wpdbbkp_user, $wpdbbkp_pass );

// Get contents of the current directory.
$wpdbbkp_contents = ftp_nlist( $wpdbbkp_conn_id, "{$wpdbbkp_subdir}/*.tar" );

?>
<ol></em>

<?php
if ( ! empty( $wpdbbkp_contents ) && is_array( $wpdbbkp_contents ) ) {
	foreach ( $wpdbbkp_contents as $wpdbbkp_key => $wpdbbkp_value ) {
		echo '<li>' . esc_attr( substr( $wpdbbkp_value, strlen( $wpdbbkp_subdir ) ) ) . '</li>';
	}
}

?>
</ol>
<p><br />
<em><?php echo esc_html__( 'This section shows a list of Backup in your repository. ', 'wpdbbkp' ); ?></em></p>
<p><em><?php echo esc_html__( "If you're using the Auto-Delete option under Automation: ", 'wpdbbkp' ); ?> <br />
</em><em><?php echo esc_html__( 'the files at the bottom of this list will be deleted, the ones at the top will stay in place. ', 'wpdbbkp' ); ?></em>
<?php
	ftp_close( $wpdbbkp_conn_id );
?>
</p>
