<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	<script src="<?php echo get_template_directory_uri(); ?>/style/js/jscolor/jscolor.js" type="text/javascript"></script>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	
<?php 

// WP 5.2 new action hook
do_action( 'wp_body_open' );

if( 'yes' == get_theme_mod( 'show_preloader', 'yes' ) ){
	get_template_part( 'inc/content', 'preloader' );
} 

/**
 * Get header layout by theme option
 * Overrides handled by theme_filters by pre-filtering the theme_mod call
 * 
 * Elementor Pro Compatibility
 * 
 * Checks first that Elementor Pro is functioning, 
 * then checks if a custom header has been set.
 * 
 * If neither of these are true, we fall back to the default theme header.
 */
if ( !function_exists( 'elementor_theme_do_location' ) || !elementor_theme_do_location( 'header' ) ){
	get_template_part( 'inc/layout-header', get_theme_mod( 'header_layout', 'white' ) ); 
}