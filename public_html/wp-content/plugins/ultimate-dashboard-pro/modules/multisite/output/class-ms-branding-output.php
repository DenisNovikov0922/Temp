<?php
/**
 * Multisite's branding output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use UdbPro\Helpers\Branding_Helper;
use Udb\Branding\Branding_Output as Free_Branding_Output;

use UdbPro\Branding\Branding_Output;

/**
 * Class to setup the module output.
 */
class Ms_Branding_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/multisite';

	}

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

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup the module output.
	 */
	public function setup() {

		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ), 90 );
		add_action( 'wp_enqueue_scripts', array( self::get_instance(), 'frontend_styles' ), 90 );
		add_action( 'admin_bar_menu', array( self::get_instance(), 'replace_admin_bar_logo' ), 11 );
		add_action( 'admin_bar_menu', array( self::get_instance(), 'remove_admin_bar_logo' ), 99 );
		add_filter( 'admin_footer_text', array( self::get_instance(), 'footer_text' ) );
		add_filter( 'update_footer', array( self::get_instance(), 'version_text' ), 11 );
		add_action( 'adminmenu', array( self::get_instance(), 'modern_admin_bar_logo' ) );

	}

	/**
	 * Branding admin styles.
	 */
	public function admin_styles() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$branding_helper = new Branding_Helper();

		// Stop here if branding is enabled for the current blog.
		if ( $branding_helper->is_enabled() ) {
			return;
		}

		$branding_output = Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$branding_output->admin_styles();
		$branding_output->dashboard_styles();
		restore_current_blog();

	}

	/**
	 * Frontend styles.
	 */
	public function frontend_styles() {

		global $blueprint;

		$branding_helper = new Branding_Helper();

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		// Stop here if branding is enabled for the current blog.
		if ( $branding_helper->is_enabled() ) {
			return;
		}

		$branding_output = Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$branding_output->frontend_styles();
		restore_current_blog();

	}

	/**
	 * Replace admin bar logo.
	 *
	 * @param object $wp_admin_bar WP admin bar.
	 */
	public function replace_admin_bar_logo( $wp_admin_bar ) {

		global $blueprint;

		$branding_helper = new Branding_Helper();

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		// Stop here if branding is enabled for the current blog.
		if ( $branding_helper->is_enabled() ) {
			return;
		}

		$branding_output = Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$branding_output->replace_admin_bar_logo( $wp_admin_bar );
		restore_current_blog();

	}

	/**
	 * Remove admin bar logo.
	 *
	 * @param object $wp_admin_bar WP admin bar.
	 */
	public function remove_admin_bar_logo( $wp_admin_bar ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$branding_output = Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$branding_output->remove_admin_bar_logo( $wp_admin_bar );
		restore_current_blog();

	}

	/**
	 * Footer text.
	 *
	 * @param string $footer_text The footer text.
	 *
	 * @return string The updated footer text.
	 */
	public function footer_text( $footer_text ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $footer_text;
		}

		// Get branding option from current blog.
		$branding = get_option( 'udb_branding' );

		// Stop here if footer text is defined for the current blog.
		if ( ! empty( $branding['footer_text'] ) ) {
			return $footer_text;
		}

		$branding_output = Free_Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$footer_text = $branding_output->footer_text( $footer_text );
		restore_current_blog();

		return $footer_text;

	}

	/**
	 * Version text.
	 *
	 * @param string $version_text The version text.
	 *
	 * @return string The updated version text.
	 */
	public function version_text( $version_text ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $version_text;
		}

		// Get branding option from current blog.
		$branding = get_option( 'udb_branding' );

		// Stop here if version text is defined for the current blog.
		if ( ! empty( $branding['version_text'] ) ) {
			return $version_text;
		}

		$branding_output = Free_Branding_Output::get_instance();

		switch_to_blog( $blueprint );
		$version_text = $branding_output->version_text( $version_text );
		restore_current_blog();

		return $version_text;

	}

	/**
	 * Modern layout: custom admin bar logo.
	 */
	public function modern_admin_bar_logo() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$branding = get_option( 'udb_branding' );

		// Stop here if branding is enabled on the subsite.
		if ( isset( $branding['enabled'] ) ) {
			return;
		}

		switch_to_blog( $blueprint );
		$branding = get_option( 'udb_branding' );
		restore_current_blog();

		// Stop here if modern layout is not selected.
		if ( ! isset( $branding['layout'] ) || 'modern' !== $branding['layout'] ) {
			return;
		}

		// If no logo is selected, use default.
		if ( ! empty( $branding['admin_bar_logo_image'] ) ) {
			$logo = $branding['admin_bar_logo_image'];
		} else {
			$logo = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/branding/assets/images/ultimate-dashboard-logo.png';
		}

		// If no logo url was set, use default.
		if ( ! empty( $branding['admin_bar_logo_url'] ) ) {
			$url = $branding['admin_bar_logo_url'];
		} else {
			$url = network_site_url();
		}

		// Let's add a filter, in case someone wants to dynamically change the logo.
		$logo = apply_filters( 'udb_admin_bar_logo_image', $logo );

		echo '<li id="udb-admin-logo-wrapper">';
		echo '<a href="' . esc_url( $url ) . '">';
		echo '<img class="udb-admin-logo" src="' . esc_url( $logo ) . '" />';
		echo '</a>';
		echo '</li>';

	}

}
