<?php
/**
 * Network settings template.
 *
 * @package Ultimate_Dashboard_PRO
 *
 * @subpackage Multisite
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

?>

<div class="wrap settingstuff">

	<h1><?php echo esc_html( get_admin_page_title() ); ?> <?php _e( 'Network Settings', 'ultimatedashboard' ); ?></h1>

	<?php settings_errors(); ?>

	<?php if ( isset( $_POST['submit'] ) ) { ?>

		<div class="notice notice-success">
			<p><?php _e( 'Settings Saved', 'ultimatedashboard' ); ?></p>
		</div>

		<?php

		// Defaults.
		if ( ! isset( $_POST['udb_multisite_blueprint'] ) ) {
			$_POST['udb_multisite_blueprint'] = 1;
		}

		if ( ! isset( $_POST['udb_multisite_exclude'] ) ) {
			$_POST['udb_multisite_exclude'] = false;
		}

		if ( ! isset( $_POST['udb_multisite_widget_order'] ) ) {
			$_POST['udb_multisite_widget_order'] = 0;
		}

		if ( ! isset( $_POST['udb_multisite_capability'] ) ) {
			$_POST['udb_multisite_capability'] = 'manage_network';
		}

		$multisite_blueprint    = absint( $_POST['udb_multisite_blueprint'] );
		$multisite_exclude      = sanitize_text_field( $_POST['udb_multisite_exclude'] );
		$multisite_widget_order = absint( $_POST['udb_multisite_widget_order'] );
		$multisite_capability   = sanitize_text_field( $_POST['udb_multisite_capability'] );

		update_site_option( 'udb_multisite_blueprint', $multisite_blueprint );
		update_site_option( 'udb_multisite_exclude', $multisite_exclude );
		update_site_option( 'udb_multisite_widget_order', $multisite_widget_order );
		update_site_option( 'udb_multisite_capability', $multisite_capability );

	}

	?>

	<form method="post" action="">
		<?php
		settings_fields( 'udb-multisite-settings-group' );
		do_settings_sections( 'ultimate-dashboard-multisite' );
		submit_button();
		?>
	</form>

</div>
