<?php
/**
 * Admin page output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\AdminPage;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use UdbPro\Helpers\Content_Helper;
use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup admin page output.
 */
class Admin_Page_Output extends Base_Output {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/admin-page';

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
	 * Setup admin page output.
	 */
	public function setup() {

		add_action( 'udb_admin_page_setup_menu', array( self::get_instance(), 'setup_menu' ) );
		add_action( 'udb_admin_page_content_output', array( self::get_instance(), 'output_content' ), 10, 3 );
		add_filter( 'udb_admin_page_user_roles', array( self::get_instance(), 'add_super_admin' ) );

	}

	/**
	 * Setup menu.
	 *
	 * @param object $output_class The output utility class.
	 */
	public function setup_menu( $output_class ) {

		$ms_helper = new Multisite_Helper();

		$parent_pages  = $output_class->get_posts( 'parent' );
		$submenu_pages = $output_class->get_posts( 'submenu' );

		$ms_parent_pages  = array();
		$ms_submenu_pages = array();

		if ( $ms_helper->needs_to_switch_blog() ) {
			global $blueprint;

			switch_to_blog( $blueprint );

			$ms_parent_pages  = $output_class->get_posts( 'parent' );
			$ms_submenu_pages = $output_class->get_posts( 'submenu' );

			restore_current_blog();
		}

		if ( ! empty( $parent_pages ) ) {
			$output_class->prepare_menu( $parent_pages );
		}

		if ( ! empty( $ms_parent_pages ) ) {
			$output_class->prepare_menu( $ms_parent_pages, true );
		}

		if ( ! empty( $submenu_pages ) ) {
			$output_class->prepare_menu( $submenu_pages );
		}

		if ( ! empty( $ms_submenu_pages ) ) {
			$output_class->prepare_menu( $ms_submenu_pages, true );
		}

	}

	/**
	 * Output admin page content.
	 *
	 * @param WP_Post $post The admin page's post object.
	 * @param string  $editor The content editor type.
	 * @param bool    $from_multisite Whether or not the function is called by multisite function.
	 */
	public function output_content( $post, $editor, $from_multisite = false ) {

		$ms_helper   = new Multisite_Helper();
		$switch_blog = $from_multisite && $ms_helper->needs_to_switch_blog() ? true : false;

		if ( $switch_blog ) {
			global $blueprint;
			switch_to_blog( $blueprint );
		}

		if ( 'html' === $post->content_type ) {

			echo $post->html_content;

		} else {

			if ( 'beaver' === $editor ) {
				wp_enqueue_style( 'bxslider', ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/admin-page/assets/bxslider/jquery.bxslider.min.css', array(), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION );

				wp_enqueue_script( 'bxslider', ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/admin-page/assets/bxslider/jquery.bxslider.min.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );
			}

			$content_helper = new Content_Helper();
			$content_helper->output_content_using_builder( $post, $editor );

		}

		if ( $switch_blog ) {
			restore_current_blog();
		}

	}

	/**
	 * Add super admin to existing roles in "class-admin-page-output.php" in the free version.
	 *
	 * @param array $roles The existing user roles.
	 * @return array The user roles.
	 */
	public function add_super_admin( $roles ) {

		$ms_helper = new Multisite_Helper();

		if ( $ms_helper->multisite_supported() ) {
			if ( is_super_admin() ) {
				array_push( $roles, 'super_admin' );
			}
		}

		return $roles;

	}

}
