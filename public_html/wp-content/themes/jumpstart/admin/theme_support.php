<?php

/**
 * tommusrhodus_add_editor_styles()
 * 
 * Add actions to init, editor styles, content width, post type support.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_add_editor_styles' ) )){
	function tommusrhodus_add_editor_styles() {
	    
	    add_editor_style( 'admin/editor-style.css' );
	    
	    /**
	     * Set Content Width
	     */
	    global $content_width;
	    if ( ! isset( $content_width ) ){
	    	$content_width = 1170;
	    }
	    
	}
	add_action( 'init', 'tommusrhodus_add_editor_styles', 10 );
}

/**
 * tommusrhodus_add_theme_support()
 * 
 * Add theme support items for the theme setup.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_add_theme_support' ) )){
	function tommusrhodus_add_theme_support() {
		
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'product', 'excerpt' );
		 
		/**
		 * Add custom image size support
		 */
	 	add_image_size( 'jumpstart-card-top', 768, 528, true );
	 	
		/**
		 * Add post thumbnail (featured image) support
		 */
		add_theme_support( 'post-thumbnails' );
		
		/**
		 * Custom Logo support
		 */
		add_theme_support( 'custom-logo' );

		/**
		 * Add Custom Background Support and Set Default
		 */
		add_theme_support( 'custom-background', array( 'default-color' => '#e9ecef' ) );
		
		/**
		 * Add feed link support
		 */
		add_theme_support( 'automatic-feed-links' );
		
		/**
		 * Add html5 support
		 */
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		
		/**
		 * Load Translation Files
		 */
		load_theme_textdomain( 'jumpstart', trailingslashit( get_template_directory() ) . 'languages' );
		
		/**
		 * Title tag support
		 */
		add_theme_support( 'title-tag' );
		
		/**
		 * Allow widgets in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		/**
		 * Gutenberg
		 */
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		
		/**
		 * WooCommerce Support
		 */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
	}
	add_action( 'after_setup_theme', 'tommusrhodus_add_theme_support', 10 );
}