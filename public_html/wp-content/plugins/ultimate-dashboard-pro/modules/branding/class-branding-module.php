<?php
/**
 * Branding module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Branding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup branding module.
 */
class Branding_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/branding';

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
	 * Setup branding module.
	 */
	public function setup() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_filter( 'udb_branding_enable_feature_field_path', array( self::get_instance(), 'enable_field' ) );
		add_filter( 'udb_branding_choose_layout_field_path', array( self::get_instance(), 'choose_layout_field' ) );
		add_filter( 'udb_branding_accent_color_field_path', array( self::get_instance(), 'accent_color_field' ) );
		add_filter( 'udb_branding_admin_bar_logo_field_path', array( self::get_instance(), 'admin_bar_logo_field' ) );
		add_filter( 'udb_branding_admin_bar_logo_url_field_path', array( self::get_instance(), 'admin_bar_logo_url_field' ) );
		add_filter( 'udb_branding_version_text_field_path', array( self::get_instance(), 'version_text_field' ) );

		// The module output.
		require_once __DIR__ . '/class-branding-output.php';
		Branding_Output::init();

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$enqueue = require __DIR__ . '/inc/css-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		$enqueue = require __DIR__ . '/inc/js-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Enable branding field.
	 *
	 * @param string $template The existing template path.
	 * @return string The template path.
	 */
	public function enable_field( $template ) {

		return __DIR__ . '/templates/fields/enable.php';

	}

	/**
	 * Choose layout field.
	 *
	 * @param string $template The existing template path.
	 * @return string The template path.
	 */
	public function choose_layout_field( $template ) {

		return __DIR__ . '/templates/fields/choose-layout.php';

	}

	/**
	 * Accent color field.
	 *
	 * @param string $template The existing template path.
	 * @return string The template path.
	 */
	public function accent_color_field( $template ) {

		return __DIR__ . '/templates/fields/accent-color.php';

	}

	/**
	 * Admin bar logo field.
	 *
	 * @param string $template The existing template path.
	 * @return string The template path.
	 */
	public function admin_bar_logo_field( $template ) {

		return __DIR__ . '/templates/fields/admin-bar-logo.php';

	}

	/**
	 * Admin bar logo url field.
	 *
	 * @param string $template The existing template path.
	 * @return string The template path.
	 */
	public function admin_bar_logo_url_field( $template ) {

		return __DIR__ . '/templates/fields/admin-bar-logo-url.php';

	}

}
