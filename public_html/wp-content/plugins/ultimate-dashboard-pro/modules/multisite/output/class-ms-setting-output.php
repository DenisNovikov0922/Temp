<?php
/**
 * Multisite's settings output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\Setting\Setting_Output;

/**
 * Class to setup the module output.
 */
class Ms_Setting_Output extends Base_Output {

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

		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'dashboard_custom_css' ), 199 );
		add_action( 'admin_head', array( self::get_instance(), 'admin_custom_css' ), 199 );
		add_action( 'admin_head', array( self::get_instance(), 'change_dashboard_headline' ), 199 );
		add_action( 'admin_head', array( self::get_instance(), 'remove_help_tab' ) );
		add_filter( 'screen_options_show_screen', array( self::get_instance(), 'remove_screen_options_tab' ), 199 );
		add_action( 'init', array( self::get_instance(), 'remove_admin_bar' ), 199 );
		add_action( 'init', array( self::get_instance(), 'remove_font_awesome' ), 15 );

	}

	/**
	 * Add dashboard custom CSS.
	 */
	public function dashboard_custom_css() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		// Get setting option from current blog.
		$settings = get_option( 'udb_settings' );

		// Stop here if custom CSS is defined for the current blog.
		if ( isset( $settings['custom_css'] ) && ! empty( $settings['custom_css'] ) ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->dashboard_custom_css();
		restore_current_blog();

	}

	/**
	 * Add admin custom CSS.
	 */
	public function admin_custom_css() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		// Get setting option from current blog.
		$settings = get_option( 'udb_settings' );

		// Stop here if custom CSS is defined for the current blog.
		if ( isset( $settings['custom_admin_css'] ) && ! empty( $settings['custom_admin_css'] ) ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->admin_custom_css();
		restore_current_blog();

	}

	/**
	 * Change Dashboard's headline.
	 */
	public function change_dashboard_headline() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->change_dashboard_headline();
		restore_current_blog();

	}

	/**
	 * Remove help tab on admin area.
	 */
	public function remove_help_tab() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->remove_help_tab();
		restore_current_blog();

	}

	/**
	 * Remove screen options on admin area.
	 *
	 * @param bool $show_screen Whether or not to show the screen options tab.
	 * @return bool
	 */
	public function remove_screen_options_tab( $show_screen ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $show_screen;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$show_screen = $settings_output->remove_screen_options_tab( $show_screen );
		restore_current_blog();

		return $show_screen;

	}

	/**
	 * Remove admin bar from frontend.
	 *
	 * @return void
	 */
	public function remove_admin_bar() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->remove_admin_bar();
		restore_current_blog();

	}

	/**
	 * Remove Font Awesome.
	 */
	public function remove_font_awesome() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$settings_output = Setting_Output::get_instance();

		switch_to_blog( $blueprint );
		$settings_output->remove_font_awesome();
		restore_current_blog();

	}

}
