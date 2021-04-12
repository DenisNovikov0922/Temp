<?php
/**
 * Branding output.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Branding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use UdbPro\Helpers\Branding_Helper;

/**
 * Class to setup branding output.
 */
class Branding_Output extends Base_Output {

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
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup branding output.
	 */
	public function setup() {

		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'dashboard_styles' ), 100 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ), 100 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'frontend_styles' ), 100 );

		add_filter( 'udb_branding_dashboard_styles', array( self::get_instance(), 'minify_css' ), 20 );
		add_filter( 'udb_branding_admin_styles', array( self::get_instance(), 'minify_css' ), 20 );
		add_filter( 'udb_branding_frontend_styles', array( self::get_instance(), 'minify_css' ), 20 );
		add_filter( 'udb_branding_login_styles', array( self::get_instance(), 'minify_css' ), 20 );

		add_action( 'admin_bar_menu', array( self::get_instance(), 'replace_admin_bar_logo' ), 11 );
		add_filter( 'udb_admin_bar_logo_url', array( self::get_instance(), 'change_admin_bar_logo_url' ) );
		add_action( 'admin_bar_menu', array( self::get_instance(), 'remove_admin_bar_logo' ), 99 );
		add_action( 'adminmenu', array( self::get_instance(), 'modern_admin_bar_logo' ) );

	}

	/**
	 * Enqueue dashboard styles.
	 */
	public function dashboard_styles() {

		$udb_dashboard_styles = $this->get_dashboard_styles();
		wp_add_inline_style( 'udb-dashboard', $udb_dashboard_styles );

	}

	/**
	 * Get dashboard styles.
	 *
	 * @return string The dashboard CSS.
	 */
	public function get_dashboard_styles() {

		$css = '';

		ob_start();
		include_once __DIR__ . '/inc/widget-styles.css.php';
		$css = ob_get_clean();

		return apply_filters( 'udb_branding_dashboard_styles', $css );

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$branding_helper = new Branding_Helper();

		if ( ! $branding_helper->is_enabled() ) {
			return;
		}

		$udb_admin_styles = $this->get_admin_styles();
		wp_add_inline_style( 'wp-admin', $udb_admin_styles );

	}

	/**
	 * Get admin styles.
	 *
	 * @return string The admin CSS.
	 */
	public function get_admin_styles() {

		$css = '';

		$branding = get_option( 'udb_branding' );

		ob_start();

		if ( ! isset( $branding['layout'] ) || 'default' === $branding['layout'] ) {
			require_once __DIR__ . '/inc/admin-styles-default.css.php';
		} else {
			require_once __DIR__ . '/inc/admin-styles-modern.css.php';
		}

		$css = ob_get_clean();

		return apply_filters( 'udb_branding_admin_styles', $css );

	}

	/**
	 * Enqueue frontend styles.
	 */
	public function frontend_styles() {

		$branding_helper = new Branding_Helper();

		if ( ! $branding_helper->is_enabled() ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			return;
		}

		$udb_frontend_styles = $this->get_frontend_styles();
		wp_add_inline_style( 'admin-bar', $udb_frontend_styles );

	}

	/**
	 * Get frontend styles.
	 *
	 * @return string The frontend CSS.
	 */
	public function get_frontend_styles() {

		$css = '';

		ob_start();
		include_once __DIR__ . '/inc/frontend-styles.css.php';
		$css = ob_get_clean();

		return apply_filters( 'udb_branding_frontend_styles', $css );

	}

	/**
	 * Minify CSS
	 *
	 * @param string $css The css.
	 *
	 * @return string the minified CSS.
	 */
	public function minify_css( $css ) {

		// Remove comments.
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Remove spaces.
		$css = str_replace( ': ', ':', $css );
		$css = str_replace( ' {', '{', $css );
		$css = str_replace( ', ', ',', $css );
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

		return $css;

	}

	/**
	 * Replace admin bar logo.
	 *
	 * We do this to add a filter to the logo URL.
	 *
	 * @param object $wp_admin_bar The wp admin bar.
	 */
	public function replace_admin_bar_logo( $wp_admin_bar ) {

		$wp_admin_bar->remove_menu( 'wp-logo' );

		$wp_admin_bar->add_menu(
			array(
				'id'    => 'wp-logo',
				'title' => '<span class="ab-icon"></span>',
				'href'  => apply_filters( 'udb_admin_bar_logo_url', network_site_url() ),
			)
		);

	}

	/**
	 * Change admin bar logo URL.
	 *
	 * Doesn't require separate multisite support!
	 *
	 * @param string $admin_bar_logo_url The admin bar logo URL.
	 *
	 * @return string The updated admin bar logo URL.
	 */
	public function change_admin_bar_logo_url( $admin_bar_logo_url ) {

		$branding = get_option( 'udb_branding' );

		if ( ! isset( $branding['enabled'] ) ) {
			return $admin_bar_logo_url;
		}

		if ( isset( $branding['remove_admin_bar_logo'] ) ) {
			return $admin_bar_logo_url;
		}

		if ( ! empty( $branding['admin_bar_logo_url'] ) ) {
			$admin_bar_logo_url = $branding['admin_bar_logo_url'];
		}

		return $admin_bar_logo_url;

	}

	/**
	 * Remove admin bar logo.
	 *
	 * @param object $wp_admin_bar The wp admin bar.
	 */
	public function remove_admin_bar_logo( $wp_admin_bar ) {

		$branding = get_option( 'udb_branding' );

		if ( isset( $branding['remove_admin_bar_logo'] ) ) {
			$wp_admin_bar->remove_node( 'wp-logo' );
		}

	}

	/**
	 * Modern layout: custom admin bar logo.
	 */
	public function modern_admin_bar_logo() {

		$branding = get_option( 'udb_branding' );

		// Stop here if branding is not enabled.
		if ( ! isset( $branding['enabled'] ) ) {
			return;
		}

		// Stop here if modern layout is not selected.
		if ( ! isset( $branding['layout'] ) || 'modern' !== $branding['layout'] ) {
			return;
		}

		// If no logo is selected, use default.
		if ( ! empty( $branding['admin_bar_logo_image'] ) ) {
			$logo = $branding['admin_bar_logo_image'];
		} else {
			$logo = $this->url . '/assets/images/ultimate-dashboard-logo.png';
		}

		// If no logo url was set, use default.
		if ( ! empty( $branding['admin_bar_logo_url'] ) ) {
			$url = $branding['admin_bar_logo_url'];
		} else {
			$url = network_site_url();
		}

		// Let's add a filter, in case someone wants to dynamically change the logo.
		$logo = apply_filters( 'udb_admin_bar_logo_image', $logo );

		echo '<li id="udb-admin-logo-wrapper">';
		echo '<a href="' . esc_url( $url ) . '">';
		echo '<img class="udb-admin-logo" src="' . esc_url( $logo ) . '" />';
		echo '</a>';
		echo '</li>';

	}

}
