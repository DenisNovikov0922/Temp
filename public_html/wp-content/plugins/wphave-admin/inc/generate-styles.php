<?php 

/*
 *******************
 * GENERATE USER ADMIN STYLES
 *******************
 *
 *	Create an user generated stylesheet for WordPress admin only.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_generate_admin_css') ) :	

	function wphave_admin_generate_admin_css() {

		if( empty( wphave_option('css_admin') ) ) {
			// Stop if no custom admin css is entered
			return;
		}

		global $wp_filesystem;
		WP_Filesystem(); // Initial WP file system

		// Add custom user css for wp admin
		ob_start();
		require_once( wphave_admin_dir('assets/css/admin.php') );
		$css = ob_get_clean();
		$wp_filesystem->put_contents( wphave_admin_dir('assets/css/admin.css'), $css, 0644 );

	}

endif;		
	
add_action( 'admin_init', 'wphave_admin_generate_admin_css' );


/*
 *******************
 * GENERATE USER LOGIN STYLES
 *******************
 *
 *	Create an user generated stylesheet for WordPress login only.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_generate_login_css') ) :
	
	function wphave_admin_generate_login_css() {

		if( empty( wphave_option('css_login') ) ) {
			// Stop if no custom login css is entered
			return;
		}

		global $wp_filesystem;
		WP_Filesystem(); // Initial WP file system

		// Add custom user css for wp login
		ob_start();
		require_once( wphave_admin_dir('assets/css/login.php') );
		$css = ob_get_clean();
		$wp_filesystem->put_contents( wphave_admin_dir('assets/css/login.css'), $css, 0644 );

	}

endif;	

add_action( 'admin_init', 'wphave_admin_generate_login_css' );