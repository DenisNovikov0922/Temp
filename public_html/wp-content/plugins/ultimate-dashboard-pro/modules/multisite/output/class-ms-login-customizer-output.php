<?php
/**
 * Multisite's login customizer output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Multisite\Output;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\LoginCustomizer\Login_Customizer_Output as Free_Login_Customizer_Output;

use UdbPro\LoginCustomizer\Login_Customizer_Output;

/**
 * Class to setup the module output.
 */
class Ms_Login_Customizer_Output extends Base_Output {

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

		add_filter( 'login_headertext', array( self::get_instance(), 'login_headertext' ), 30 );
		add_filter( 'login_headerurl', array( self::get_instance(), 'login_logo_url' ), 30 );
		add_action( 'login_head', array( self::get_instance(), 'print_login_styles' ), 15 );
		add_action( 'customize_register', array( self::get_instance(), 'customizer_sections' ) );

	}

	/**
	 * Change login page header text.
	 *
	 * @param string $text The existing header text.
	 * @return string The modified header text.
	 */
	public function login_headertext( $text ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $text;
		}

		// Get login option from the current blog.
		$login = get_option( 'udb_login' );

		// Stop here if logo url is set on the current blog.
		if ( isset( $login['logo_title'] ) && ! empty( $login['logo_title'] ) ) {
			return $text;
		}

		$login_customizer_output = Free_Login_Customizer_Output::get_instance();

		switch_to_blog( $blueprint );
		$text = $login_customizer_output->login_headertext( $text );
		restore_current_blog();

		return $text;

	}

	/**
	 * Change login logo url.
	 *
	 * @param string $url The existing login logo url.
	 * @return string The modified login logo url.
	 */
	public function login_logo_url( $url ) {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return $url;
		}

		// Get login option from the current blog.
		$login = get_option( 'udb_login' );

		// Stop here if logo url is set on the current blog.
		if ( isset( $login['logo_url'] ) && ! empty( $login['logo_url'] ) ) {
			return $url;
		}

		switch_to_blog( $blueprint );

		$login = get_option( 'udb_login' );

		// Get login URL from blueprint.
		if ( isset( $login['logo_url'] ) && ! empty( $login['logo_url'] ) ) {
			$url = $login['logo_url'];
		}

		restore_current_blog();

		// If login URL on the blueprint is {home_url}, replace it with the actual home URL of the subsite.
		if ( '{home_url}' === $url ) {
			$url = home_url();
		}

		return $url;

	}

	/**
	 * Print login styles.
	 */
	public function print_login_styles() {

		global $blueprint;

		// Stop here if we're on the blueprint or if it's not defined.
		if ( empty( $blueprint ) || get_current_blog_id() === $blueprint ) {
			return;
		}

		$login_customizer_output = Login_Customizer_Output::get_instance();

		switch_to_blog( $blueprint );
		$login_customizer_output->print_login_styles( true );
		restore_current_blog();

	}

	/**
	 * Register login customizer's sections in WP Customizer.
	 *
	 * @param WP_Customize $wp_customize The WP_Customize instance.
	 */
	public function customizer_sections( $wp_customize ) {

		// Stop here if we're on the main site.
		if ( is_main_site() ) {
			return;
		}

		$wp_customize->add_section(
			'udb_login_customizer_logo_section',
			array(
				'title' => __( 'Logo', 'ultimatedashboard' ),
				'panel' => 'udb_login_customizer_panel',
			)
		);

	}

}
