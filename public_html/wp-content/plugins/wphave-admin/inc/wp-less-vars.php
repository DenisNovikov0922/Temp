<?php 

/*
 *******************
 * SET WP LESS VARS
 *******************
 *
 *	Define different variables for parsing with wpless.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_wp_less_vars' ) ) :

	function wphave_admin_wp_less_vars( $vars, $handle ) {

		// Get pre options
		$pre_option = new wphave_admin_settings();
		
		$vars[ 'wpatThemeColor' ] = wphave_option('theme_color');
		if( ! wphave_option('theme_color') ) {
			$vars[ 'wpatThemeColor' ] = $pre_option->pre_options['theme_color'];
		}
		
		$vars[ 'wpatGradientStartColor' ] = wphave_admin_hex2rgba( wphave_option('theme_background') );
		if( ! wphave_option('theme_background') ) {
			$vars[ 'wpatGradientStartColor' ] = wphave_admin_hex2rgba( $pre_option->pre_options['theme_background'] );
		}
		
		$vars[ 'wpatGradientEndColor' ] = wphave_admin_hex2rgba( wphave_option('theme_background_end') );
		if( ! wphave_option('theme_background_end') ) {
			$vars[ 'wpatGradientEndColor' ] = wphave_admin_hex2rgba( $pre_option->pre_options['theme_background_end'] );
		}
		
		$vars[ 'wpatToolbarColor' ] = wphave_option('toolbar_color');
		if( ! wphave_option('toolbar_color') ) {
			$vars[ 'wpatToolbarColor' ] = $pre_option->pre_options['toolbar_color'];
		}
		
		$vars[ 'wpatSpacingMaxWidth' ] = wphave_option('spacing_max_width') . 'px';
		if( ! wphave_option('spacing_max_width') ) {
			$vars[ 'wpatSpacingMaxWidth' ] = $pre_option->pre_options['spacing_max_width'] . 'px';
		}
		
		$vars[ 'wpatMenuLeftWidth' ] = wphave_option('left_menu_width') . 'px';
		if( ! wphave_option('left_menu_width') ) {
			$vars[ 'wpatMenuLeftWidth' ] = $pre_option->pre_options['left_menu_width'] . 'px';
		}
		
		$vars[ 'wpatMenuLeftWidthDiff' ] = wphave_option('left_menu_width') - 40 . 'px';
		if( ! wphave_option('left_menu_width') ) {
			$vars[ 'wpatMenuLeftWidthDiff' ] = $pre_option->pre_options['left_menu_width'] - 40 . 'px';
		}
		
		$vars[ 'wpatLoginLogoSize' ] = wphave_option('logo_size') . 'px';
		if( ! wphave_option('logo_size') ) {
			$vars[ 'wpatLoginLogoSize' ] = $pre_option->pre_options['logo_size'] . 'px';
		}

		$vars[ 'wpatToolbarIcon' ] = 'none';
		if( wphave_option('toolbar_icon') != '' ) {
			$vars[ 'wpatToolbarIcon' ] = 'url(' . wphave_option('toolbar_icon') . ')';
		}

		$vars[ 'wpatWebFont' ] = 'none';
		if( wphave_option('google_webfont') != '' ) {
			$web_font = str_replace( '+', ' ', esc_html( wphave_option('google_webfont') ) );
			$vars[ 'wpatWebFont' ] = '"' . $web_font . '"';
		}

		$vars[ 'wpatLoginBg' ] = 'none';
		if( wphave_option('login_bg') != '' ) {
			$vars[ 'wpatLoginBg' ] = 'url(' . wphave_option('login_bg') . ')';
		}

		$vars[ 'wpatLoginLogo' ] = 'none';
		if( wphave_option('logo_upload') != '' ) {
			$vars[ 'wpatLoginLogo' ] = 'url(' . wphave_option('logo_upload') . ')';
		}

		return $vars;

	}

endif;

add_filter( 'less_vars', 'wphave_admin_wp_less_vars', 999, 2 );


/*
 *******************
 * LOAD WP LESS
 *******************
 *
 *	Load filter only in special cases.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

/*

if ( ! function_exists( 'wphave_admin_load_wpless' ) ) :

	function wphave_admin_load_wpless() {

		// Function to check if is wp login or wp register page
		$is_login = wphave_admin_is_login_page();

		global $pagenow;
		
		// Load only on admin_toplevel_page?page=mypluginname
		$is_wphave_option_page = ( $pagenow === 'tools.php' ) && ( $_GET['page'] === 'wphave-admin' );
		
		// Load filter only in above cases
		if( $is_login || is_admin() && $is_wphave_option_page ) {
			add_filter( 'less_vars', 'wphave_admin_wp_less_vars', 999, 2 );
		}

	}

endif;

add_action( 'init', 'wphave_admin_load_wpless' );

*/

/*
 *******************
 * DELETE WPLESS CACHE AFTER PLUGIN DEACTIVATION
 *******************
 *
 *	Action to delete the wpless option cache after deactivate this plugin.
 *	This is neccessary, to refresh the less variables stored in the option cache.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	2.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
/*
function wphave_admin_delete_wp_less_cache_after_plugin_deactivation() {
	
	// Check current user can manage plugins
    if( ! current_user_can( 'activate_plugins' ) ) {
		// Stop here, if that's not the case
		return;
	}
        
	// Check for WP plugin request
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	
	// Check for WP admin referer
	check_admin_referer( "deactivate-plugin_{$plugin}" );
	
	// Delete existing wpless option cache, to start recreating the cache after next page load
	delete_option( 'wp_less_cached_files' );
	
}

register_deactivation_hook( WPHAVE_ADMIN_PLUGIN, 'wphave_admin_delete_wp_less_cache_after_plugin_deactivation' );
*/