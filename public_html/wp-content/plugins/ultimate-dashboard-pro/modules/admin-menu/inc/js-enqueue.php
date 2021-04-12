<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_admin_menu() ) {

		wp_dequeue_script( 'udb-admin-menu' );
		wp_deregister_script( 'udb-admin-menu' );

		// Admin menu.
		wp_enqueue_script( 'udb-admin-menu', $module->url . '/assets/js/admin-menu.js', array( 'jquery', 'dashicons-picker', 'jquery-ui-sortable' ), ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION, true );

		$wp_roles   = wp_roles();
		$role_names = $wp_roles->role_names;
		$roles      = array();

		foreach ( $role_names as $role_key => $role_name ) {
			array_push(
				$roles,
				array(
					'key'  => $role_key,
					'name' => $role_name,
				)
			);
		}

		$admin_menu_data = array(
			'nonces'    => array(
				'getMenu'   => wp_create_nonce( 'udb_admin_menu_get_menu' ),
				'getUsers'  => wp_create_nonce( 'udb_admin_menu_get_users' ),
				'resetMenu' => wp_create_nonce( 'udb_admin_menu_reset_menu' ),
				'saveMenu'  => wp_create_nonce( 'udb_admin_menu_save_menu' ),
			),
			'roles'     => $roles,
			'templates' => array(
				'menuList'       => require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/templates/menu-list.php',
				'submenuList'    => require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/templates/submenu-list.php',
				'menuSeparator'  => require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/templates/menu-separator.php',

				'userTabMenu'    => '',
				'userTabContent' => '',
			),
		);

		// Ultimate Dashboard (free version) v3.1.3 and below doesn't have "user-tab-menu.php" and "user-tab-content.php".
		if ( version_compare( ULTIMATE_DASHBOARD_PLUGIN_VERSION, '3.1.3', '>' ) ) {
			$admin_menu_data['templates']['userTabMenu']    = require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/templates/user-tab-menu.php';
			$admin_menu_data['templates']['userTabContent'] = require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-menu/templates/user-tab-content.php';
		}

		$admin_menu_data = apply_filters( 'udb_admin_menu_js_object', $admin_menu_data );

		wp_localize_script(
			'udb-admin-menu',
			'udbAdminMenu',
			$admin_menu_data
		);

	}

};
