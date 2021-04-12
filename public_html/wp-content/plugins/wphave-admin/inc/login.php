<?php

/*
 *******************
 * ADD LOGIN BODY CLASSES
 *******************
 *
 *	Add custom classes to the login body only.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_login_body_class' ) ) :

	function wphave_admin_login_body_class( $classes ) {
		
		if( wphave_option('login_bg') ) {
			$classes[] = 'wpat-login-bg';
		}
		
		if( wphave_option('logo_upload') ) {
			$classes[] = 'wpat-login-logo';
		}
		
		if( wphave_option('logo_size') ) {
			$classes[] = 'wpat-login-logo-size';
		}
	
		return $classes;

	}

endif;

add_filter( 'login_body_class', 'wphave_admin_login_body_class' );


/*
 *******************
 * LOGIN PAGE CHECK
 *******************
 *
 *	Check if the current page is a login page.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_is_login_page' ) ) :

	function wphave_admin_is_login_page() {
		
		// Check for WP login + WP register page
		return in_array( $GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php') );
		
	}

endif;


/*
 *******************
 * INCLUDE LOGIN ASSETS
 *******************
 *
 *	Enqueue login assents for WordPress login page only.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_login_assets' ) ) :

	function wphave_admin_login_assets() {

		if( wphave_option('login_disable') ) {
			// Stop here, if disabled
			return;
		}

		// Add custom user css for wp login
		if( wphave_option('css_login') ) {
			wp_enqueue_style( 'wphave-admin-login-custom', wphave_admin_path('assets/css/login.css'), array(), filemtime( wphave_admin_dir('assets/css/login.css') ), 'all' );
		}

		wp_enqueue_style( 
			'wphave-admin-login',  wphave_admin_path( 'assets/css/less/login.less' ), array(), null, 'all'
		);			

	}

endif;

add_action('login_enqueue_scripts', 'wphave_admin_login_assets');


/*
 *******************
 * LOGIN LOGO URL
 *******************
 *
 *	Change the url of the login page logo.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_login_logo_url' ) ) :

	function wphave_admin_login_logo_url() {

		if( wphave_option('login_disable') ) {
			// Stop here, if disabled
			return;
		}

		return home_url();
	}

endif;

add_filter( 'login_headerurl', 'wphave_admin_login_logo_url' );


/*
 *******************
 * LOGIN MESSAGE
 *******************
 *
 *	Change the message of the login page.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_login_message' ) ) :

	function wphave_admin_login_message( $message ) {

		if( wphave_option('login_disable') && ! wphave_option('login_title') ) {
			// Stop here, if disabled
			return $message;
		}

		if( empty( $message ) ){
			return '<div class="login-message">' . esc_html( wphave_option('login_title') ) . '</div>';
		}

		return $message;

	}

endif;

add_filter( 'login_message', 'wphave_admin_login_message' );