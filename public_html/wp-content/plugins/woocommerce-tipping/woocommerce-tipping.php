<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              https://www.wpslash.com
* @since             1.0.4
* @package           WPSlash_Tipping
*
* @wordpress-plugin
* Plugin Name:       Tipping for WooCommerce
* Plugin URI:        wpslash-tipping
* Description:       Adds the ability for customers to add a percentage or their own tip on checkout 
* Version:           1.0.4
* Author:            WPSlash
* Author URI:        https://www.wpslash.com
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       'wpslash-tipping'
* Domain Path:       /languages
* WC requires at least: 3.4
* WC tested up to: 4.7.1
* Woo: 6511571:20700e5820bff44190a3dd4ec0406639
* Copyright: © 2009-2020 WooCommerce.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('WPSAUSC_DIR', plugin_dir_path( __FILE__ ) );
define('WPSTIP_FILE', dirname( __FILE__ ) );
define('WPSTIP_DIR', plugin_dir_path( __FILE__ ) );

define('WPSTIP_DIR_URL', plugin_dir_url(__FILE__) );

add_action('plugins_loaded', 'WPSlash_Tipping_load_textdomain');
function WPSlash_Tipping_load_textdomain() {
	load_plugin_textdomain( 'wpslash-tipping', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	require_once __DIR__ . '/inc/reports.php';
	new WPSlash_Tipping_Reports();

}

//check if WooCommerce is activated
if ( 
	in_array( 
		'woocommerce/woocommerce.php', 
		apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
		) 
	) {



	add_action( 'woocommerce_settings_tabs', 'wpslash_tipping_add_settings_tab' );
	function wpslash_tipping_add_settings_tab() {
		$current_tab = ( isset($_GET['tab']) == 'wpslash_tipping' ) ? 'nav-tab-active' : '';
		echo '<a href="admin.php?page=wc-settings&amp;tab=wpslash_tipping" class="nav-tab ' . esc_html($current_tab) . '">' . esc_html__( 'Tipping', 'wpslash-tipping' ) . '</a>';
	}



	add_action( 'woocommerce_settings_wpslash_tipping', 'wpslash_tipping_tab_content' );
	function wpslash_tipping_tab_content() { 

		woocommerce_admin_fields( wpslash_tipping_get_settings() );

	}


	function wpslash_tipping_enqueue_styles() {
 
		wp_enqueue_style('wpslash-tipping-css', WPSTIP_DIR_URL . '/css/main.css', array(), '0.1.0', 'all');
		wp_enqueue_script('wpslash-tipping-js', WPSTIP_DIR_URL . '/js/main.js', array('jquery'), '0.1.0');
		wp_localize_script( 'wpslash-tipping-js', 'wpslash_tipping_obj',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce('wpslash_tip_security')

		)
		);
	}
	add_action( 'wp_enqueue_scripts', 'wpslash_tipping_enqueue_styles' );



	function wpslash_tipping_get_settings() {
		$settings = array(
		'section_title' => array(
			'name'     => __( 'Impostazioni Mancia', 'wpslash-tipping' ),
			'type'     => 'title',
			'desc'     => '',
			'id'       => 'wc_settings_tab_WPSlash_Tipping_section_title_acs'
			),

		'tipping_title' => array(
			'name' => __( 'Titolo', 'wpslash-tipping' ),
			'type' => 'text',
			'desc' => __( 'Titolo sopra al modulo mancia nel checkout', 'wpslash-tipping' ),
			'id'   => 'wc_settings_tab_wpslash_tipping_title'
			),

		'tipping_amount_default' => array(
			'name' => __( 'Mancia di default', 'wpslash-tipping' ),
			'type' => 'text',
			'desc' => __( 'Il valore di default di mancia nel modulo checkout', 'wpslash-tipping' ),
			'id'   => 'wc_settings_tab_wpslash_tipping_default_amount'
			),

		'tipping_title_enabled' => array(
			'name' => __( 'Abilita Titolo modulo mancia', 'wpslash-tipping' ),
			'type' => 'checkbox',
			'desc' => '',
			'id'   => 'wc_settings_tab_wpslash_tipping_title_enabled',
			'default'  => 'no'
			),
		'tipping_taxable' => array(
			'name' => __( 'IVA applicabile?', 'wpslash-tipping' ),
			'type' => 'checkbox',
			'desc' => '',
			'id'   => 'wc_settings_tab_wpslash_tipping_taxable',
			'default'  => 'no'

			),
		'tipping_tax_class' => array(
			'name' => esc_html__('Tasse', 'wpslash-tipping'),
			'type' => 'select',
			'desc' => esc_html__('Scegli la classe di tassa se hai impostato IVA applicabile', 'wpslash-tipping'),
			'id' => 'wc_settings_tab_wpslash_tipping_tax_class',
			'options' => wc_get_product_tax_class_options(),
			),


			'tipping_percentage_enabled' => array(
			'name' => __( 'Abilita pulsanti percentuale mancia', 'wpslash-tipping' ),
			'type' => 'checkbox',
			'desc' => esc_html__('Will enable percentage buttons to be added based on the below percentage options', 'wpslash-tipping'),
			'id'   => 'wc_settings_tab_wpslash_tipping_percentage_enabled'
			),
			'tipping_percentage' => array(
			'name' => __( 'Percentages(Comma Sepatated)', 'wpslash-tipping' ),
			'type' => 'text',
			'desc' => __( 'Comma seperated tipping options like 10,20,30', 'wpslash-tipping' ),
			'id'   => 'wc_settings_tab_wpslash_tipping_percentage'
			),

			'tipping_percentage_display' => array(
			'name' => esc_html__('Percentage Display Option', 'wpslash-tipping'),
			'type' => 'select',
			'desc' => esc_html__('Leave it empty for all shipping methods or select the  methods you want. This setting applies only to  auto-generated vouchers. You will still be able to generate a voucher manually even if the shipping method is not in the list.', 'wpslash-tipping'),
			'id' => 'wc_settings_tab_wpslash_tipping_percentage_display',
			'options' => array(
				'percentage' => esc_html__('Add 20% Tip (Percentage Display)', 'wpslash-tipping'),
				'amount' => esc_html__('Add $20 Tip (Tip Amount)', 'wpslash-tipping'),
				'percentage-amount' => esc_html__('Add 20% ($7.5) Tip  (Tip Amount)', 'wpslash-tipping')

			),
			),

		'tipping_enabled' => array(
			'name' => __( 'Enable Tipping Feature', 'wpslash-tipping' ),
			'type' => 'checkbox',
			'desc' => '',
			'id'   => 'wc_settings_tab_wpslash_tipping_enabled',
			'default'  => 'no'
			),
		'section_end' => array(
			'type' => 'sectionend',
			'id' => 'wc_settings_tab_WPSlash_Tipping_section_end'
			)
		);
		return apply_filters( 'wc_settings_tab_WPSlash_Tipping_settings', $settings );
	}



	add_action('woocommerce_settings_save_wpslash_tipping', 'save_wpslash_tipping_settings');

	function save_wpslash_tipping_settings() {

		woocommerce_update_options( wpslash_tipping_get_settings() );

	}






	require_once __DIR__ . '/inc/activation.hook.php';
	require_once __DIR__ . '/inc/calc.hook.php';
	require_once __DIR__ . '/inc/checkout.hook.php';
	require_once __DIR__ . '/inc/ajax.hook.php';









}//end check if WooCommerce is activated


