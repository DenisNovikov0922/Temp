<?php
/**
 * Multisite module.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup multisite module.
 */
class Multisite_Module extends Base_Module {

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
	 * Setup tools module.
	 */
	public function setup() {

		add_action( 'network_admin_menu', array( self::get_instance(), 'submenu_page' ) );
		add_action( 'admin_init', array( self::get_instance(), 'add_settings' ) );
		add_action( 'admin_menu', array( self::get_instance(), 'remove_submenu_pages' ), 20 );
		add_filter( 'udb_login_customizer_control_file_paths', array( self::get_instance(), 'remove_login_customizer_controls' ) );

		require __DIR__ . '/class-multisite-output.php';
		Multisite_Output::init();

	}

	/**
	 * Remove unnecessary submenu pages on subsites.
	 */
	public function remove_submenu_pages() {

		global $blueprint;

		if ( get_current_blog_id() === $blueprint ) {
			return;
		}

		remove_submenu_page( 'edit.php?post_type=udb_widgets', 'udb_features' );
		remove_submenu_page( 'edit.php?post_type=udb_widgets', 'udb_tools' );

	}

	/**
	 * Remove unnecessary controls from login customizer.
	 *
	 * @param array $control_files List of control files.
	 * @return array
	 */
	public function remove_login_customizer_controls( $control_files ) {

		global $blueprint;

		if ( get_current_blog_id() === $blueprint ) {
			return $control_files;
		}

		if ( ! isset( $control_files['logo'] ) ) {
			return array();
		}

		return array(
			'logo' => $control_files['logo'],
		);

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'settings.php', 'Ultimate Dashboard', 'Ultimate Dashboard', 'manage_options', 'ultimate-dashboard-multisite', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		require __DIR__ . '/templates/multisite-template.php';

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Register settings.
		register_setting( 'udb-multisite-settings-group', 'udb_multisite_blueprint' );
		register_setting( 'udb-multisite-settings-group', 'udb_multisite_exclude' );
		register_setting( 'udb-multisite-settings-group', 'udb_multisite_widget_order' );
		register_setting( 'udb-multisite-settings-group', 'udb_multisite_capability' );

		// Settings sections.
		add_settings_section( 'udb-multisite-blueprint-settings', '', '', 'ultimate-dashboard-multisite' );
		add_settings_section( 'udb-multisite-widget-order-settings', '', '', 'ultimate-dashboard-multisite' );
		add_settings_section( 'udb-multisite-capability-settings', '', '', 'ultimate-dashboard-multisite' );

		// Settings fields.
		add_settings_field( 'multisite-blueprint-settings', __( 'Blueprint Site', 'ultimatedashboard' ), array( $this, 'blueprint_field' ), 'ultimate-dashboard-multisite', 'udb-multisite-blueprint-settings' );
		add_settings_field( 'multisite-exclude-settings', __( 'Exclude', 'ultimatedashboard' ), array( $this, 'exclude_sites_field' ), 'ultimate-dashboard-multisite', 'udb-multisite-blueprint-settings' );
		add_settings_field( 'multisite-widget-order-settings', __( 'Order Widgets by', 'ultimatedashboard' ), array( $this, 'widgets_order_field' ), 'ultimate-dashboard-multisite', 'udb-multisite-widget-order-settings' );
		add_settings_field( 'multisite-capability-settings', __( 'Capability', 'ultimatedashboard' ), array( $this, 'capability_field' ), 'ultimate-dashboard-multisite', 'udb-multisite-capability-settings' );

	}

	/**
	 * Blueprint field.
	 */
	public function blueprint_field() {

		$field = require __DIR__ . '/templates/fields/blueprint.php';
		$field();

	}

	/**
	 * Exclude sites field.
	 */
	public function exclude_sites_field() {

		$field = require __DIR__ . '/templates/fields/exclude-sites.php';
		$field();

	}

	/**
	 * Widgets order field.
	 */
	public function widgets_order_field() {

		$field = require __DIR__ . '/templates/fields/widgets-order.php';
		$field();

	}

	/**
	 * Capability field.
	 */
	public function capability_field() {

		$field = require __DIR__ . '/templates/fields/capability.php';
		$field();

	}

}
