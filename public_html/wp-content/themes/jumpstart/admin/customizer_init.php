<?php 

function tommusrhodus_customizer_live_preview(){

	wp_enqueue_script( 
		  'jumpstart-customizer',
		  get_theme_file_uri( '/style/js/theme-customizer.js' ),
		  array( 'jquery','customize-preview' ),
		  '1.0.0',
		  true
	);
	
}
add_action( 'customize_preview_init', 'tommusrhodus_customizer_live_preview', 10 );

function tommusrhodus_customizer_controls_scripts() {
   
   wp_enqueue_script( 
   	  'jumpstart-customizer-controls',
   	  get_theme_file_uri( '/style/js/theme-customizer-controls.js' ),
   	  array( 'jquery', 'customize-controls' ),
   	  '1.0.0',
   	  true
   );
   
	/**
	 * Send in post type URLs
	 * 
	 * @note URL must be trailing-slashed!
	 */
	$script_array = array( 
		'blog_url'          => esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
		'documentation_url' => esc_url( trailingslashit( home_url( get_option( 'documentation_post_type_slug', 'documentation' ) ) ) ),
		'portfolio_url'     => esc_url( trailingslashit( home_url( get_option( 'portfolio_post_type_slug', 'portfolio' ) ) ) )
	);
	wp_localize_script( 'jumpstart-customizer-controls', 'jumpstart_data', $script_array );
   
}
add_action( 'customize_controls_enqueue_scripts', 'tommusrhodus_customizer_controls_scripts', 10 );

function tommusrhodus_set_transport( $wp_customize ){	
	
	// Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }
	    
	// Blog Title
	$wp_customize->selective_refresh->add_partial( 
		'blog-title', 
		array(
		    'selector'        => '[data-theme-mod="post_archive_title"]',
		    'settings'        => array( 'post_archive_title' ),
	        'render_callback' => function() {
	            return get_theme_mod( 'post_archive_title' );
	        },
		) 
	);
	
	// Portfolio Title
	$wp_customize->selective_refresh->add_partial( 
		'portfolio-title', 
		array(
		    'selector'        => '[data-theme-mod="portfolio_archive_title"]',
		    'settings'        => array( 'portfolio_archive_title' ),
	        'render_callback' => function() {
	            return get_theme_mod( 'portfolio_archive_title' );
	        },
		) 
	);
	
	// Blog Image
	$wp_customize->selective_refresh->add_partial( 
		'blog-header-image', 
		array(
		    'selector'        => '[data-theme-mod="post_archive_background_image"]',
		    'settings'        => array( 'post_archive_background_image' ),
	        'render_callback' => function() {
		        return get_theme_mod( 'post_archive_background_image' );
	        },
		) 
	);

}
add_action( 'customize_register', 'tommusrhodus_set_transport', 20 );
