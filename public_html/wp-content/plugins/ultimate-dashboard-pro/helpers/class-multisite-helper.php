<?php
/**
 * Multisite helper.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup multisite helper.
 */
class Multisite_Helper {
	/**
	 * Check whether plugin is active on multisite or not.
	 *
	 * @return bool
	 */
	public function is_network_active() {
		// Load plugin.php if it doesn't already exist.
		if ( ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return ( is_plugin_active_for_network( 'ultimate-dashboard-pro/ultimate-dashboard-pro.php' ) ? true : false );
	}

	/**
	 * Check whether multisite actions are supported or not.
	 *
	 * @return bool
	 */
	public function multisite_supported() {
		return ( $this->is_network_active() && apply_filters( 'udb_pro_ms_support', false ) ? true : false );
	}

	/**
	 * Check whether we need to switch blog.
	 *
	 * @return bool
	 */
	public function needs_to_switch_blog() {

		if ( ! $this->multisite_supported() ) {
			return false;
		}

		global $blueprint;

		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return false;
		}

		return true;

	}

	/**
	 * Construct array of excluded sites
	 *
	 * @return array The array of excluded sites.
	 */
	public function get_excluded_sites() {

		global $blueprint;

		$array = array();

		// Include blueprint site if it is defined.
		if ( ! empty( $blueprint ) ) {
			$array[] = $blueprint;
		}

		if ( get_site_option( 'udb_multisite_exclude' ) ) {
			$excluded_sites = get_site_option( 'udb_multisite_exclude' );
			$excluded_sites = str_replace( ' ', '', $excluded_sites );
			$excluded_sites = explode( ',', $excluded_sites );
		} else {
			$excluded_sites = array();
		}

		$excluded_sites = array_merge( $array, $excluded_sites );

		return $excluded_sites;

	}
}
