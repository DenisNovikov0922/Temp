<?php
/**
 * Plugin Name: WooFood Gutenberg Blocks
 * Plugin URI: https://www.wpslash.com/plugin/foodmaster
 * Description: WooFood blocks for the Gutenberg editor.
 * Version: 2.1.8
 * Author: WPSlash
 * Author URI: https://www.wpslash.com
 * Text Domain:  woofood
 * WC requires at least: 3.6
 * WC tested up to: 4.0
 *
 * @package WooFood\Blocks
 */

defined( 'ABSPATH' ) || die();

define( 'WFB_VERSION', '2.1.8' );
define( 'WFB_PLUGIN_FILE', __FILE__ );
define( 'WFB_ABSPATH', dirname( WFB_PLUGIN_FILE ) . '/' );
require_once plugin_dir_path( __FILE__ ) . 'assets/php/update.php';
/**
 * Load up the assets if Gutenberg is active.
 */
function WFB_initialize() {
	require_once plugin_dir_path( __FILE__ ) . 'assets/php/class-WFB-block-library.php';
	//

	// Remove core hook in favor of our local feature plugin handler.
	/*remove_action( 'init', array( 'WC_Block_Library', 'init' ) );
	// Remove core hooks from pre-3.6 (in 3.6.2 all functions were moved to one hook on init).
	remove_action( 'init', array( 'WC_Block_Library', 'register_blocks' ) );
	remove_action( 'init', array( 'WC_Block_Library', 'register_assets' ) );
	remove_filter( 'block_categories', array( 'WC_Block_Library', 'add_block_category' ) );
	remove_action( 'admin_print_footer_scripts', array( 'WC_Block_Library', 'print_script_settings' ), 1 );*/

	$files_exist = file_exists( plugin_dir_path( __FILE__ ) . '/build/featured-product.js' );
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && ! $files_exist ) {
		add_action( 'admin_notices', 'WFB_plugins_notice' );
	}
}
add_action( 'woocommerce_loaded', 'WFB_initialize' );

/**
 * Display a warning about building files.
 */
function WFB_plugins_notice() {
	echo '<div class="error"><p>';
	printf(
		/* Translators: %1$s is the install command, %2$s is the build command, %3$s is the watch command. */
		esc_html__( 'WooCommerce Blocks development mode requires files to be built. From the plugin directory, run %1$s to install dependencies, %2$s to build the files or %3$s to build the files and watch for changes.', 'woo-gutenberg-products-block' ),
		'<code>npm install</code>',
		'<code>npm run build</code>',
		'<code>npm start</code>'
	);
	echo '</p></div>';
}
