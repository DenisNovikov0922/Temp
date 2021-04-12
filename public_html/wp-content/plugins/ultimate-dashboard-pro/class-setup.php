<?php
/**
 * Setup Ultimate Dashboard PRO plugin.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use UdbPro\Helpers\Content_Helper;
use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup Ultimate Dashboard PRO plugin.
 */
class Setup {

	/**
	 * The class instanace
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Get the class instance.
	 *
	 * @return object
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

		add_action( 'plugins_loaded', array( self::get_instance(), 'setup' ) );

	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		// Check whether Ultimate Dashboard free active status & version.
		if ( ! defined( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION' ) || version_compare( ULTIMATE_DASHBOARD_PLUGIN_VERSION, '3.0', '<' ) ) {

			require __DIR__ . '/modules/instant-install/class-instant-install-module.php';
			InstantInstall\Instant_Install_Module::init();

			// Stop if Ultimate Dashboard free is not active or it's version is lower than 3.0.
			return;

		}

		$this->load_helpers();
		Backwards_Compatibility::init();

		$blueprint = get_site_option( 'udb_multisite_blueprint' );

		// Declare glolbal variables.
		$GLOBALS['blueprint'] = $blueprint ? (int) $blueprint : 0;

		// Enable multisite support.
		add_filter( 'udb_pro_ms_support', '__return_true' );
		add_action( 'init', array( $this, 'load_textdomain' ) );

		$prefix = is_network_admin() ? 'network_admin_' : '';
		add_filter( $prefix . 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

		register_deactivation_hook( plugin_basename( __FILE__ ), array( $this, 'deactivation' ) );

		// Pro version filters.
		add_filter( 'udb_saved_modules', array( $this, 'saved_modules' ) );
		add_filter( 'udb_modules', array( $this, 'load_modules' ) );
		add_filter( 'udb_content_editor', array( $this, 'get_content_editor' ), 10, 2 );

	}

	/**
	 * Load Ultimate Dashboard Pro helper classes.
	 */
	public function load_helpers() {
		// Helper classes.
		require __DIR__ . '/helpers/class-multisite-helper.php';
		require __DIR__ . '/helpers/class-video-helper.php';
		require __DIR__ . '/helpers/class-content-helper.php';
		require __DIR__ . '/helpers/class-widget-helper.php';
		require __DIR__ . '/helpers/class-branding-helper.php';
	}

	/**
	 * Load textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'ultimatedashboard', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The modified action links array.
	 */
	public function action_links( $links ) {

		$multisite_settings = array();
		$settings           = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">' . __( 'Settings', 'ultimatedashboard' ) . '</a>' );

		if ( apply_filters( 'udb_pro_ms_support', false ) ) {
			$multisite_settings = is_multisite() ? array( '<a href="' . network_admin_url( 'settings.php?page=ultimate-dashboard-multisite' ) . '">' . __( 'Network Settings', 'ultimatedashboard' ) . '</a>' ) : array();
		}

		return array_merge( $links, $settings, $multisite_settings );

	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivation() {

		$settings  = get_option( 'udb_settings' );
		$ms_helper = new Multisite_Helper();

		if ( $ms_helper->needs_to_switch_blog() ) {

			global $blueprint;

			switch_to_blog( $blueprint );

			$settings = get_option( 'udb_settings' );

			restore_current_blog();
		}

		$remove_on_uninstall = isset( $settings['remove-on-uninstall'] ) ? true : false;

		add_filter( 'udb_clean_uninstall', ( $remove_on_uninstall ? '__return_true' : '__return_false' ) );

		if ( $remove_on_uninstall ) {

			delete_option( 'udb_admin_menu' );

			delete_option( 'udb_compat_branding_meta' );

			delete_option( 'udb_pro_widget_order' );
			delete_option( 'udb_multisite_blueprint' );
			delete_option( 'udb_multisite_exclude' );
			delete_option( 'udb_multisite_widget_order' );
			delete_option( 'udb_multisite_capability' );

		}

	}

	/**
	 * Filter the "get_content_editor" value of Content_Helper class in the free version.
	 *
	 * @param string $editor The editor name from free version.
	 * @param int    $post_id ID of the post being checked.
	 *
	 * @return string The content editor name from pro version.
	 */
	public function get_content_editor( $editor, $post_id ) {
		$content_helper = new Content_Helper();
		return $content_helper->get_content_editor( $post_id );
	}

	/**
	 * Get saved/default modules.
	 *
	 * Helper function, similar to what we have in the free version but with multisite support in mind.
	 * Also used to filter "udb_saved_modules" in the free version.
	 *
	 * @return array The saved/default modules.
	 */
	public function saved_modules() {

		$defaults = array(
			'white_label'       => 'true',
			'login_customizer'  => 'true',
			'admin_pages'       => 'true',
			'admin_menu_editor' => 'true',
		);

		$saved_modules = get_option( 'udb_modules', $defaults );

		$ms_helper = new Helpers\Multisite_Helper();

		if ( $ms_helper->needs_to_switch_blog() ) {
			global $blueprint;

			// If we need to switch blog, let's grab udb_modules from the blueprint.
			$saved_modules = get_blog_option( $blueprint, 'udb_modules', $defaults );
		}

		return $saved_modules;

	}

	/**
	 * Load Ultimate Dashboard Pro modules.
	 *
	 * @param array $modules The modules being loaded.
	 * @return array $modules The modules being loaded.
	 */
	public function load_modules( $modules ) {

		$modules['UdbPro\\Widget\\Widget_Module']   = __DIR__ . '/modules/widget/class-widget-module.php';
		$modules['UdbPro\\Setting\\Setting_Module'] = __DIR__ . '/modules/setting/class-setting-module.php';

		$saved_modules = $this->saved_modules();

		if ( 'true' === $saved_modules['white_label'] ) {
			$modules['UdbPro\\Branding\\Branding_Module'] = __DIR__ . '/modules/branding/class-branding-module.php';
		}

		if ( 'true' === $saved_modules['login_customizer'] ) {
			$modules['UdbPro\\LoginCustomizer\\Login_Customizer_Module'] = __DIR__ . '/modules/login-customizer/class-login-customizer-module.php';
		}

		if ( 'true' === $saved_modules['admin_pages'] ) {
			$modules['UdbPro\\AdminPage\\Admin_Page_Module'] = __DIR__ . '/modules/admin-page/class-admin-page-module.php';
		}

		if ( 'true' === $saved_modules['admin_menu_editor'] ) {
			$modules['UdbPro\\AdminMenu\\Admin_Menu_Module'] = __DIR__ . '/modules/admin-menu/class-admin-menu-module.php';
		}

		$modules['UdbPro\\Tool\\Tool_Module']       = __DIR__ . '/modules/tool/class-tool-module.php';
		$modules['UdbPro\\License\\License_Module'] = __DIR__ . '/modules/license/class-license-module.php';

		$ms_helper = new Helpers\Multisite_Helper();

		if ( $ms_helper->multisite_supported() ) {
			$modules['UdbPro\\Multisite\\Multisite_Module'] = __DIR__ . '/modules/multisite/class-multisite-module.php';
		}

		return $modules;

	}

}
