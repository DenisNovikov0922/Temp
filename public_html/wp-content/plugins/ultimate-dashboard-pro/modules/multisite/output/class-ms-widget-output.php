<?php
/**
 * Multisite's widgets output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use UdbPro\Helpers\Branding_Helper;
use UdbPro\Helpers\Multisite_Helper;

use Udb\Widget\Widget_Output as Free_Widget_Output;
use UdbPro\Widget\Widget_Output;

/**
 * Class to setup the module output.
 */
class Ms_Widget_Output extends Base_Output {

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

		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'remove_dashboard_widgets' ), 100 );
		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'add_dashboard_widgets' ) );
		add_filter( 'udb_pro_dashboard_columns', array( self::get_instance(), 'dashboard_columns' ) );
		add_filter( 'udb_pro_dashboard_columns_layout', array( self::get_instance(), 'dashboard_columns_layout' ) );
		add_action( 'admin_init', array( self::get_instance(), 'update_widget_order' ), 15 );
		add_action( 'user_register', array( self::get_instance(), 'update_widget_order' ) );

		add_filter(
			'udb_license_capability',
			function () {
				'manage_network'; // Only allow network admins to access the license page.
			}
		);

		add_filter(
			'udb_tools_capability',
			function () {
				'manage_network'; // Only allow network admins to access the tools page.
			}
		);

		add_filter(
			'udb_modules_capability',
			function () {
				'manage_network'; // Only allow network admins to access the modules page.
			}
		);

		add_filter( 'udb_settings_capability', array( self::get_instance(), 'multisite_capability' ) );

	}

	/**
	 * Remove dashboard widgets.
	 */
	public function remove_dashboard_widgets() {

		global $blueprint;

		// Stop here if blueprint is not defined.
		if ( empty( $blueprint ) ) {
			return;
		}

		$blog_id   = get_current_blog_id();
		$ms_helper = new Multisite_Helper();

		if ( ! in_array( $blog_id, $ms_helper->get_excluded_sites() ) ) {

			switch_to_blog( $blueprint );

			$widgets_output     = Free_Widget_Output::get_instance();
			$pro_widgets_output = Widget_Output::get_instance();

			$widgets_output->remove_default_dashboard_widgets();
			$pro_widgets_output->remove_3rd_party_widgets();

			restore_current_blog();

		}

	}

	/**
	 * Add dashboard widgets.
	 */
	public function add_dashboard_widgets() {

		global $blueprint;

		// Stop here if blueprint is not defined.
		if ( empty( $blueprint ) ) {
			return;
		}

		$blog_id    = get_current_blog_id();
		$user_roles = wp_get_current_user()->roles;
		$ms_helper  = new Multisite_Helper();

		if ( ! in_array( $blog_id, $ms_helper->get_excluded_sites() ) ) {

			$widgets_output = Free_Widget_Output::get_instance();

			switch_to_blog( $blueprint );
			$widgets_output->add_dashboard_widgets( $user_roles );
			restore_current_blog();

		}

	}

	/**
	 * Dashboard columns.
	 *
	 * @param array $columns The dashboard columns.
	 *
	 * @return array The updated dashboard columns.
	 */
	public function dashboard_columns( $columns ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $columns;
		}

		switch_to_blog( $blueprint );

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['dashboard_columns'] ) ) {
			$columns['dashboard'] = 4;
		} else {
			$columns['dashboard'] = $settings['dashboard_columns'];
		}

		restore_current_blog();

		return $columns;

	}

	/**
	 * Dashboard columns layout.
	 *
	 * @param array $columns The dashboard columns.
	 *
	 * @return array The updated dashboard columns.
	 */
	public function dashboard_columns_layout( $columns ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $columns;
		}

		switch_to_blog( $blueprint );

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['dashboard_columns'] ) ) {
			$columns = 4;
		} else {
			$columns = $settings['dashboard_columns'];
		}

		restore_current_blog();

		return $columns;

	}

	/**
	 * Update widget order.
	 *
	 * @param int $user_id The user ID (when new user registered).
	 */
	public function update_widget_order( $user_id = 0 ) {

		$widget_helper     = new \UdbPro\Helpers\Widget_Helper();
		$widget_order_user = $widget_helper->get_widget_order_user( true );
		$order_option_meta = get_option( 'udb_pro_widget_order' );

		// Stop here if global widget order is not defined.
		if ( ! $widget_order_user ) {
			return;
		}

		$order_key       = 'meta-box-order_dashboard';
		$order_user_meta = get_user_meta( $widget_order_user, $order_key, true );

		if ( ! empty( $user_id ) ) {
			// If executed in "user_register" hook, update the new user's meta.
			update_user_meta( $user_id, $order_key, $order_user_meta );
		}

		// Stop if order hasn't changed.
		if ( $order_user_meta === $order_option_meta ) {
			return;
		}

		$blogusers = get_users();

		foreach ( $blogusers as $user ) {

			// Check if they're not the selected user.
			if ( $user->ID !== $widget_order_user ) {
				update_user_meta( $user->ID, $order_key, $order_user_meta );
			}
		}

		update_option( 'udb_pro_widget_order', $order_user_meta );

	}

	/**
	 * Multisite capability.
	 */
	public function multisite_capability() {

		$udb_capability = get_site_option( 'udb_multisite_capability' ) ? get_site_option( 'udb_multisite_capability' ) : 'manage_network';

		return $udb_capability;

	}

}
