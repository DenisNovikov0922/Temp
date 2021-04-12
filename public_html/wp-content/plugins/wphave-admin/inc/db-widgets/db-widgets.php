<?php

/*
 *******************
 * INCLUDE DASHBOARD WIDGETS
 *******************
 *
 *	Add different dashboard widgets to WordPress admin.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

/****************
* USER ACTIVITIES
****************/

if( ! wphave_option('dbw_wpat_user_log') ) { 
    include wphave_admin_dir( 'inc/db-widgets/db-widget-user.php' );
}

/****************
* SYSTEM INFO
****************/

if( ! wphave_option('disable_page_system') && ! wphave_option('dbw_wpat_sys_info') ) { 
    include wphave_admin_dir( 'inc/db-widgets/db-widget-system.php' );
}

/****************
* RECENT POSTS
****************/

if( ! wphave_option('dbw_wpat_recent_post') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-recent-posts.php' );
}

/****************
* RECENT PAGES
****************/

if( ! wphave_option('dbw_wpat_recent_page') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-recent-pages.php' );
}

/****************
* RECENT COMMENTS
****************/

if( ! wphave_option('dbw_wpat_recent_comment') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-recent-comments.php' );
}

/****************
* POST COUNTS
****************/

if( ! wphave_option('dbw_wpat_count_post') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-count-posts.php' );
}
    
/****************
* PAGE COUNTS
****************/

if( ! wphave_option('dbw_wpat_count_page') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-count-pages.php' );
}
    
/****************
* COMMENT COUNTS
****************/

if( ! wphave_option('dbw_wpat_count_comment') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-count-comments.php' );
}
    
/****************
* MEMORY USAGE
****************/

if( ! wphave_option('dbw_wpat_memory') ) {
    include wphave_admin_dir( 'inc/db-widgets/db-widget-memory.php' );
}


/*
 *******************
 * RECIPE DASHBOARD WIDGETS
 *******************
 *
 *	Include recipe db widgets only, if the recipe post type exist.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_post_type_recipe' ) ) :

	function wphave_admin_post_type_recipe() {

		if( ! post_type_exists('recipe') ) {
			// Stop here, if post type not exist
			return;	
		}
			
		/****************
		* RECENT RECIPES
		****************/

		include wphave_admin_dir( 'inc/db-widgets/db-widget-recent-recipes.php' );

		/****************
		* RECIPE COUNTS
		****************/

		include wphave_admin_dir( 'inc/db-widgets/db-widget-count-recipes.php' );
		
	}

endif;

add_action( 'admin_init', 'wphave_admin_post_type_recipe', 30 );


/*
 *******************
 * SEARCH ENGINE VISIBILITY WARNING
 *******************
 *
 *	Show this db widget, if the search engine visibility is hidden.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

$visibility = get_option( 'blog_public' );
if( is_multisite() ) {
    $visibility = get_blog_option( get_current_blog_id(), 'blog_public', array() );
}

if( 0 == $visibility ) {
	
	/****************
	* SEARCH ENGINE VISIBILITY WARNING
	****************/
	
    include wphave_admin_dir( 'inc/db-widgets/db-widget-search-engine-notice.php' );
}