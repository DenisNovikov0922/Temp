<?php

/*
 *******************
 * REMOVE USER THEME OPTION
 *******************
 *
 *	Remove the user theme option.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_remove_user_theme_option' ) ) :

	function wphave_admin_remove_user_theme_option() {
        
		global $_wp_admin_css_colors;

		// Get fresh color data 
		$fresh_color_data = $_wp_admin_css_colors['fresh'];

		// Remove everything else
		$_wp_admin_css_colors = array( 'fresh' => $fresh_color_data );
	}

endif;

add_action( 'admin_init', 'wphave_admin_remove_user_theme_option', 1 );


/*
 *******************
 * SET USER THEME OPTION
 *******************
 *
 *	Set the user theme option to default.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
 
if ( ! function_exists( 'wphave_admin_user_theme_default' ) ) :

	function wphave_admin_user_theme_default( $color ){
		return 'fresh';
	}

endif; 

add_filter( 'get_user_option_admin_color', 'wphave_admin_user_theme_default' );


/*
 *******************
 * CHANGE / REMOVE WP "HOWDY"
 *******************
 *
 *	This filter allows you to change the WordPress default "Howdy" greeting.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_howdy' ) ) : 

	function wphave_admin_howdy( $wp_admin_bar ) {
		
		$disable_greeting = wphave_option('howdy_greeting');
		$custom_greeting = wphave_option('howdy_greeting_text');
		
		if( $disable_greeting || $custom_greeting ) {
			
			$greeting = '';
			if( ! $disable_greeting && $custom_greeting ) {
				$greeting = $custom_greeting . ',';
			}
			
			$my_account = $wp_admin_bar->get_node('my-account');
			$newtext = str_replace( 'Howdy,', $greeting, $my_account->title );
			$wp_admin_bar->add_node( array(
				'id' => 'my-account',
				'title' => $newtext,
			) );
			
		}
		
	}

endif; 

add_filter( 'admin_bar_menu', 'wphave_admin_howdy', 25 );


/*
 *******************
 * SVG SUPPORT
 *******************
 *
 *	Allow the upload of SVG files.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_svg_support' ) ) : 

	function wphave_admin_svg_support( $svg_mime ) {
		
		if( ! wphave_option('wp_svg') ) {
			// Stop here, if disabled
			return $svg_mime;
		}
		
		if( function_exists( 'current_user_can' ) ) {
			if( ! current_user_can( 'manage_options' ) ) {
				// Only allowed for a user with admin privileges
				return;
			}
		}
		
		// ! Notice: Only works if the SVG file in the first line of the file contains "</?xml version="1.0" encoding="utf-8"?/>"
		$svg_mime['svg'] = 'image/svg+xml';		
		return $svg_mime;
	}

endif;

add_filter('upload_mimes', 'wphave_admin_svg_support', 10, 4);
	

/*
 *******************
 * ICO SUPPORT
 *******************
 *
 *	Allow the upload of ICO files.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
	
if ( ! function_exists( 'wphave_admin_ico_support' ) ) : 

	function wphave_admin_ico_support( $ico_mime ) {

		if( ! wphave_option('wp_ico') ) {
			// Stop here, if disabled
			return $ico_mime;
		}

		$ico_mime['ico'] = 'image/x-icon';
		return $ico_mime;
	}

endif;

add_filter('upload_mimes', 'wphave_admin_ico_support', 10, 5);


/*
 *******************
 * UPLOAD MIMETYPE FIX
 *******************
 *
 *	Allow the upload of resctricted files.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
	
/* if ( ! function_exists( 'wphave_admin_mimetype_fix' ) ) : 

	function wphave_admin_mimetype_fix( $data, $file, $filename, $mimes ) {

		if( ! wphave_option('wp_svg') || ! wphave_option('wp_ico') ) {
			// Stop here, if disabled
			return;
		}			

		$wp_filetype = wp_check_filetype( $filename, $mimes );	
		$ext = $wp_filetype['ext'];
		$type = $wp_filetype['type'];
		$proper_filename = $data['proper_filename'];
		return compact( 'ext', 'type', 'proper_filename' );
	}	

endif;

add_filter( 'wp_check_filetype_and_ext', 'wphave_admin_mimetype_fix', 10, 4 ); */


/*
 *******************
 * WP VERSION TAG
 *******************
 *
 *	Remove the WordPress version meta tag on frontend.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_version_tag') ) {

	remove_action('wp_head', 'wp_generator');

}


/*
 *******************
 * REMOVE WP EMOTICONS
 *******************
 *
 *	Remove the WordPress default emoticons.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_emoji') ) {

	if ( ! function_exists( 'wphave_admin_remove_emoji' ) ) : 
	
		function wphave_admin_remove_emoji() {
			remove_action('wp_head', 'print_emoji_detection_script', 7);
			remove_action('admin_print_scripts', 'print_emoji_detection_script');
			remove_action('admin_print_styles', 'print_emoji_styles');
			remove_action('wp_print_styles', 'print_emoji_styles');
			remove_filter('the_content_feed', 'wp_staticize_emoji');
			remove_filter('comment_text_rss', 'wp_staticize_emoji');
			remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
			add_filter('tiny_mce_plugins', 'wphave_admin_remove_tinymce_emoji');
		}
	
	endif;

	add_action('init', 'wphave_admin_remove_emoji');

	if ( ! function_exists( 'wphave_admin_remove_tinymce_emoji' ) ) : 
	
		function wphave_admin_remove_tinymce_emoji( $plugins ) {
			if( ! is_array( $plugins ) ) {
				return array();
			}
			return array_diff( $plugins, array( 'wpemoji' ) );
		}
	
	endif;

}


/*
 *******************
 * REMOVE RSS FEED LINKS
 *******************
 *
 *	Remove the WordPress default rss feed functionality.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_feed_links') ) {
	
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	
	if ( ! function_exists( 'wphave_admin_disable_rss' ) ) : 
	
		function wphave_admin_disable_rss() {
			wp_die( 
				esc_html__( 'No feed available, please visit our', 'wphave-admin' ) . ' <a href="'. esc_url( home_url( '/' ) ) .'">' . esc_html__( 'homepage', 'wphave-admin' ) . '</a>!'
			);
		}
	
	endif;

	add_action('do_feed', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_rdf', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_rss', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_rss2', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_atom', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_rss2_comments', 'wphave_admin_disable_rss', 1);
	add_action('do_feed_atom_comments', 'wphave_admin_disable_rss', 1);
		
}

/*
 *******************
 * REMOVE RSD LINK
 *******************
 *
 *	Remove the WordPress default rsd link.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_rsd_link') ) {
	
	remove_action('wp_head', 'rsd_link');
	
}


/*
 *******************
 * REMOVE WLWMANIFEST LINK
 *******************
 *
 *	Remove the WordPress default wlwmanifest link.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_wlwmanifest') ) {
	
	remove_action('wp_head', 'wlwmanifest_link');
	
}


/*
 *******************
 * REMOVE SHORTLINK
 *******************
 *
 *	Remove the WordPress default shortlinks.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_shortlink') ) {
	
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
}


/*
 *******************
 * DISABLE REST API
 *******************
 *
 *	Disable the WordPress default rest api.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_rest_api') ) {
	
	remove_action('wp_head','rest_output_link_wp_head',10);
	add_filter('rest_enabled','_return_false');
	add_filter('rest_jsonp_enabled','_return_false'); 
	
}


/*
 *******************
 * DISABLE oEMBED
 *******************
 *
 *	Disable the WordPress default oEMBED.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_oembed') ) {

	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	
	if ( ! function_exists( 'wphave_admin_block_wp_embed' ) ) : 
	
		function wphave_admin_block_wp_embed() {
			wp_deregister_script('wp-embed'); 
		}

    endif;

	add_action('init', 'wphave_admin_block_wp_embed');

}


/*
 *******************
 * DISABLE XML-RPC
 *******************
 *
 *	Disable the WordPress default XML-RPC.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_xml_rpc') ) {
	
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	if ( ! function_exists( 'wphave_admin_remove_x_pingback' ) ) : 
	
		function wphave_admin_remove_x_pingback( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}

    endif;

	add_filter( 'wp_headers', 'wphave_admin_remove_x_pingback' );

}


/*
 *******************
 * STOP WP HEARTBEA
 *******************
 *
 *	Disable the WordPress default heartbeat.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/


if( wphave_option('wp_heartbeat') ) {
	
	if ( ! function_exists( 'wphave_admin_stop_heartbeat' ) ) : 
	
		function wphave_admin_stop_heartbeat() {
			wp_deregister_script('heartbeat');
		}

    endif;

	add_action('init', 'wphave_admin_stop_heartbeat', 1);

}


/*
 *******************
 * REMOVE REL LINKS PREV/NEXT
 *******************
 *
 *	Remove the WordPress default prev/next links.
 *
 *  @type	remove_action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_rel_link') ) {
	
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'index_rel_link');
	
}


/*
 *******************
 * DISABLE SELF PINGBACKS
 *******************
 *
 *	Disable the WordPress default self pingbacks.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_self_pingback') ) {
    
	if ( ! function_exists( 'wphave_admin_disable_self_pingback' ) ) : 
	
		function wphave_admin_disable_self_pingback( &$links ) {
			$home = get_option( 'home' );
			foreach( $links as $l => $link ) {
				if( 0 === strpos( $link, $home ) ) {
					unset($links[$l]);  
				}
			}
		}

    endif;

    add_action( 'pre_ping', 'wphave_admin_disable_self_pingback' );

}  


/*
 *******************
 * REFERRER POLICY META TAG
 *******************
 *
 *	Set and modify the referrer policy meta tag.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('meta_referrer_policy') && wphave_option('meta_referrer_policy') != 'none' ) {

    if ( ! function_exists( 'wphave_admin_meta_referrer_policy' ) ) : 

        function wphave_admin_meta_referrer_policy() {
            echo '<meta name="referrer" content="' . wphave_option('meta_referrer_policy') . '">';
        }

    endif;

    add_action('wp_head', 'wphave_admin_meta_referrer_policy');

}


/*
 *******************
 * WP HEAD CODE
 *******************
 *
 *	Add custom code the wphead.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_header_code') ) {

    if ( ! function_exists( 'wphave_admin_add_code_to_wphead' ) ) :

        function wphave_admin_add_code_to_wphead() {
            echo wphave_option('wp_header_code');
        }

    endif;

    add_action( 'wp_head', 'wphave_admin_add_code_to_wphead' );
    
}


/*
 *******************
 * WP FOOTER CODE
 *******************
 *
 *	Add custom code the wpfooter.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('wp_footer_code') ) {
    
    if ( ! function_exists( 'wphave_admin_add_code_to_wpfooter' ) ) :

        function wphave_admin_add_code_to_wpfooter() {
            echo wphave_option('wp_footer_code');
        }

    endif;
	
    add_action( 'wp_footer', 'wphave_admin_add_code_to_wpfooter', 999 );
    
}

 
/*
 *******************
 * REMOVE WP ADMIN META BOX
 *******************
 *
 *	Remove different meta boxes from WordPress admin.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_remove_metaboxes') ) :

    function wphave_admin_remove_metaboxes() {
        
        if( wphave_option('mb_custom_fields') ) {    
            remove_meta_box( 'postcustom', '', 'normal' );	
        }

        if( wphave_option('mb_commentstatus') ) {  
            remove_meta_box( 'commentstatusdiv', '', 'normal' );
        }

        if( wphave_option('mb_comments') ) {  
            remove_meta_box( 'commentsdiv', '', 'normal' );
        }

        if( wphave_option('mb_author') ) {  
            remove_meta_box( 'authordiv', '', 'normal' );
        }

        if( wphave_option('mb_category') ) {  
            remove_meta_box( 'categorydiv', '', 'side' );
        }

        if( wphave_option('mb_format') ) {  
            remove_meta_box( 'formatdiv', '', 'side' );
        }

        if( wphave_option('mb_pageparent') ) {  
            remove_meta_box( 'pageparentdiv', '', 'side' );
        }

        if( wphave_option('mb_postexcerpt') ) {  
            remove_meta_box( 'postexcerpt', '', 'normal' );
        }

        if( wphave_option('mb_postimage') ) {  
            remove_meta_box( 'postimagediv', '', 'side' );
        }

        if( wphave_option('mb_revisions') ) {  
            remove_meta_box( 'revisionsdiv', '', 'normal' );
        }

        if( wphave_option('mb_slug') ) {  
            remove_meta_box( 'slugdiv', '', 'normal' );
        }

        if( wphave_option('mb_tags') ) {  
            remove_meta_box( 'tagsdiv-post_tag', '', 'side' );
        }

        if( wphave_option('mb_trackbacks') ) {  
            remove_meta_box( 'trackbacksdiv', '', 'normal' );
        }

    }

endif;

add_action( 'do_meta_boxes' , 'wphave_admin_remove_metaboxes' );


/*
 *******************
 * REMOVE WP ADMIN DASHBOARD WIDGETS
 *******************
 *
 *	Remove different dashboard widgets from WordPress admin.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_remove_db_widgets') ) :

    function wphave_admin_remove_db_widgets() {
        
        if( wphave_option('dbw_quick_press') ) {
            remove_meta_box ( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
        }
        
        if( wphave_option('dbw_right_now') ) {
            remove_meta_box ( 'dashboard_right_now', 'dashboard', 'normal' ); // At the Glance
            if( is_multisite() ) {
                remove_meta_box ( 'network_dashboard_right_now', 'dashboard-network', 'normal' );
            } 
        }
        
        if( wphave_option('dbw_activity') ) {
            remove_meta_box ( 'dashboard_activity', 'dashboard', 'normal' ); // Activity
        }
        
        if( wphave_option('dbw_primary') ) {
            remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPress Events and News
            if( is_multisite() ) {
                remove_meta_box( 'dashboard_primary', 'dashboard-network', 'side' );
            }
        }
        
        if( wphave_option('dbw_welcome') ) {
            remove_action('welcome_panel', 'wp_welcome_panel'); // Welcome
        }

    }

endif;

add_action( 'wp_dashboard_setup' , 'wphave_admin_remove_db_widgets' );

if( is_multisite() ) {
    add_action( 'wp_network_dashboard_setup' , 'wphave_admin_remove_db_widgets' );
}


/*
 *******************
 * REMOVE WP ADMIN WIDGETS
 *******************
 *
 *	Remove different widgets from WordPress admin.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_remove_widgets') ) :

    function wphave_admin_remove_widgets() {
        
        if( wphave_option('wt_pages') ) {
            unregister_widget('WP_Widget_Pages');
        }
        
        if( wphave_option('wt_calendar') ) {
            unregister_widget('WP_Widget_Calendar');
        }
        
        if( wphave_option('wt_archives') ) {
            unregister_widget('WP_Widget_Archives');
        }
        
        if( wphave_option('wt_meta') ) {
            unregister_widget('WP_Widget_Meta');
        }
        
        if( wphave_option('wt_search') ) {
            unregister_widget('WP_Widget_Search');
        }
        
        if( wphave_option('wt_text') ) {
            unregister_widget('WP_Widget_Text');
        }
        
        if( wphave_option('wt_categories') ) {
            unregister_widget('WP_Widget_Categories');
        }
        
        if( wphave_option('wt_recent_posts') ) {
            unregister_widget('WP_Widget_Recent_Posts');
        }
        
        if( wphave_option('wt_recent_comments') ) {
            unregister_widget('WP_Widget_Recent_Comments');
        }
        
        if( wphave_option('wt_rss') ) {
            unregister_widget('WP_Widget_RSS');
        }
        
        if( wphave_option('wt_tag_cloud') ) {
            unregister_widget('WP_Widget_Tag_Cloud');
        }
        
        if( wphave_option('wt_nav') ) {
            unregister_widget('WP_Nav_Menu_Widget');
        }
        
        if( wphave_option('wt_image') ) {
            unregister_widget('WP_Widget_Media_Image');
        }
        
        if( wphave_option('wt_audio') ) {
            unregister_widget('WP_Widget_Media_Audio');
        }
        
        if( wphave_option('wt_video') ) {
            unregister_widget('WP_Widget_Media_Video');
        }
        
        if( wphave_option('wt_gallery') ) {
            unregister_widget('WP_Widget_Media_Gallery');
        }
        
        if( wphave_option('wt_html') ) {
            unregister_widget('WP_Widget_Custom_HTML');
        }

    }

endif;

add_action( 'widgets_init' , 'wphave_admin_remove_widgets' );


/*
 *******************
 * REMOVE WP SCREEN OPTIONS
 *******************
 *
 *	Remove the default WordPress admin "Screen Options" panel.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_screen_option') ) {

    if( ! function_exists('wphave_admin_remove_screen_options') ) :

        function wphave_admin_remove_screen_options() {
            return false; 
        }

    endif;

    add_filter('screen_options_show_screen', 'wphave_admin_remove_screen_options');

}


/*
 *******************
 * REMOVE WP CONTEXTUAL HELP
 *******************
 *
 *	Remove the default WordPress admin "Contectual Help" panel.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_help') ) {

    if( ! function_exists('wphave_admin_remove_contextual_help') ) :
    
        function wphave_admin_remove_contextual_help( $old_help, $screen_id, $screen ) {
            $screen->remove_help_tabs();
            return $old_help;
        }
    
    endif;
    
    add_filter( 'contextual_help', 'wphave_admin_remove_contextual_help', 999, 3 );
    
}


/*
 *******************
 * REMOVE COMMENTS MENU
 *******************
 *
 *	Remove the default WordPress "comments menu" from the upper toolbar.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_adminbar_comments') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_comments') ) :
    
        function wphave_admin_remove_adminbar_comments() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        }
    
    endif;
    
    add_action( 'wp_before_admin_bar_render', 'wphave_admin_remove_adminbar_comments' );
    
}


/*
 *******************
 * REMOVE NEW CONTENT MENU
 *******************
 *
 *	Remove the default WordPress "new content menu" from the upper toolbar.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_adminbar_new') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_new') ) :

        function wphave_admin_remove_adminbar_new() {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_menu('new-content');   
        }
    
    endif;

    add_action( 'wp_before_admin_bar_render', 'wphave_admin_remove_adminbar_new', 999 );
    
}


/*
 *******************
 * REMOVE WP (LOGO) MENU
 *******************
 *
 *	Remove the default WordPress "logo menu" from the upper toolbar.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('toolbar_wp_icon') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_wp_logo') ) :
    
        function wphave_admin_remove_adminbar_wp_logo() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wphave_admin_remove_adminbar_wp_logo', 0);
    
}


/*
 *******************
 * REMOVE CUSTOMIZE LINK
 *******************
 *
 *	Remove the default WordPress "customize link" from the upper toolbar.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_adminbar_customize') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_customize') ) :
    
        function wphave_admin_remove_adminbar_customize() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('customize');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wphave_admin_remove_adminbar_customize', 0);
    
}


/*
 *******************
 * REMOVE SEARCH FROM ADMIN BAR
 *******************
 *
 *	Remove the default WordPress "search form" from the upper toolbar.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( wphave_option('hide_adminbar_search') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_search') ) :
    
        function wphave_admin_remove_adminbar_search() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('search');
        }
    
    endif;
    
    add_action('wp_before_admin_bar_render', 'wphave_admin_remove_adminbar_search', 0);
    
}


/*
 *******************
 * REMOVE ADMIN BAR COMPLETE
 *******************
 *
 *	Remove the entire default WordPress upper toolbar.
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
if( wphave_option('toolbar') ) {

    if( ! function_exists('wphave_admin_remove_adminbar_complete') ) :

        function wphave_admin_remove_adminbar_complete() {
            wp_deregister_script('admin-bar');
            wp_deregister_style('admin-bar');  
            remove_action('admin_init', '_wp_admin_bar_init');
            remove_action('in_admin_header', 'wp_admin_bar_render', 0);
        }

    endif;

    add_action('admin_head', 'wphave_admin_remove_adminbar_complete', 0);
    
}*/