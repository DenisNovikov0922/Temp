<?php
/**
 * Backwards compatibility.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Widget_helper;
use Udb\Backwards_Compatibility as Free_Backwards_Compatibility;

use UdbPro\Helpers\Multisite_Helper;

/**
 * Class that handles backwards compatibility.
 */
class Backwards_Compatibility {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {
		$instance = new self();
		$instance->setup();
	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		add_action( 'udb_parse_widget_type', array( $this, 'parse_widget_type' ), 10, 2 );
		add_action( 'udb_replace_submeta_keys', array( $this, 'replace_submeta_keys' ) );
		add_action( 'udb_delete_old_options', array( $this, 'delete_old_options' ) );
		add_action( 'udb_meta_compatibility', array( $this, 'branding_compatibility' ) );

	}

	/**
	 * Add extra checking for "udb_compat_widget_type" filter of the free version.
	 *
	 * @param int $widget_type The determined widget type from Ultimate Dashboard free.
	 * @param int $post_id The post ID.
	 *
	 * @return string The widget type.
	 */
	public function parse_widget_type( $widget_type, $post_id ) {

		if ( get_post_meta( $post_id, 'udb_video_id', true ) ) {
			$widget_type = 'video';
		}

		return $widget_type;

	}

	/**
	 * Delete old options and move their value to $settings.
	 */
	public function delete_old_options() {

		$settings = get_option( 'udb_settings', array() );

		if ( ! $settings ) {
			update_option( 'udb_settings', array() );
		}

		// 3rd party widgets.
		if ( get_option( 'elementor' ) ) {
			$settings['e-dashboard-overview'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'elementor' );
		}

		if ( get_option( 'yoast' ) ) {
			$settings['wpseo-dashboard-overview'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'yoast' );
		}

		if ( get_option( 'edd' ) ) {
			$settings['edd_dashboard_sales'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'edd' );
		}

		if ( get_option( 'gf' ) ) {
			$settings['rg_forms_dashboard'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'gf' );
		}

		if ( get_option( 'wpsal' ) ) {
			$settings['wsal'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'wpsal' );
		}

		if ( get_option( 'backupbuddy' ) ) {
			$settings['pb_backupbuddy_stats'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'backupbuddy' );
		}

		if ( get_option( 'prettylink' ) ) {
			$settings['prli_dashboard_widget'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'prettylink' );
		}

		if ( get_option( 'wooreviews' ) ) {
			$settings['woocommerce_dashboard_recent_reviews'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'wooreviews' );
		}

		if ( get_option( 'woostatus' ) ) {
			$settings['woocommerce_dashboard_status'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'woostatus' );
		}

		if ( get_option( 'give' ) ) {
			$settings['give_dashboard_sales'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'give' );
		}

		if ( get_option( 'moderntribe' ) ) {
			$settings['tribe_dashboard_widget'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'moderntribe' );
		}

		if ( get_option( 'itsecpro' ) ) {
			$settings['itsec-dashboard-widget'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'itsecpro' );
		}

		if ( get_option( 'csshero' ) ) {
			$settings['widget_cssheronews'] = 1;
			update_option( 'udb_settings', $settings );
			delete_option( 'csshero' );
		}

		// Widget order.
		if ( get_option( 'udb_extras_widget_order' ) || get_option( 'udb_extras_widget_order' ) === '0' ) {
			$settings['widget_order'] = get_option( 'udb_extras_widget_order' );

			update_option( 'udb_settings', $settings );
			delete_option( 'udb_extras_widget_order' );
		}

		/**
		 * Un-used multisite setting.
		 * Delete it if exists.
		 */
		if ( get_site_option( 'udb_multisite_overwrite' ) || get_site_option( 'udb_multisite_overwrite' ) === '0' ) {
			delete_site_option( 'udb_multisite_overwrite' );
		}

	}

	/**
	 * Replace some submeta keys from an option meta to other option meta.
	 * This function is hooked to "udb_replace_submeta_keys" hook from Ultimate Dashboard free.
	 */
	public function replace_submeta_keys() {

		$setting_opts  = get_option( 'udb_settings', array() );
		$pro_opts      = get_option( 'udb_pro_settings', array() );
		$branding_opts = get_option( 'udb_branding', array() );

		$update_setting_opts  = false;
		$update_branding_opts = false;

		$widget_helper  = new Widget_Helper();
		$plugin_widgets = $widget_helper->get_3rd_party();
		$plugin_widgets = $plugin_widgets ? $plugin_widgets : array();

		foreach ( $plugin_widgets as $id => $widget ) {

			// 3rd party widgets.
			if ( isset( $pro_opts[ $id ] ) ) {
				$setting_opts[ $id ] = $pro_opts[ $id ];
				$update_setting_opts = true;

				unset( $pro_opts[ $id ] );
			}
		}

		// Dashboard's page builder template.
		if ( isset( $pro_opts['page_builder_template'] ) ) {
			$setting_opts['page_builder_template'] = $pro_opts['page_builder_template'];
			$update_setting_opts                   = true;

			unset( $pro_opts['page_builder_template'] );
		}

		// Dashboard's columns.
		if ( isset( $pro_opts['dashboard_columns'] ) ) {
			$setting_opts['dashboard_columns'] = $pro_opts['dashboard_columns'];
			$update_setting_opts               = true;

			unset( $pro_opts['dashboard_columns'] );
		}

		// Widget's order.
		if ( isset( $pro_opts['widget_order'] ) ) {
			$setting_opts['widget_order'] = $pro_opts['widget_order'];
			$update_setting_opts          = true;

			unset( $pro_opts['widget_order'] );
		}

		// Widget's icon color.
		if ( isset( $branding_opts['icon_color'] ) ) {
			$setting_opts['icon_color'] = $branding_opts['icon_color'];
			$update_setting_opts        = true;
			$update_branding_opts       = true;

			unset( $branding_opts['icon_color'] );
		}

		// Widget's headline color.
		if ( isset( $branding_opts['headline_color'] ) ) {
			$setting_opts['headline_color'] = $branding_opts['headline_color'];
			$update_setting_opts            = true;
			$update_branding_opts           = true;

			unset( $branding_opts['headline_color'] );
		}

		// Update the settings meta if necessary.
		if ( $update_setting_opts ) {
			update_option( 'udb_settings', $setting_opts );
		}

		// Update the branding meta if necessary.
		if ( $update_branding_opts ) {
			update_option( 'udb_branding', $branding_opts );
		}

	}

	/**
	 * Branding meta compatibility.
	 */
	public function branding_compatibility() {

		$ms_helper = new Multisite_Helper();

		if ( $ms_helper->needs_to_switch_blog() ) {
			global $blueprint;

			$backwards_compat = Free_Backwards_Compatibility::get_instance();

			switch_to_blog( $blueprint );
			$this->rename_branding_submeta_keys();
			$backwards_compat->replace_submeta_keys();
			restore_current_blog();
		}

		$this->rename_branding_submeta_keys();

	}

	/**
	 * Change old "udb_branding[udb_branding_*]" meta key to "udb_branding[*]"
	 */
	public function rename_branding_submeta_keys() {

		// Make sure we don't check again.
		if ( get_option( 'udb_compat_branding_meta' ) ) {
			return;
		}

		$branding_opts = get_option( 'udb_branding', array() );
		$login_opts    = get_option( 'udb_login', array() );

		$update_branding_opts = false;
		$update_login_opts    = false;

		// Login logo image.
		if ( isset( $branding_opts['udb_branding_login_logo'] ) ) {
			$login_opts['logo_image'] = $branding_opts['udb_branding_login_logo'];
			$update_branding_opts     = true;
			$update_login_opts        = true;

			unset( $branding_opts['udb_branding_login_logo'] );
		}

		// Login logo url.
		if ( isset( $branding_opts['login_logo_url'] ) ) {
			$login_opts['logo_url'] = $branding_opts['login_logo_url'];
			$update_branding_opts   = true;
			$update_login_opts      = true;

			unset( $branding_opts['login_logo_url'] );
		}

		// Branding enabled.
		if ( isset( $branding_opts['udb_branding_activate'] ) ) {
			$branding_opts['enabled'] = $branding_opts['udb_branding_activate'];
			$update_branding_opts     = true;

			unset( $branding_opts['udb_branding_activate'] );
		}

		// Branding accent color.
		if ( isset( $branding_opts['udb_branding_accent_color'] ) ) {
			$branding_opts['accent_color'] = $branding_opts['udb_branding_accent_color'];
			$update_branding_opts          = true;

			unset( $branding_opts['udb_branding_accent_color'] );
		}

		// Branding icon color.
		if ( isset( $branding_opts['udb_branding_icon_color'] ) ) {
			$branding_opts['icon_color'] = $branding_opts['udb_branding_icon_color'];
			$update_branding_opts        = true;

			unset( $branding_opts['udb_branding_icon_color'] );
		}

		// Branding headline color.
		if ( isset( $branding_opts['udb_branding_headline_color'] ) ) {
			$branding_opts['headline_color'] = $branding_opts['udb_branding_headline_color'];
			$update_branding_opts            = true;

			unset( $branding_opts['udb_branding_headline_color'] );
		}

		// Branding admin bar logo image.
		if ( isset( $branding_opts['udb_branding_admin_bar_logo'] ) ) {
			$branding_opts['admin_bar_logo_image'] = $branding_opts['udb_branding_admin_bar_logo'];
			$update_branding_opts                  = true;

			unset( $branding_opts['udb_branding_admin_bar_logo'] );
		}

		// Branding admin bar logo url.
		if ( isset( $branding_opts['udb_branding_admin_bar_logo_url'] ) ) {
			$branding_opts['admin_bar_logo_url'] = $branding_opts['udb_branding_admin_bar_logo_url'];
			$update_branding_opts                = true;

			unset( $branding_opts['udb_branding_admin_bar_logo_url'] );
		}

		// Branding remove admin bar logo.
		if ( isset( $branding_opts['udb_branding_remove_wp_admin_bar_logo'] ) ) {
			$branding_opts['remove_admin_bar_logo'] = $branding_opts['udb_branding_remove_wp_admin_bar_logo'];
			$update_branding_opts                   = true;

			unset( $branding_opts['udb_branding_remove_wp_admin_bar_logo'] );
		}

		// Branding footer text.
		if ( isset( $branding_opts['udb_branding_footer_text'] ) ) {
			$branding_opts['footer_text'] = $branding_opts['udb_branding_footer_text'];
			$update_branding_opts         = true;

			unset( $branding_opts['udb_branding_footer_text'] );
		}

		// Branding version text.
		if ( isset( $branding_opts['udb_branding_version_text'] ) ) {
			$branding_opts['version_text'] = $branding_opts['udb_branding_version_text'];
			$update_branding_opts          = true;

			unset( $branding_opts['udb_branding_version_text'] );
		}

		// Update the branding meta if necessary.
		if ( $update_branding_opts ) {
			update_option( 'udb_branding', $branding_opts );
		}

		// Update the login meta if necessary.
		if ( $update_login_opts ) {
			update_option( 'udb_login', $login_opts );
		}

		// Make sure we don't check again.
		update_option( 'udb_compat_branding_meta', 1 );

	}

}
