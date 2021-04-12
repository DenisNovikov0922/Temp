<?php
/**
 * Multisite's admin menu output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\Helpers\User_Helper;
use Udb\AdminMenu\Admin_Menu_Module as Free_Admin_Menu_Module;

use UdbPro\Helpers\Multisite_Helper;
use UdbPro\AdminMenu\Admin_Menu_Output;

/**
 * Class to setup the module output.
 */
class Ms_Admin_Menu_Output extends Base_Output {

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

		add_action( 'admin_menu', array( self::get_instance(), 'menu_output' ), 110 );
		add_action( 'udb_ajax_before_get_admin_menu', array( self::get_instance(), 'remove_output_actions' ) );

		remove_action( 'udb_ajax_get_admin_menu', array( Free_Admin_Menu_Module::get_instance(), 'get_admin_menu' ), 15, 2 );
		add_action( 'udb_ajax_get_admin_menu', array( self::get_instance(), 'get_admin_menu' ), 20, 2 );

	}

	/**
	 * Remove multisite output from the ajax process of getting admin menu.
	 * See modules/admin-menu/ajax/class-get-menu.php in the free version.
	 */
	public function remove_output_actions() {

		// We need to remove admin menu's multisite output to get the original $menu & $submenu.
		remove_action( 'admin_menu', array( self::get_instance(), 'menu_output' ), 110 );

	}

	/**
	 * Preparing the admin menu output.
	 */
	public function menu_output() {

		$ms_helper = new Multisite_Helper();

		if ( ! $ms_helper->needs_to_switch_blog() ) {
			return;
		}

		global $blueprint;

		// Get data from current blog.
		$admin_menu = get_option( 'udb_admin_menu', array() );
		$roles      = wp_get_current_user()->roles;

		$admin_menu_output = Admin_Menu_Output::get_instance();

		// If current blog has the data, use it.
		if ( ! empty( $admin_menu ) ) {
			$admin_menu_output->menu_output( $roles );
			return;
		}

		// Otherwise, switch blog.
		switch_to_blog( $blueprint );
		$admin_menu_output->menu_output( $roles );
		restore_current_blog();

	}

	/**
	 * Get admin menu via ajax.
	 * This action will be called in "ajax" method in "class-get-menu.php" in the free version.
	 *
	 * @param object $ajax_handler The ajax handler class from the free version.
	 * @param string $role The role target to simulate.
	 */
	public function get_admin_menu( $ajax_handler, $role ) {

		$ms_helper   = new Multisite_Helper();
		$switch_blog = $ms_helper->needs_to_switch_blog();
		$admin_menu  = get_option( 'udb_admin_menu', array() );

		if ( $switch_blog && empty( $admin_menu ) ) {
			global $blueprint;
			switch_to_blog( $blueprint );
		}

		$roles = wp_get_current_user()->roles;
		$roles = ! $roles || ! is_array( $roles ) ? array() : $roles;

		$user_helper = new User_Helper();

		if ( ! in_array( $role, $roles, true ) ) {
			$user_helper->simulate_role( $role );
		}

		$ajax_handler->load_menu();

		$response = $ajax_handler->format_response( $role );

		if ( $switch_blog && empty( $admin_menu ) ) {
			restore_current_blog();
		}

		wp_send_json_success( $response );

	}

}
