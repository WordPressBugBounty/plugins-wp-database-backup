<?php
/**
 * Destination dropboxs
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

// Variables for the field and option names.
$wpdbbkp_opt_name  = 'backupbreeze_ftp_host';
$wpdbbkp_opt_name2 = 'backupbreeze_ftp_user';
$wpdbbkp_opt_name3 = 'backupbreeze_ftp_pass';
$wpdbbkp_opt_name4 = 'backupbreeze_ftp_subdir';
$wpdbbkp_opt_name5 = 'backupbreeze_ftp_prefix';
$wpdbbkp_opt_name6 = 'backupbreeze_add_dir1';
$wpdbbkp_opt_name7 = 'backupbreeze_auto_interval';
$wpdbbkp_opt_name8 = 'backupbreeze_auto_email';
$wpdbbkp_opt_name9 = 'backupbreeze_ftp_port';

$wpdbbkp_hidden_field_name  = 'backupbreeze_ftp_hidden';
$wpdbbkp_hidden_field_name2 = 'backupbreeze_backup_hidden';
$wpdbbkp_hidden_field_name3 = 'backupbreeze_check_repo';
$wpdbbkp_data_field_name    = 'backupbreeze_ftp_host';
$wpdbbkp_data_field_name2   = 'backupbreeze_ftp_user';
$wpdbbkp_data_field_name3   = 'backupbreeze_ftp_pass';
$wpdbbkp_data_field_name4   = 'backupbreeze_ftp_subdir';
$wpdbbkp_data_field_name5   = 'backupbreeze_ftp_prefix';
$wpdbbkp_data_field_name6   = 'backupbreeze_add_dir1';
$wpdbbkp_data_field_name7   = 'backupbreeze_auto_interval';
$wpdbbkp_data_field_name8   = 'backupbreeze_auto_email';
$wpdbbkp_data_field_name9   = 'backupbreeze_ftp_port';

// Read in existing option value from database.
$wpdbbkp_opt_val                      = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name ) );
$wpdbbkp_opt_val2                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name2 ) );
$wpdbbkp_opt_val3                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name3 ) );
$wpdbbkp_opt_val4                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name4 ) );
$wpdbbkp_opt_val5                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name5 ) );
$wpdbbkp_opt_val6                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name6 ) );
$wpdbbkp_opt_val7                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name7 ) );
$wpdbbkp_opt_val8                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name8 ) );
$wpdbbkp_opt_val9                     = wpdbbkp_filter_data( get_option( $wpdbbkp_opt_name9 ) );
$wpdbbkp_destination_ftp = wpdbbkp_filter_data( get_option( 'wp_db_backup_destination_FTP' ) );

// If user pressed this button, this hidden field will be set to 'Y'.
if ( true === isset( $_POST[ $wpdbbkp_hidden_field_name3 ] ) && 'Y' === $_POST[ $wpdbbkp_hidden_field_name3 ] ) {
	// Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4.
	if ( ! isset( $_POST['wpdbbackup_update_setting'] ) ) {
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	if ( ! wp_verify_nonce( wp_unslash($_POST['wpdbbackup_update_setting'] ), 'wpdbbackup-update-setting' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- using as nonce
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	// Read their posted value.
	if ( true === isset( $_POST[ $wpdbbkp_data_field_name6 ] ) ) {
		$wpdbbkp_opt_val6 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name6 ] ) );
	}
	// Save the posted value in the database.
	if ( true === isset( $_POST[ $wpdbbkp_opt_val6 ] ) ) {
		update_option( $wpdbbkp_opt_name6, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val6 ) ) , false);
	}
	// Put a "settings updated" message on the screen.
	?>
	<div class="updated"><p><strong><?php echo esc_html__('Your additional directory has been saved.','wpdbbkp'); ?></strong></p></div>
	<?php
}

// If user pressed this button, this hidden field will be set to 'Y'.
if ( isset( $_POST[ $wpdbbkp_hidden_field_name ] ) && 'Y' === $_POST[ $wpdbbkp_hidden_field_name ] ) {
	// Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4.
	if ( ! isset( $_POST['wpdbbackup_update_setting'] ) ) {
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	if ( ! wp_verify_nonce(  wp_unslash( $_POST['wpdbbackup_update_setting'] ), 'wpdbbackup-update-setting' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- using as nonce
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	// Read their posted value.
	if ( isset( $_POST[ $wpdbbkp_data_field_name ] ) ) {
		$wpdbbkp_opt_val = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name ] ) );
	}
	if ( isset( $_POST[ $wpdbbkp_data_field_name2 ] ) ) {
		$wpdbbkp_opt_val2 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name2 ] ) );
	}
	if ( isset( $_POST[ $wpdbbkp_data_field_name3 ] ) ) {
		$wpdbbkp_opt_val3 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name3 ] ) );
	}
	if ( isset( $_POST[ $wpdbbkp_data_field_name4 ] ) ) {
		$wpdbbkp_opt_val4 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name4 ] ) );
	}
	if ( isset( $_POST[ $wpdbbkp_data_field_name5 ] ) ) {
		$wpdbbkp_opt_val5 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name5 ] ) );
	}
	if ( isset( $_POST[ $wpdbbkp_data_field_name9 ] ) ) {
		$wpdbbkp_opt_val9 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name9 ] ) );
	}

	// Save the posted value in the database.
	update_option( $wpdbbkp_opt_name, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val ) ) , false);
	update_option( $wpdbbkp_opt_name2, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val2 ) ) , false);
	update_option( $wpdbbkp_opt_name3, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val3 ) ), false );
	update_option( $wpdbbkp_opt_name4, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val4 ) ) , false);
	if ( isset( $_POST['wp_db_backup_destination_FTP'] ) ) {
		update_option( 'wp_db_backup_destination_FTP', 1 , false);
	} else {
		update_option( 'wp_db_backup_destination_FTP', 0, false );
	}
	$wpdbbkp_destination_ftp = wpdbbkp_filter_data( get_option( 'wp_db_backup_destination_FTP' ) );
	if ( isset( $_POST[ $wpdbbkp_data_field_name5 ] ) ) {
		update_option( $wpdbbkp_opt_name5, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val5 ) ) , false);
	}
	update_option( $wpdbbkp_opt_name9, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val9 ) ) , false);

	// Put a "settings updated" message on the screen.
	?>
	<div class="updated"><p><strong><?php esc_html_e( 'Your FTP details have been saved.', 'wpdbbkp'  ); ?></strong></p></div>
	<?php
} // end if.

// If user pressed this button, this hidden field will be set to 'Y'.
if ( isset( $_POST[ $wpdbbkp_hidden_field_name ] ) && 'Test Connection' === $_POST[ $wpdbbkp_hidden_field_name ] ) {
	// Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4.
	if ( ! isset( $_POST['wpdbbackup_update_setting'] ) ) {
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	if ( ! wp_verify_nonce( wp_unslash( $_POST['wpdbbackup_update_setting'] ) , 'wpdbbackup-update-setting' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- using as nonce
		wp_die( esc_html__('Invalid form data. form request came from the somewhere else not current site!','wpdbbkp') );
	}
	include plugin_dir_path( __FILE__ ) . 'test-ftp.php';
	// update all options while we're at it.
	$wpdbbkp_opt_val  = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name ] ) );
	$wpdbbkp_opt_val2 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name2 ] ) );
	$wpdbbkp_opt_val3 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name3 ] ) );
	$wpdbbkp_opt_val4 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name4 ] ) );
	if ( isset( $_POST[ $wpdbbkp_data_field_name5 ] ) ) {
		$wpdbbkp_opt_val5 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name5 ] ) );
	}
	$wpdbbkp_opt_val9 = sanitize_text_field( wp_unslash( $_POST[ $wpdbbkp_data_field_name9 ] ) );

	// Save the posted value in the database.
	update_option( $wpdbbkp_opt_name, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val ) ) , false);
	update_option( $wpdbbkp_opt_name2, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val2 ) ), false );
	update_option( $wpdbbkp_opt_name3, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val3 ) ) , false);
	update_option( $wpdbbkp_opt_name4, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val4 ) ) , false);
	if ( isset( $_POST[ $wpdbbkp_data_field_name5 ] ) ) {
		update_option( $wpdbbkp_opt_name5, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val5 ) ) , false);
	}
	update_option( $wpdbbkp_opt_name9, wpdbbkp_filter_data( sanitize_text_field( $wpdbbkp_opt_val9 ) ) , false);
	$wpdbbkp_result = wpdbbkp_test_ftp();

	if ( 'OK' !== $wpdbbkp_result ) {
		?>
		<div class="error"><p><strong><?php echo esc_html__('connection has failed!', 'wpdbbkp') ?><br /></strong></p>
			<?php echo esc_html( $wpdbbkp_result ) . '<br /><br />'; ?>
		</div>
	<?php } else { ?>

		<div class="updated"><p><strong><?php echo esc_html__('Connected to ', 'wpdbbkp') ?><?php echo esc_attr( $wpdbbkp_opt_val ); ?>, <?php echo esc_html__('for user', 'wpdbbkp') ?> <?php echo esc_attr( $wpdbbkp_opt_val2 ); ?></strong></p></div>
		<?php
	} // end if.
} // end if.
?>
<style>td, th {
		padding: 5px;
	}</style>
<p><?php echo esc_html__('Enter your FTP details for your offsite backup repository. Leave these blank for local backups or Disable FTP Destination.', 'wpdbbkp') ?></p>		
<form  class="form-group" name="form1" method="post" action="">
	<input type="hidden" name="<?php echo esc_attr( $wpdbbkp_hidden_field_name ); ?>" value="Y">
	<input name="wpdbbackup_update_setting" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'wpdbbackup-update-setting' ) ); ?>" />
<?php wp_nonce_field( 'wp-database-backup' ); ?>

	<div class="row form-group">
		<label class="col-sm-2" for="wp_db_backup_destination_FTP"><?php echo esc_html__('Enable FTP Destination', 'wpdbbkp') ?></label>
		<div class="col-sm-6">
			<input type="checkbox" id="wp_db_backup_destination_FTP" <?php echo ( isset( $wpdbbkp_destination_ftp ) && 1 === (int) $wpdbbkp_destination_ftp ) ? 'checked' : ''; ?> name="wp_db_backup_destination_FTP">
		</div>
	</div>

	<div class="row form-group">
		<label class="col-sm-2" for="FTP_host"><?php echo esc_html__('FTP Host', 'wpdbbkp') ?></label>
		<div class="col-sm-6">
			<input type="text" id="FTP_host" class="form-control" name="<?php echo esc_html( $wpdbbkp_data_field_name ); ?>" value="<?php echo esc_html( $wpdbbkp_opt_val ); ?>" size="25" placeholder="<?php esc_attr_e('e.g. ftp.yoursite.com','wpdbbkp');?>">
		</div>
	</div>

	<div class="row form-group">
		<label class="col-sm-2" for="FTP_port"><?php echo esc_html__('FTP Port', 'wpdbbkp') ?></label>
		<div class="col-sm-2">
			<input type="text" id="FTP_port" class="form-control" name="<?php echo esc_html( $wpdbbkp_data_field_name9 ); ?>" value="<?php echo esc_html( $wpdbbkp_opt_val9 ); ?>" size="4">
		</div>
		<div class="col-sm-4">
			<em><?php echo esc_html__('defaults to 21 if left blank', 'wpdbbkp') ?> </em>
		</div>
	</div>

	<div class="row form-group">
		<label class="col-sm-2" for="FTP_user"><?php echo esc_html__('FTP User', 'wpdbbkp') ?></label>
		<div class="col-sm-6">
			<input type="text" id="FTP_user" class="form-control" name="<?php echo esc_html( $wpdbbkp_data_field_name2 ); ?>" value="<?php echo esc_html( $wpdbbkp_opt_val2 ); ?>" size="25">
		</div>
	</div>

	<div class="row form-group">
		<label class="col-sm-2" for="FTP_password"><?php echo esc_html__('FTP Password', 'wpdbbkp') ?></label>
		<div class="col-sm-6">
			<input type="password" id="FTP_password" class="form-control" name="<?php echo esc_html( $wpdbbkp_data_field_name3 ); ?>" value="<?php echo esc_html( $wpdbbkp_opt_val3 ); ?>" size="25">
		</div>
	</div>

	<div class="row form-group">
		<label class="col-sm-2" for="FTP_dir"><?php echo esc_html__('Subdirectory', 'wpdbbkp') ?></label>
		<div class="col-sm-6">
			<input type="text" id="FTP_dir" placeholder="<?php esc_attr_e('e.g. /httpdocs/backups','wpdbbkp');?>" class="form-control" name="<?php echo esc_html( $wpdbbkp_data_field_name4 ); ?>" value="<?php echo esc_html( $wpdbbkp_opt_val4 ); ?>" size="25">
		</div>
		<div class="col-sm-4"> 
			<em><?php echo esc_html__('e.g. /httpdocs/backups or leave blank', 'wpdbbkp') ?></em> 
		</div>
	</div>

	<p><input type="submit" name="Submit" class="btn btn-primary" value="<?php esc_attr_e( 'Save' , 'wpdbbkp' ); ?>" />&nbsp;
		<input type="submit" name="<?php echo esc_html( $wpdbbkp_hidden_field_name ); ?>" class="btn btn-secondary" value="Test Connection" />

		<br />
	</p>
</form>
<hr />
<br />
