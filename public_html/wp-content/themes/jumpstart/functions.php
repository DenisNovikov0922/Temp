<?php 

/**
 * Queue up all admin theme files.
 * Filters and functions etc. are logically split for easier management.
 */
require_once get_parent_theme_file_path( '/admin/theme_icons.php' );
require_once get_parent_theme_file_path( '/admin/theme_layouts.php' );
require_once get_parent_theme_file_path( '/admin/theme_functions.php' );
require_once get_parent_theme_file_path( '/admin/theme_filters.php' );
require_once get_parent_theme_file_path( '/admin/theme_actions.php' );
require_once get_parent_theme_file_path( '/admin/theme_menus_widgets.php' );
require_once get_parent_theme_file_path( '/admin/theme_support.php' );
require_once get_parent_theme_file_path( '/admin/theme_scripts.php' );
require_once get_parent_theme_file_path( '/admin/tr_framework_init.php' );
require_once get_parent_theme_file_path( '/admin/customizer_init.php' );

/**
 * Some parts of the framework only need to run on admin views.
 * These would be those.
 * Calling these only on admin saves some operation time for the theme, everything in the name of speed.
 */
if( is_admin() ){

	if (!( class_exists( 'TGM_Plugin_Activation' ) )){
		require_once get_parent_theme_file_path( '/admin/class-tgm-plugin-activation.php' );
	}
		
	require_once get_parent_theme_file_path( '/admin/theme_metaboxes.php' );
	
	/**
	 * Configure Merlin - The theme auto starter
	 */
	require_once get_parent_theme_file_path( '/admin/merlin_config.php' );
	
}

/*function tommusrhodus_disable_post_types( $args ){

	unset( $args['post_types']['portfolio'] );
	unset( $args['post_types']['team'] );
	unset( $args['post_types']['documentation'] );
	
	unset( $args['taxonomy_types']['portfolio_category'] );
	unset( $args['taxonomy_types']['team_category'] );
	unset( $args['taxonomy_types']['documentation_category'] );
	
	return $args;
	
}
add_filter( 'tommusrhodus_framework_theme_args', 'tommusrhodus_disable_post_types', 10, 1 );*/

/*
Example function to add more icons

function example_add_icons( $icons ){
	
	$icons[ 'Custom Arrow' ] = '<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12L12 19L19 12M12 5V18V5Z" stroke="#2C3038" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	
	return $icons;
}
add_filter( 'tommusrhodus_add_svg_icons', 'example_add_icons', 10, 1 );

*/

function example_add_icons( $icons ){
    
    $icons[ 'Easy Delivery 1' ] = '<img src="http://homepage.staging-ed.it/wp-content/uploads/sites/3/2021/03/easy-delivery-1.png" width="100px" height="100px">';
    $icons[ 'Easy Delivery 2' ] = '<img src="http://homepage.staging-ed.it/wp-content/uploads/sites/3/2021/03/easy-delivery-2-1.png" width="100px" height="100px">';
    $icons[ 'Easy Delivery 3' ] = '<img src="http://homepage.staging-ed.it/wp-content/uploads/sites/3/2021/03/easy-delivery-3-2.png" width="100px" height="100px">';
    $icons[ 'Easy Delivery 4' ] = '<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12L12 19L19 12M12 5V18V5Z" stroke="#2C3038" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
    
    return $icons;
}
add_filter( 'tommusrhodus_add_svg_icons', 'example_add_icons', 10, 1 );