<?php
/*
Plugin Name:	Qyrr-Code
Plugin URI:		https://patrickposner.dev/plugins/qyrr
Description: 	QR-Code generation, management and tracking as it should be.
Author: 		Patrick Posner
Version:		0.6
Text Domain:    qyrr
Domain Path:    /languages
*/

define( 'QYRR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'QYRR_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/* load setup */
require_once( QYRR_PATH . '/inc/setup.php' );

/* localize */
$textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
load_plugin_textdomain( 'qyrr', false, $textdomain_dir );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', 'qyrr_run_plugin' );

/**
 * Run plugin
 *
 * @return void
 */
function qyrr_run_plugin() {
	qyrr\QYRR_Admin::get_instance();
	qyrr\QYRR_Meta::get_instance();
	qyrr\QYRR_Shortcode::get_instance();
}
