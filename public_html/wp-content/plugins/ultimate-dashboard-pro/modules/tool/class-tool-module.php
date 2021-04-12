<?php
/**
 * Tool module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Tool;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup tools module.
 */
class Tool_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/tool';

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

		add_filter( 'udb_export', array( self::get_instance(), 'add_export_data' ) );
		add_action( 'udb_import_settings', array( self::get_instance(), 'import_settings' ) );
		add_action( 'udb_import', array( self::get_instance(), 'import_admin_menu' ) );

	}

	/**
	 * Add extra export data.
	 *
	 * @param array $data The existing export data.
	 * @return array The merged export data.
	 */
	public function add_export_data( $data ) {

		$process    = require __DIR__ . '/inc/process-export.php';
		$extra_data = $process();

		return array_merge( $data, $extra_data );

	}

	/**
	 * Import extra settings.
	 *
	 * @param array $data The existing import data.
	 */
	public function import_settings( $data ) {

		$multisite_settings = isset( $data['multisite_settings'] ) ? $data['multisite_settings'] : array();

		if ( $multisite_settings ) {

			// Check if multisite is enabled regardless is_plugin_active_for_network status.
			if ( is_multisite() && ! empty( $multisite_settings ) ) {
				foreach ( $multisite_settings as $key => $value ) {
					update_site_option( $key, $value );
				}
			}
		}

	}

	/**
	 * Import admin menu.
	 *
	 * @param array $data The existing import data.
	 */
	public function import_admin_menu( $data ) {

		$admin_menu = isset( $data['admin_menu'] ) ? $data['admin_menu'] : array();

		if ( $admin_menu ) {
			update_option( 'udb_admin_menu', $admin_menu );

			add_settings_error(
				'udb_export',
				esc_attr( 'udb-import' ),
				__( 'Admin menu imported', 'ultimatedashboard' ),
				'updated'
			);
		}

	}

}
