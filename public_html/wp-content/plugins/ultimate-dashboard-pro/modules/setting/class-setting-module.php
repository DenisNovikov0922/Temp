<?php
/**
 * Settings module.
 *
 * @package Ultimate_Dashboard
 */

namespace UdbPro\Setting;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;
use UdbPro\Helpers\Multisite_Helper;

/**
 * Class to setup settings module.
 */
class Setting_Module extends Base_Module {
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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/setting';

	}

	/**
	 * Setup settings module.
	 */
	public function setup() {

		add_filter( 'udb_remove_3rd_party_widgets_field_path', array( $this, 'remove_3rd_party_widgets_field' ) );
		add_action( 'udb_after_widget_metabox', array( $this, 'dashboard_builder_metabox' ) );
		add_action( 'admin_init', array( $this, 'pro_setting_fields' ) );

	}

	/**
	 * Remove 3rd party widgets field.
	 *
	 * @param string $template The template path of the field.
	 * @return string The template path of the field.
	 */
	public function remove_3rd_party_widgets_field( $template ) {

		return __DIR__ . '/templates/fields/remove-3rd-party-widgets.php';

	}

	/**
	 * Add dashboard builder metabox after widget styling metabox.
	 */
	public function dashboard_builder_metabox() {
		add_settings_section( 'udb-builder-section', __( 'Page Builder Dashboard', 'ultimatedashboard' ), '', 'udb-widget-settings' );

		add_settings_field(
			'page-builder-template-headline',
			__( 'User Role', 'ultimatedashboard' ),
			function () {
				echo '<strong>' . __( 'Saved Template', 'ultimatedashboard' ) . '<strong> ';
			},
			'udb-widget-settings',
			'udb-builder-section'
		);

		add_settings_field(
			'page-builder-template-all',
			__( 'All', 'ultimatedashboard' ),
			function () {
				$this->page_builder_template_field( 'all' );
			},
			'udb-widget-settings',
			'udb-builder-section'
		);

		$ms_helper = new Multisite_Helper();

		if ( $ms_helper->multisite_supported() ) {
			add_settings_field(
				'page-builder-template-super_admin',
				__( 'Super Admin', 'ultimatedashboard' ),
				function () {
					$this->page_builder_template_field( 'super_admin' );
				},
				'udb-widget-settings',
				'udb-builder-section'
			);
		}

		$wp_roles   = wp_roles();
		$role_names = $wp_roles->role_names;

		foreach ( $role_names as $role_key => $role_name ) {
			add_settings_field(
				'page-builder-template-' . $role_key,
				ucwords( $role_name ),
				function () use ( $role_key ) {
					$this->page_builder_template_field( $role_key );
				},
				'udb-widget-settings',
				'udb-builder-section'
			);
		}
	}

	/**
	 * Page builder template field.
	 *
	 * @param string $role_key The role key.
	 */
	public function page_builder_template_field( $role_key ) {

		$field = require __DIR__ . '/templates/fields/page-builder.php';
		$field( $role_key );

	}

	/**
	 * Settings fields for pro version only.
	 */
	public function pro_setting_fields() {

		add_settings_field( 'column-settings', __( 'Dashboard Columns', 'ultimatedashboard' ), array( $this, 'widget_columns_field' ), 'udb-general-settings', 'udb-general-section' );

		$ms_helper = new Multisite_Helper();

		// Widget order.
		if ( ! $ms_helper->is_network_active() ) {

			add_settings_field( 'widget-order', __( 'Order Widgets by', 'ultimatedashboard' ), array( $this, 'widgets_order_field' ), 'udb-general-settings', 'udb-general-section' );

		}

	}

	/**
	 * Widget columns field.
	 */
	public function widget_columns_field() {

		$field = require __DIR__ . '/templates/fields/widget-columns.php';
		$field();

	}

	/**
	 * Widgets order field.
	 */
	public function widgets_order_field() {

		$field = require __DIR__ . '/templates/fields/widgets-order.php';
		$field();

	}

}
