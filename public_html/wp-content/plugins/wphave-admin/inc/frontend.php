<?php

/*
 *******************
 * ADD FRONTEND BODY CLASSES
 *******************
 *
 *	Add custom classes to the frontend body only.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_frontend_body_class' ) ) :

	function wphave_admin_frontend_body_class( $classes ) {
		
		// Remove wp toolbar icon
		if( wphave_option('toolbar_wp_icon') ) {
			$classes[] = 'wpat-wp-toolbar-icon-remove';
		}
		
		// Custom toolbar icon
		if( wphave_option('toolbar_icon') ) {
			$classes[] = 'wpat-toolbar-icon';
		}
	
		return $classes;

	}

endif;

add_filter( 'body_class', 'wphave_admin_frontend_body_class' );


/*
 *******************
 * ADD FRONTEND ASSETS
 *******************
 *
 *	Enqueue assets for frontend only.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_frontend_assets' ) ) :

	function wphave_admin_frontend_assets() {
		
		if( ! is_user_logged_in() ) {
			return;
		}
			
		wp_enqueue_style( 
			'wphave-admin-frontend', wphave_admin_path( 'assets/css/frontend.css' ), array(), null, 'all'
		);

		/*
		 *******************
		 * FRONTEND STYLE (DUMMY)
		 *******************
		 *
		 *	Handle for using inline styles.
		 *  ! Notice: Adding a dummy handler is required to assign an inline styles (without dependency) when merging styles into a file.
		*/

		wp_register_style( 'wphave-admin-frontend', false );
		wp_enqueue_style( 'wphave-admin-frontend' );		

		// Add custom toolbar wp icon for frontend adminbar
		if( wphave_option('toolbar_icon') ) {
			$toolbar_wp_icon = "body.wpat-toolbar-icon #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before { background-image: url(" . wphave_option('toolbar_icon') . ") }";
			wp_add_inline_style( 'wphave-admin-frontend', $toolbar_wp_icon );
		}
		
	}

endif;

add_action( 'wp_enqueue_scripts', 'wphave_admin_frontend_assets' );