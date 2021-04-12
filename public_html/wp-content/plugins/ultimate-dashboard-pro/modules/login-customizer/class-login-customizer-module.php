<?php
/**
 * Login Customizer module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\LoginCustomizer;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;
use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup login customizer module.
 */
class Login_Customizer_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/login-customizer';

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
	 * Setup login customizer module.
	 */
	public function setup() {

		add_action( 'customize_controls_enqueue_scripts', array( self::get_instance(), 'control_scripts' ) );
		add_action( 'login_enqueue_scripts', array( self::get_instance(), 'preview_styles' ), 99 );
		add_action( 'customize_preview_init', array( self::get_instance(), 'preview_scripts' ) );

		add_filter( 'udb_login_customizer_control_file_paths', array( self::get_instance(), 'modify_control_files' ) );
		add_filter( 'udb_login_customizer_default_logo_height', array( self::get_instance(), 'default_logo_height' ) );

		// The module output.
		require_once __DIR__ . '/class-login-customizer-output.php';
		Login_Customizer_Output::init();

	}

	/**
	 * Enqueue login customizer control scripts.
	 */
	public function control_scripts() {

		wp_enqueue_script( 'udb-pro-login-customizer-control', $this->url . '/assets/js/controls.js', array( 'customize-controls' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

		wp_enqueue_script( 'udb-pro-login-customizer-events', $this->url . '/assets/js/preview.js', array( 'customize-controls' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

	}

	/**
	 * Enqueue scripts to login customizer preview scripts.
	 */
	public function preview_scripts() {

		wp_enqueue_script( 'udb-pro-login-customizer-preview', $this->url . '/assets/js/preview.js', array( 'customize-preview' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

	}

	/**
	 * Enqueue styles to login customizer preview styles.
	 */
	public function preview_styles() {

		if ( ! is_customize_preview() ) {
			return;
		}

		wp_enqueue_style( 'udb-pro-login-customizer-preview', $this->url . '/assets/css/preview.css', array( 'udb-login-customizer-preview' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, 'all' );

	}

	/**
	 * Change the default logo height value of login customizer
	 *
	 * @param string|int $height The logo height value.
	 * @return string|int The logo height value.
	 */
	public function default_logo_height( $height ) {

		$ms_helper       = new Multisite_Helper();
		$blueprint_login = array();

		if ( $ms_helper->needs_to_switch_blog() ) {
			global $blueprint;

			$blueprint_login = get_blog_option( $blueprint, 'udb_login', array() );
			$height          = ! empty( $blueprint_login ) && isset( $blueprint_login['logo_height'] ) ? $blueprint_login['logo_height'] : '100%';
		}

		return $height;

	}

	/**
	 * Modify existing UDB control files.
	 *
	 * @param array $files Associative array containing "section -> file" pairs.
	 * @return array $files
	 */
	public function modify_control_files( $files ) {

		$files['bg']     = __DIR__ . '/sections/bg.php';
		$files['layout'] = __DIR__ . '/sections/layout.php';

		return $files;

	}

}
