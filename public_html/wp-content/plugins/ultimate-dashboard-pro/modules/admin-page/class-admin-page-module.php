<?php
/**
 * Admin page module.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\AdminPage;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup admin page module.
 */
class Admin_Page_Module extends Base_Module {

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
	 * Setup admin page module.
	 */
	public function setup() {

		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'udb_admin_page_advanced_fields', array( self::get_instance(), 'custom_js_field' ) );
		add_action( 'udb_save_admin_page', array( self::get_instance(), 'save_post' ) );
		add_filter( 'udb_admin_page_list_roles_column_content', array( self::get_instance(), 'roles_column_content' ), 10, 2 );

		// Page builder supports.
		// add_action( 'elementor/init', array( self::get_instance(), 'add_elementor_support' ) );
		// add_action( 'init', array( self::get_instance(), 'add_beaver_support' ) );
		// add_action( 'init', array( self::get_instance(), 'add_brizy_support' ) );
		// add_action( 'init', array( self::get_instance(), 'add_divi_support' ) );

		require __DIR__ . '/class-admin-page-output.php';
		Admin_Page_Output::init();

	}

	/**
	 * Register metaboxes.
	 */
	public function register_meta_boxes() {

		add_meta_box( 'udb-roles-metabox', __( 'User Role Access', 'ultimatedashboard' ), array( $this, 'roles_metabox' ), 'udb_admin_page', 'side' );

	}

	/**
	 * "User Role Access" metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function roles_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/roles.php';
		$metabox( $post );

	}

	/**
	 * Custom JS field inside "Advanced" metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function custom_js_field( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/custom-js.php';
		$metabox( $post );

	}

	/**
	 * Save admin page's postmeta data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post( $post_id ) {

		$save_widget = require __DIR__ . '/inc/save-post.php';
		$save_widget( $this, $post_id );

	}

	/**
	 * Auto add udb_admin_page post type to Elementor cpt support.
	 */
	public function add_elementor_support() {

		$post_types = get_option( 'elementor_cpt_support', array() );

		if ( ! in_array( 'udb_admin_page', $post_types, true ) ) {
			array_push( $post_types, 'udb_admin_page' );
			update_option( 'elementor_cpt_support', $post_types, true );
		}

	}

	/**
	 * Auto add udb_admin_page post type to Beaver Builder cpt support.
	 */
	public function add_beaver_support() {

		if ( ! class_exists( 'FLBuilderModel' ) ) {
			return;
		}

		$post_types = FLBuilderModel::get_post_types();

		if ( ! in_array( 'udb_admin_page', $post_types, true ) ) {
			array_push( $post_types, 'udb_admin_page' );
			FLBuilderModel::update_admin_settings_option( '_fl_builder_post_types', $post_types, true );
		}

	}

	/**
	 * Auto add udb_admin_page post type to Brizy Builder cpt support.
	 */
	public function add_brizy_support() {

		if ( ! class_exists( 'Brizy_Editor_Storage_Common' ) ) {
			return;
		}

		$post_types = Brizy_Editor_Storage_Common::instance()->get( 'post-types' );

		if ( ! in_array( 'udb_admin_page', $post_types, true ) ) {
			array_push( $post_types, 'udb_admin_page' );
			Brizy_Editor_Storage_Common::instance()->set( 'post-types', $post_types );
		}

	}

	/**
	 * Auto add udb_admin_page post type to Divi Builder cpt support.
	 */
	public function add_divi_support() {

		// Divi uses 2 option meta.
		$divi_integrations = array(
			'et_divi_builder_plugin' => 'et_pb_post_type_integration',
			'et_pb_builder_options'  => 'post_type_integration_main_et_pb_post_type_integration',
		);

		foreach ( $divi_integrations as $option_name => $integration_key ) {
			$options    = get_option( $option_name, array() );
			$post_types = isset( $options[ $integration_key ] ) ? $options[ $integration_key ] : array();

			if ( ! isset( $post_types['udb_admin_page'] ) || 'on' !== $post_types['udb_admin_page'] ) {
				$options[ $integration_key ]['udb_admin_page'] = 'on';

				update_option( $option_name, $options, true );
			}
		}

	}

	/**
	 * Modify the roles column content in admin page's post list screen.
	 *
	 * @param string $column_content The existing column content.
	 * @param int    $post_id The current admin page's post id.
	 *
	 * @return string The column content.
	 */
	public function roles_column_content( $column_content, $post_id ) {

		$roles = get_post_meta( $post_id, 'udb_allowed_roles', true );
		$roles = is_serialized( $roles ) ? unserialize( $roles ) : $roles;
		$roles = empty( $roles ) ? array( 'all' ) : $roles;
		$roles = implode( ', ', $roles );

		return $roles;

	}

}
