<?php
/**
 * Admin menu output.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\AdminMenu;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\Helpers\Array_Helper;
use UdbPro\Helpers\Multisite_Helper;
use WP_User;

/**
 * Class to setup admin menu output.
 */
class Admin_Menu_Output extends Base_Output {

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

		$this->url = ULTIMATE_DASHBOARD_PRO_PLUGIN_URL . '/modules/admin-menu';

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
	 * Setup Admin Menu output.
	 */
	public function setup() {

		add_action( 'admin_menu', array( self::get_instance(), 'menu_output' ), 105 );
		add_action( 'udb_ajax_before_get_admin_menu', array( self::get_instance(), 'remove_output_actions' ) );

	}

	/**
	 * Remove output from the ajax process of getting admin menu.
	 * See modules/admin-menu/ajax/class-get-menu.php in the free version.
	 */
	public function remove_output_actions() {

		// We need to remove admin menu output to get the original $menu & $submenu.
		remove_action( 'admin_menu', array( self::get_instance(), 'menu_output' ), 105 );

	}

	/**
	 * Preparing the admin menu output.
	 *
	 * @param array $roles User roles before switching blog.
	 */
	public function menu_output( $roles = array() ) {
		$ms_helper = new Multisite_Helper();

		// Stop if multisite enabled & current user is a super admin.
		if ( $ms_helper->multisite_supported() && is_super_admin() ) {
			return;
		}

		// Stop if $roles is empty but needs to switch blog.
		if ( ! $roles && $ms_helper->needs_to_switch_blog() ) {
			return;
		}

		global $menu, $submenu;

		$user = wp_get_current_user();

		if ( ! $roles ) {
			$roles = $user->roles;

			if ( empty( $roles ) ) {
				$user  = new WP_User( get_current_user_id(), '', get_main_site_id() );
				$roles = $user->roles;
			}
		}

		if ( empty( $roles ) ) {
			return;
		}

		$role = $roles[0];

		$saved_menu = get_option( 'udb_admin_menu', array() );

		// Prioritize user based menu over role based menu.
		if ( isset( $saved_menu[ 'user_id_' . $user->ID ] ) ) {
			$role_menu = $saved_menu[ 'user_id_' . $user->ID ];
		} else {
			$role_menu = isset( $saved_menu[ $role ] ) ? $saved_menu[ $role ] : array();
		}

		if ( ! $role_menu ) {
			return;
		}

		$new_menu       = array();
		$hidden_menu    = array();
		$new_submenu    = array();
		$hidden_submenu = array();

		foreach ( $role_menu as $menu_index => $menu_item ) {
			$array_helper = new Array_Helper();

			$new_menu_item   = array();
			$menu_search_key = 'separator' === $menu_item['type'] ? 'url' : 'id';

			if ( 'separator' === $menu_item['type'] ) {
				$menu_finder_index = 2; // The separator url.
			} else {
				$menu_finder_index = 5; // The menu id attribute.
			}

			$default_menu_index   = $array_helper->find_assoc_array_index_by_value( $menu, $menu_finder_index, $menu_item[ $menu_search_key . '_default' ] );
			$matched_default_menu = false !== $default_menu_index ? $menu[ $default_menu_index ] : false;

			if ( $menu_item['was_added'] ) {
				$matched_default_menu = array(
					$menu_item['title'],
					'read',
					( $menu_item['url_default'] ? $menu_item['url_default'] : '/wp-admin/' ),
					'',
					$menu_item['class_default'],
					$menu_item['id_default'],
					'',
				);

				if ( isset( $menu_item[ $menu_item['icon_type'] ] ) ) {
					$matched_default_menu[6] = $menu_item[ $menu_item['icon_type'] ];
				}
			}

			if ( ! $menu_item['is_hidden'] && ! empty( $matched_default_menu ) ) {
				$menu_title = $menu_item['title'] ? $menu_item['title'] : ( isset( $matched_default_menu[0] ) ? $matched_default_menu[0] : '' );
				$menu_cap   = isset( $matched_default_menu[1] ) ? $matched_default_menu[1] : '';
				$menu_url   = $menu_item['url'] ? $menu_item['url'] : ( isset( $matched_default_menu[2] ) ? $matched_default_menu[2] : '' );
				$page_title = isset( $matched_default_menu[3] ) ? $matched_default_menu[3] : '';
				$menu_class = $menu_item['class'] ? $menu_item['class'] : ( isset( $matched_default_menu[4] ) ? $matched_default_menu[4] : '' );

				array_push( $new_menu_item, $menu_title );
				array_push( $new_menu_item, $menu_cap );
				array_push( $new_menu_item, $menu_url );
				array_push( $new_menu_item, $page_title );
				array_push( $new_menu_item, $menu_class );

				if ( 'menu' === $menu_item['type'] ) {
					$menu_id   = $menu_item['id'] ? $menu_item['id'] : ( isset( $matched_default_menu[5] ) ? $matched_default_menu[5] : '' );
					$menu_icon = isset( $matched_default_menu[6] ) ? $matched_default_menu[6] : '';

					if ( $menu_item['icon_type'] && $menu_item[ $menu_item['icon_type'] ] ) {
						$menu_icon = $menu_item[ $menu_item['icon_type'] ];
					}

					array_push( $new_menu_item, $menu_id );
					array_push( $new_menu_item, $menu_icon );
				}

				$default_submenu = isset( $submenu[ $menu_url ] ) ? $submenu[ $menu_url ] : array();

				if ( isset( $menu_item['submenu'] ) && ! empty( $menu_item['submenu'] ) ) {
					$custom_submenu       = array();
					$hidden_submenu_items = array();

					foreach ( $menu_item['submenu'] as $submenu_index => $submenu_item ) {
						$submenu_finder_index = 2; // The submenu url.

						/**
						 * In the menu editor, the submenu is taken via ajax.
						 * It makes the Customize submenu (under Appearance menu) url become like this:
						 * customize.php?return=%2Fwp-admin%2Fadmin-ajax.php.
						 *
						 * But in the output, the return url should not be admin-ajax.php.
						 * In the output, the return url should be the current url.
						 */
						if ( 'customize.php?return=%2Fwp-admin%2Fadmin-ajax.php' === $submenu_item['url_default'] ) {
							$current_path  = $_SERVER['REQUEST_URI'];
							$return_path   = rawurlencode( $current_path );
							$customize_url = 'customize.php?return=' . $return_path;

							$submenu_item['url_default'] = $customize_url;
						}

						$default_submenu_index   = $array_helper->find_assoc_array_index_by_value( $default_submenu, $submenu_finder_index, $submenu_item['url_default'] );
						$matched_default_submenu = false !== $default_submenu_index ? $default_submenu[ $default_submenu_index ] : false;

						if ( $submenu_item['was_added'] ) {
							$matched_default_submenu = array(
								$submenu_item['title'],
								'read',
								( $submenu_item['url_default'] ? $submenu_item['url_default'] : '/wp-admin/' ),
								$submenu_item['title'],
								'',
							);
						}

						if ( ! $submenu_item['is_hidden'] ) {
							$new_submenu_item = array();

							$submenu_title = $submenu_item['title'] ? $submenu_item['title'] : ( isset( $matched_default_submenu[0] ) ? $matched_default_submenu[0] : '' );
							array_push( $new_submenu_item, $submenu_title );

							$submenu_cap = isset( $matched_default_submenu[1] ) ? $matched_default_submenu[1] : '';
							array_push( $new_submenu_item, $submenu_cap );

							$submenu_url = $submenu_item['url'] ? $submenu_item['url'] : ( isset( $matched_default_submenu[2] ) ? $matched_default_submenu[2] : '' );
							array_push( $new_submenu_item, $submenu_url );

							$submenu_page_title = isset( $matched_default_submenu[3] ) ? $matched_default_submenu[3] : '';
							array_push( $new_submenu_item, $submenu_page_title );

							$submenu_class = isset( $matched_default_submenu[4] ) ? $matched_default_submenu[4] : '';

							if ( $submenu_class ) {
								array_push( $new_submenu_item, $matched_default_submenu[4] );
							}

							$new_submenu_item['url_default'] = $submenu_item['url_default'];

							if ( ! $submenu_item['was_added'] ) {
								if ( $matched_default_submenu ) {
									array_push( $custom_submenu, $new_submenu_item );
								}
							} else {
								array_push( $custom_submenu, $new_submenu_item );
							}
						} else {
							if ( $matched_default_submenu ) {
								$hidden_submenu_item = $matched_default_submenu;

								$hidden_submenu_item['url_default'] = $submenu_item['url_default'];

								array_push( $hidden_submenu_items, $hidden_submenu_item );
							}
						}
					} // End of foreach $menu_item['submenu'].

					$new_submenu[ $menu_url ] = $custom_submenu;

					if ( ! empty( $hidden_submenu_items ) ) {
						$hidden_submenu[ $menu_url ] = $hidden_submenu_items;
					}
				} // End of checking $menu_item['submenu'].

				array_push( $new_menu, $new_menu_item );
			} else {
				if ( $matched_default_menu ) {
					array_push( $hidden_menu, $matched_default_menu );
				}
			} // End of is_hidden checking.
		} // End of foreach $role_menu.

		$new_menu    = $this->get_new_menu_items( $role, $menu, $new_menu, $hidden_menu );
		$new_submenu = $this->get_new_submenu_items( $role, $submenu, $new_submenu, $hidden_submenu );

		$menu    = $new_menu;
		$submenu = $new_submenu;

	}

	/**
	 * Get new items from menu
	 *
	 * @param string $role The specified role.
	 * @param array  $menu The old menu.
	 * @param array  $custom_menu The custom menu.
	 * @param array  $hidden_menu The hidden menu.
	 *
	 * @return array The modified custom menu.
	 */
	public function get_new_menu_items( $role, $menu, $custom_menu, $hidden_menu ) {
		ksort( $menu );

		$prev_custom_index = 0;

		$array_helper = new Array_Helper();

		foreach ( $menu as $menu_index => $menu_item ) {
			$menu_type = empty( $menu_item[0] ) && empty( $menu_item[3] ) ? 'separator' : 'menu';

			if ( 'separator' === $menu_type ) {
				$menu_finder_index = 2; // The separator url.
			} else {
				$menu_finder_index = 5; // The menu id attribute.
			}

			$custom_menu_index   = $array_helper->find_assoc_array_index_by_value( $custom_menu, $menu_finder_index, $menu_item[ $menu_finder_index ] );
			$matched_custom_menu = false !== $custom_menu_index ? $custom_menu[ $custom_menu_index ] : false;

			$current_custom_index = false !== $custom_menu_index ? $custom_menu_index : $prev_custom_index + 1;
			$prev_custom_index    = $current_custom_index;

			if ( false === $matched_custom_menu ) {
				$hidden_menu_index = $array_helper->find_assoc_array_index_by_value( $hidden_menu, $menu_finder_index, $menu_item[ $menu_finder_index ] );

				if ( false === $hidden_menu_index ) {
					array_splice( $custom_menu, $current_custom_index, 0, array( $menu_item ) );
				}
			}
		}

		return $custom_menu;
	}

	/**
	 * Get new items from submenu
	 *
	 * @param string $role The specified role.
	 * @param array  $submenu The old submenu.
	 * @param array  $custom_submenu The custom submenu.
	 * @param array  $hidden_submenu The hidden submenu.
	 *
	 * @return array The modified custom submenu.
	 */
	public function get_new_submenu_items( $role, $submenu, $custom_submenu, $hidden_submenu ) {
		$array_helper = new Array_Helper();

		foreach ( $submenu as $submenu_key => $submenu_item ) {
			$matched_custom_submenu = isset( $custom_submenu[ $submenu_key ] ) ? $custom_submenu[ $submenu_key ] : false;

			if ( ! $matched_custom_submenu ) {
				if ( ! isset( $hidden_submenu[ $submenu_key ] ) ) {
					$custom_submenu[ $submenu_key ] = $submenu_item;
				}
			} else {
				ksort( $submenu_item );

				$prev_custom_index = 0;

				foreach ( $submenu_item as $submenu_order => $submenu_values ) {
					$submenu_finder_index = 2; // The submenu url.

					$custom_submenu_index = $array_helper->find_assoc_array_index_by_value( $matched_custom_submenu, 'url_default', $submenu_values[ $submenu_finder_index ] );
					$current_custom_index = false !== $custom_submenu_index ? $custom_submenu_index : $prev_custom_index + 1;
					$prev_custom_index    = $current_custom_index;

					if ( false === $custom_submenu_index ) {
						$is_hidden = false;

						if ( isset( $hidden_submenu[ $submenu_key ] ) ) {
							$hidden_submenu_index = $array_helper->find_assoc_array_index_by_value( $hidden_submenu[ $submenu_key ], $submenu_finder_index, $submenu_values[ $submenu_finder_index ] );

							if ( false !== $hidden_submenu_index ) {
								$is_hidden = true;
							}
						}

						if ( ! $is_hidden ) {
							array_splice( $custom_submenu[ $submenu_key ], $current_custom_index, 0, array( $submenu_values ) );
						}
					}
				}
			}
		}

		return $custom_submenu;
	}

}
