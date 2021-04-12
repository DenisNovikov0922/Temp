<?php
/**
 * Plugin Name: Ultimate Dashboard PRO
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Ultimate Dashboard gives you full control over your WordPress Dashboard. Remove the default Dashboard Widgets and and create your own for a better user experience.
 * Version: 3.2
 * Author: David Vongries
 * Author URI: https://mapsteps.com/
 * Text Domain: ultimatedashboard
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Constants.
define( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION', '3.2' );
define( 'ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE', 'udb_widgets&page=udb-license' );
define( 'ULTIMATE_DASHBOARD_PRO_STORE_URL', 'https://wp-pagebuilderframework.com' );
define( 'ULTIMATE_DASHBOARD_PRO_PRODUCT_NAME', 'Ultimate Dashboard PRO' );
define( 'ULTIMATE_DASHBOARD_PRO_ITEM_ID', 72376 );

// Load plugin updater if it doesn't exist.
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include dirname( __FILE__ ) . '/assets/edd/EDD_SL_Plugin_Updater.php';
}

/**
 * Plugin updater.
 */
function udb_pro_plugin_updater() {

	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}

	// Retrieve our license key from the DB.
	$license_key = trim( get_option( 'ultimate_dashboard_license_key' ) );

	// Setup the updater.
	$edd_updater = new EDD_SL_Plugin_Updater(
		ULTIMATE_DASHBOARD_PRO_STORE_URL,
		__FILE__,
		array(
			'version' => ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION,
			'license' => $license_key,
			'item_id' => ULTIMATE_DASHBOARD_PRO_ITEM_ID,
			'author'  => 'David Vongries',
			'beta'    => false,
		)
	);

}
add_action( 'init', 'udb_pro_plugin_updater' );

require __DIR__ . '/class-setup.php';
require __DIR__ . '/class-backwards-compatibility.php';

UdbPro\Setup::init();
