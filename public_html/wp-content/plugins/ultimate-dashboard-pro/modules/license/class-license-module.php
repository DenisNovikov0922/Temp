<?php
/**
 * License module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\License;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup branding module.
 */
class License_Module extends Base_Module {

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
		if(get_option( 'ultimate_dashboard_license_status' ) != 'valid') {
			update_option( 'ultimate_dashboard_license_key', '123456-123456-123456-123456' );
			update_option( 'ultimate_dashboard_license_status', 'valid' );
			}

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/license';

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
	 * Setup License module.
	 */
	public function setup() {

		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ), 20 );
		add_action( 'admin_notices', array( $this, 'license_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );

		require_once __DIR__ . '/edd/license.php';

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		// Stop here - no matter what - if we are on a Multisite installation and not on the main site.
		if ( ! is_main_site() ) {
			return;
		}

		add_submenu_page( 'edit.php?post_type=udb_widgets', 'License', 'License', apply_filters( 'udb_license_capability', 'manage_options' ), 'udb-license', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/license-template.php';
		$template();

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$enqueue = require __DIR__ . '/inc/css-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Admin notices about plugin's license.
	 */
	public function license_notice() {

		$status = get_option( 'ultimate_dashboard_license_status' );

		// Stop here if we are on a Multisite installation and not on the main site.
		if ( ! is_main_site() ) {
			return;
		}

		if ( 'valid' !== $status ) {

			$class            = 'notice notice-warning';
			$license_page_url = get_admin_url() . 'edit.php?post_type=' . ULTIMATE_DASHBOARD_PRO_LICENSE_PAGE;
			$product          = ULTIMATE_DASHBOARD_PRO_PRODUCT_NAME;
			$docs_url         = 'https://ultimatedashboard.io/docs/installation-license-activation/';
			$description      = sprintf(
				// translators: 1: License page url, 2: Product name, 3: Documentation URL.
				__( 'Please <a href="%1$s">activate your license key</a> to receive updates for <strong>%2$s</strong>. <a href="%3$s" target="_blank">Help</a>', 'ultimatedashboard' ),
				$license_page_url,
				$product,
				$docs_url
			);

			printf( '<div class="%1s"><p>%2s</p></div>', $class, $description );

		}

	}

}
