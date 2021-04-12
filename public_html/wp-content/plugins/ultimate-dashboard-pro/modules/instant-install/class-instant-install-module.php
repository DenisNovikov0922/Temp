<?php
/**
 * Instant install module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\InstantInstall;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup "instant install" module.
 */
class Instant_Install_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/instant-install';

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

		$instance = new self();
		$instance->setup();

	}

	/**
	 * Setup branding module.
	 */
	public function setup() {

		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		// Check whether Ultimate Dashboard free is active or not.
		if ( ! defined( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION' ) ) {

			add_action( 'admin_notices', array( self::get_instance(), 'free_version_notice' ) );

		} else {
			if ( version_compare( ULTIMATE_DASHBOARD_PLUGIN_VERSION, '3.0', '<' ) ) {

				add_action( 'admin_notices', array( self::get_instance(), 'lower_free_version_notice' ) );

			}
		}

		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_assets' ) );

	}

	/**
	 * Admin notice to require free version of the plugin.
	 *
	 * @return void
	 */
	public function free_version_notice() {

		$notice_class = 'notice notice-warning';

		if ( file_exists( WP_PLUGIN_DIR . '/ultimate-dashboard/ultimate-dashboard.php' ) ) {
			$button_text  = __( 'Activate Now', 'ultimatedashboard' );
			$action_class = 'udb-activate-plugin';
		} else {
			$button_text  = __( 'Install Now', 'ultimatedashboard' );
			$action_class = 'udb-install-plugin';
		}

		$install_button = '<button type="button" class="button button-primary udb-button ' . $action_class . '">' . $button_text . '</button>';

		$description  = '<h2>' . __( 'Ultimate Dashboard PRO 3.0', 'ultimatedashboard' ) . '</h2>';
		$description .= __( 'Since version 3.0, <strong>Ultimate Dashboard PRO</strong> requires <strong>Ultimate Dashboard</strong> to run on your WordPress installation.', 'ultimatedashboard' );
		$description .= '<br><br>';
		$description .= $install_button;

		printf( '<div class="%1s"><p>%2s</p></div>', $notice_class, $description );

	}

	/**
	 * Admin notice which shows that the free version is lower version 3.0.
	 *
	 * Example case:
	 *
	 * This could happen when they're still using version 2.x of the free version.
	 * Then without upgrading to version 3, they decide to buy the Pro one.
	 * And then they just install it without deactivating the old free version.
	 */
	public function lower_free_version_notice() {

		$notice_class  = 'notice notice-warning';
		$button_text   = __( 'Update Now', 'ultimatedashboard' );
		$update_button = '<button type="button" class="button button-primary udb-button udb-update-plugin">' . $button_text . '</button>';

		$description  = '<h2>' . __( 'Ultimate Dashboard PRO 3.0', 'ultimatedashboard' ) . '</h2>';
		$description .= __( 'Since version 3.0, <strong>Ultimate Dashboard PRO</strong> requires at minimum <strong>Ultimate Dashboard</strong> version 3 to run on your WordPress installation.', 'ultimatedashboard' );
		$description .= '<br><br>';
		$description .= $update_button;

		printf( '<div class="%1s"><p>%2s</p></div>', $notice_class, $description );

	}

	/**
	 * Enqueue admin assets.
	 */
	public function admin_assets() {

		wp_enqueue_style( 'udb-install-plugin', $this->url . '/assets/css/install.css', array(), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION );
		wp_enqueue_script( 'udb-install-plugin', $this->url . '/assets/js/install.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

		$slug   = 'ultimate-dashboard';
		$plugin = $slug . '/ultimate-dashboard.php';

		$activate_url = add_query_arg(
			array(
				'action'        => 'activate',
				'plugin'        => rawurlencode( $plugin ),
				'plugin_status' => 'all',
				'paged'         => '1',
				'_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin ),
			),
			esc_url( network_admin_url( 'plugins.php' ) )
		);

		wp_localize_script(
			'udb-install-plugin',
			'udbInstantInstall',
			array(
				'pluginPath'  => $plugin,
				'pluginSlug'  => $slug,
				'isActivated' => ( defined( 'ULTIMATE_DASHBOARD_PLUGIN_VERSION' ) ? true : false ),
				'redirectUrl' => admin_url( 'edit.php?post_type=udb_widgets' ),
				'activateUrl' => $activate_url,
				'updateNonce' => wp_create_nonce( 'upgrade-plugin_' . $plugin ),
				'texts'       => array(
					'update'     => __( 'Update', 'ultimatedashboard' ),
					'updating'   => __( 'Updating...', 'ultimatedashboard' ),
					'installing' => __( 'Installing...', 'ultimatedashboard' ),
					'activate'   => __( 'Activate Plugin', 'ultimatedashboard' ),
					'activating' => __( 'Activating...', 'ultimatedashboard' ),
				),
			)
		);

	}

}
