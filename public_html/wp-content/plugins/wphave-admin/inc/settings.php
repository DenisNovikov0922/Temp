<?php 

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('wphave_admin_settings') ) :

	class wphave_admin_settings {

		/*
		*  __construct
		*
		*  A dummy constructor to ensure the plugin is only initialized once
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function __construct() {
						
			// Set settings section headings
			$this->headings = array(
				'head_theme' => __( 'Design', 'wphave-admin' ),
				'head_menu' => __( 'Menu', 'wphave-admin' ),
				'head_toolbar' => __( 'Toolbar', 'wphave-admin' ),
				'head_view' => __( 'View', 'wphave-admin' ),
				'head_login' => __( 'Login Page', 'wphave-admin' ),
				'head_footer' => __( 'Footer', 'wphave-admin' ),
				'head_media' => __( 'Media', 'wphave-admin' ),
				'head_pages' => __( 'Pages', 'wphave-admin' ),
				'head_ms' => __( 'Multisite', 'wphave-admin' ),
				'head_optimize' => __( 'Optimization & Security', 'wphave-admin' ),
				'head_metabox' => __( 'Meta Boxes', 'wphave-admin' ),
				'head_dashboard' => __( 'Dashboard Widgets', 'wphave-admin' ),
				'head_widget' => __( 'Widgets', 'wphave-admin' ),
				'head_frontend' => __( 'Frontend', 'wphave-admin' ),
			);
			
			// Set keys and labels for all option fields
			$this->labels = array(
				'user_box' => __( 'User Box', 'wphave-admin' ),
				'company_box' => __( 'Logo Box', 'wphave-admin' ),
				'company_box_logo' => __( 'Company Box Logo', 'wphave-admin' ),
				'company_box_logo_size' => __( 'Company Box Logo Size', 'wphave-admin' ),
				'thumbnail' => __( 'Thumbnails', 'wphave-admin' ),
				'post_page_id' => __( 'Post/Page IDs', 'wphave-admin' ),
				'hide_help' => __( 'Contextual Help', 'wphave-admin' ),
				'hide_screen_option' => __( 'Screen Options', 'wphave-admin' ),
				'left_menu_width' => __( 'Menu Width', 'wphave-admin' ),
				'left_menu_expand' => __( 'Accordion Menu', 'wphave-admin' ),
				'spacing' => __( 'Boxed Layout', 'wphave-admin' ),
				'spacing_max_width' => __( 'Spacing Max Width', 'wphave-admin' ),
				'credits' => __( 'Credits', 'wphave-admin' ),
				'credits_text' => __( 'Credits Text', 'wphave-admin' ),
				'google_webfont' => __( 'Google Font', 'wphave-admin' ),
				'google_webfont_weight' => __( 'Custom Web Font Weight', 'wphave-admin' ),
				'toolbar' => __( 'Toolbar', 'wphave-admin' ),
				'hide_adminbar_comments' => __( 'Toolbar Comments Menu', 'wphave-admin' ),
				'hide_adminbar_new' => __( 'Toolbar New Content Menu', 'wphave-admin' ),
				'hide_adminbar_customize' => __( 'Toolbar Customize Link', 'wphave-admin' ),
				'hide_adminbar_search' => __( 'Toolbar Search', 'wphave-admin' ),
				'toolbar_wp_icon' => __( 'Toolbar WP Icon', 'wphave-admin' ),            
				'toolbar_icon' => __( 'Custom Toolbar Icon', 'wphave-admin' ),        
				'toolbar_color' => __( 'Toolbar Color', 'wphave-admin' ),        
				'howdy_greeting' => __( 'Howdy Greeting', 'wphave-admin' ),      
				'howdy_greeting_text' => __( 'Howdy Greeting', 'wphave-admin' ) . ' ' . __( 'Text', 'wphave-admin' ),
				'theme_color' => __( 'Theme Color', 'wphave-admin' ),
				'theme_background' => __( 'Background Gradient Start Color', 'wphave-admin' ),
				'theme_background_end' => __( 'Background Gradient End Color', 'wphave-admin' ),
				'login_disable' => __( 'Customized Login Page', 'wphave-admin' ),
				'login_title' => __( 'Login Title', 'wphave-admin' ),
				'logo_upload' => __( 'Login Logo', 'wphave-admin' ),
				'logo_size' => __( 'Login Logo Size', 'wphave-admin' ),
				'login_bg' => __( 'Login Background Image', 'wphave-admin' ),
				'memory_usage' => __( 'Memory Usage', 'wphave-admin' ),
				'memory_limit' => __( 'WP Memory Limit', 'wphave-admin' ),
				'memory_available' => __( 'Memory Available', 'wphave-admin' ),
				'php_version' => __( 'PHP Version', 'wphave-admin' ),
				'ip_address' => __( 'IP Address', 'wphave-admin' ),
				'wp_version' => __( 'WP Version', 'wphave-admin' ),
				'css_admin' => __( 'Admin CSS', 'wphave-admin' ),
				'css_login' => __( 'Login CSS', 'wphave-admin' ),
				'wp_svg' => __( 'SVG Support', 'wphave-admin' ),
				'wp_ico' => __( 'ICO Support', 'wphave-admin' ),
				'disable_page_system' => __( 'System Info', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_wp' => __( 'WordPress', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_constants' => __( 'Constants', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_transient_manager' => __( 'Transient Manager', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_export' => __( 'Im- / Export', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_ms' => __( 'Multisite Sync', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_error_log' => __( 'Error Log', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_htaccess' => __( '.htaccess', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_php_ini' => __( 'php.ini', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_page_robots_txt' => __( 'robots.txt', 'wphave-admin' ) . ' ' . __( 'Page', 'wphave-admin' ),
				'disable_theme_options' => __( 'Network Theme Options', 'wphave-admin' ),
				'disable_plugin_subsite' => __( 'Network Plugin Access', 'wphave-admin' ),
				'wp_version_tag' => __( 'WP Version Meta-Tag', 'wphave-admin' ),
				'wp_emoji' => __( 'WP Emoji', 'wphave-admin' ),
				'wp_feed_links' => __( 'WP RSS Feed', 'wphave-admin' ),
				'wp_rsd_link' => __( 'WP RSD', 'wphave-admin' ),
				'wp_wlwmanifest' => __( 'WP Wlwmanifest', 'wphave-admin' ),
				'wp_shortlink' => __( 'WP Shortlink', 'wphave-admin' ),
				'wp_rest_api' => __( 'WP REST API', 'wphave-admin' ),
				'wp_oembed' => __( 'WP oEmbed', 'wphave-admin' ),
				'wp_xml_rpc' => __( 'WP XML-RPC / X-Pingback', 'wphave-admin' ),
				'wp_heartbeat' => __( 'WP Heartbeat', 'wphave-admin' ),
				'wp_rel_link' => __( 'WP Rel Links', 'wphave-admin' ),
				'wp_self_pingback' => __( 'WP Self Pingbacks', 'wphave-admin' ),
				'mb_custom_fields' => __( 'Custom Fields Meta Box', 'wphave-admin' ),
				'mb_commentstatus' => __( 'Comments Status Meta Box', 'wphave-admin' ),
				'mb_comments' => __( 'Comments Meta Box', 'wphave-admin' ),
				'mb_author' => __( 'Author Meta Box', 'wphave-admin' ),
				'mb_category' => __( 'Categories Meta Box', 'wphave-admin' ),
				'mb_format' => __( 'Post Format Meta Box', 'wphave-admin' ),
				'mb_pageparent' => __( 'Page Parent Meta Box', 'wphave-admin' ),
				'mb_postexcerpt' => __( 'Post Excerpt Meta Box', 'wphave-admin' ),
				'mb_postimage' => __( 'Post Image Meta Box', 'wphave-admin' ),
				'mb_revisions' => __( 'Revisions Meta Box', 'wphave-admin' ),
				'mb_slug' => __( 'Slug Meta Box', 'wphave-admin' ),
				'mb_tags' => __( 'Tags Meta Box', 'wphave-admin' ),
				'mb_trackbacks' => __( 'Trackbacks Meta Box', 'wphave-admin' ),
				'dbw_quick_press' => __( 'Qick Draft Widget', 'wphave-admin' ),
				'dbw_right_now' => __( 'At the Glance Widget', 'wphave-admin' ),
				'dbw_activity' => __( 'Activity Widget', 'wphave-admin' ),
				'dbw_primary' => __( 'WP Events & News Widget', 'wphave-admin' ),
				'dbw_welcome' => __( 'Welcome Widget', 'wphave-admin' ),
				'dbw_wpat_user_log' => __( 'WPAT User Activities Widget', 'wphave-admin' ),
				'dbw_wpat_sys_info' => __( 'WPAT System Info Widget', 'wphave-admin' ),
				'dbw_wpat_count_post' => __( 'WPAT Post Count Widget', 'wphave-admin' ),
				'dbw_wpat_count_page' => __( 'WPAT Page Count Widget', 'wphave-admin' ),
				'dbw_wpat_count_comment' => __( 'WPAT Comment Count Widget', 'wphave-admin' ),
				'dbw_wpat_recent_post' => __( 'WPAT Recent Posts Widget', 'wphave-admin' ),
				'dbw_wpat_recent_page' => __( 'WPAT Recent Pages Widget', 'wphave-admin' ),
				'dbw_wpat_recent_comment' => __( 'WPAT Recent Comments Widget', 'wphave-admin' ),
				'dbw_wpat_memory' => __( 'WPAT Memory Usage Widget', 'wphave-admin' ),
				'wt_pages' => __( 'Pages Widget', 'wphave-admin' ),
				'wt_calendar' => __( 'Calendar Widget', 'wphave-admin' ),
				'wt_archives' => __( 'Archives Widget', 'wphave-admin' ),
				'wt_meta' => __( 'Meta Widget', 'wphave-admin' ),
				'wt_search' => __( 'Search Widget', 'wphave-admin' ),
				'wt_text' => __( 'Text Widget', 'wphave-admin' ),
				'wt_categories' => __( 'Categories Widget', 'wphave-admin' ),
				'wt_recent_posts' => __( 'Recent Posts Widget', 'wphave-admin' ),
				'wt_recent_comments' => __( 'Recent Comments Widget', 'wphave-admin' ),
				'wt_rss' => __( 'RSS Widget', 'wphave-admin' ),
				'wt_tag_cloud' => __( 'Tag Cloud Widget', 'wphave-admin' ),
				'wt_nav' => __( 'Navigation Menu Widget', 'wphave-admin' ),
				'wt_image' => __( 'Image Widget', 'wphave-admin' ),
				'wt_audio' => __( 'Audio Widget', 'wphave-admin' ),
				'wt_video' => __( 'Video Widget', 'wphave-admin' ),
				'wt_gallery' => __( 'Gallery Widget', 'wphave-admin' ),
				'wt_html' => __( 'Custom HTML Widget', 'wphave-admin' ),
				'wp_header_code' => __( 'Header Code', 'wphave-admin' ),
				'wp_footer_code' => __( 'Footer Code', 'wphave-admin' ),
				'meta_referrer_policy' => __( 'Meta Referrer Policy', 'wphave-admin' ),
			);

			// Group plugin page settings with the keys
			$this->plugin_pages_group = array(
				'disable_page_system',
				'disable_page_wp',
				'disable_page_constants',
				'disable_transient_manager',
				'disable_page_export',
				'disable_page_ms',
				'disable_page_error_log',
				'disable_page_htaccess',
				'disable_page_php_ini',
				'disable_page_robots_txt',
			);

			// Group db widget settings with the keys
			$this->db_widget_group = array(
				'dbw_quick_press',
				'dbw_right_now',
				'dbw_activity',
				'dbw_primary',
				'dbw_welcome', 
				'dbw_wpat_user_log', 
				'dbw_wpat_sys_info',
				'dbw_wpat_count_post',
				'dbw_wpat_count_page',
				'dbw_wpat_count_comment',
				'dbw_wpat_recent_post',
				'dbw_wpat_recent_page',
				'dbw_wpat_recent_comment',
				'dbw_wpat_memory',
			);

			// Group widget settings with the keys
			$this->widget_group = array(
				'wt_pages',
				'wt_calendar',
				'wt_archives',
				'wt_meta',
				'wt_search',
				'wt_text',
				'wt_categories',
				'wt_recent_posts',
				'wt_recent_comments',
				'wt_rss',
				'wt_tag_cloud',
				'wt_nav',
				'wt_image',
				'wt_audio',
				'wt_video',
				'wt_gallery',
				'wt_html',
			);

			// Group optimization settings with the keys and description
			$this->optimization_group = array(
				array(
					'wp_version_tag',
					__( 'Remove the WordPress Version Meta-Tag from wp head.', 'wphave-admin' ),
					__( 'Show the version number of your currently installed WordPress in the source code.', 'wphave-admin' ),
				),
				array(
					'wp_emoji',
					__( 'Remove the WordPress Emoticons from your source code.', 'wphave-admin' ),
					__( 'Display a textual portrayals like ";-)" as a emoticon icon.', 'wphave-admin' ),
				),
				array(
					'wp_feed_links',
					__( 'Disable the RSS feed functionality and remove the WordPress page and comments RSS feed links from wp head.', 'wphave-admin' ),
					__( 'RSS (Really Simple Syndication) is a type of web feed which allows users to access updates to online content in a standardized, computer-readable format.', 'wphave-admin' ),
				),
				array(
					'wp_rsd_link',
					__( 'Remove the RSD link from wp head.', 'wphave-admin' ),
					__( 'Really Simple Discovery (RSD) is an XML format and a publishing convention for making services exposed by a blog, or other web software, discoverable by client software.', 'wphave-admin' ),
				),
				array(
					'wp_wlwmanifest',
					__( 'Remove the Wlwmanifest link from wp head.', 'wphave-admin' ),
					__( 'Needed to enable tagging support for Windows Live Writer.', 'wphave-admin' ),
				),
				array(
					'wp_shortlink',
					__( 'Remove the shortlink link from wp head.', 'wphave-admin' ),
					__( 'Shortlink is a shorten version of a web page’s URL.', 'wphave-admin' ),
				),
				array(
					'wp_rest_api',
					__( 'Disable the REST API and remove the wp json link from wp head.', 'wphave-admin' ),
					__( 'The API makes it super easy to retrieve data using GET requests, which is useful for those building apps with WordPress.', 'wphave-admin' ),
				),
				array(
					'wp_oembed',
					__( 'Disable wp embed and remove the oEmbed links from wp head.', 'wphave-admin' ),
					__( 'oEmbed feature which allows others to embed your WordPress posts into their own site by adding the post URL.', 'wphave-admin' ),
				),
				array(
					'wp_xml_rpc',
					__( 'Disable remote access.', 'wphave-admin' ),
					__( 'XML-RPC is a remote procedure call which uses XML to encode its calls and HTTP as a transport mechanism. If you want to access and publish to your blog remotely, then you need XML-RPC enabled. XML-RPC protocol is used by WordPress as API for Pingbacks and third-party applications, such as mobile apps, inter-blog communication and popular plugins like JetPack.', 'wphave-admin' ),
				),
				array(
					'wp_heartbeat',
					__( 'Stop the heartbeat updates.', 'wphave-admin' ),
					__( 'The Heartbeat API is a simple server polling API built in to WordPress, allowing near-real-time frontend updates. The heartbeat API allows for regular communication between the users browser and the server. One of the original motivations was to allow for locking posts and warning users when more than one user is attempting to edit a post, or warning the user when their log-in has expired.', 'wphave-admin' ),
				),
				array(
					'wp_rel_link',
					__( 'Remove the post rel index / start / parent / prev / next links from wp head.', 'wphave-admin' ),
					__( 'This feature display the URL of the index, start, parent, previous and next post in the source code.', 'wphave-admin' ),
				),    
				array(
					'wp_self_pingback',
					__( 'Disable WordPress self pingbacks / trackbacks.', 'wphave-admin' ),
					__( 'This will allow you to disable self-pingbacks (messages and comments), which are linking back to your own blog.', 'wphave-admin' ),
				),     
			);

			// Group meta box settings with the keys and description
			$this->metabox_group = array(
				array(
					'mb_custom_fields',
					__( 'Remove the Custom Fields Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_commentstatus',
					__( 'Remove the Discussion Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_comments',
					__( 'Remove the Comments Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_author',
					__( 'Remove the Author Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_category',
					__( 'Remove the Category Box for posts.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_format',
					__( 'Remove the Post Format Box for posts.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_pageparent',
					__( 'Remove the Page Attributes Box for pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_postexcerpt',
					__( 'Remove the Excerpt Box for posts.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_postimage',
					__( 'Remove the Featured Image Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_revisions',
					__( 'Remove the Revisions Box for posts and pages.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_slug',
					__( 'Remove the Slug Box for posts and pages.', 'wphave-admin' ),
					__( 'Caution: Disabling the slug box does not allow you to customize the post or page URL.', 'wphave-admin' ),
				),
				array(
					'mb_tags',
					__( 'Remove the Tags Box for posts.', 'wphave-admin' ),
					'',
				),
				array(
					'mb_trackbacks',
					__( 'Remove the Send Trackbacks Box for posts and pages.', 'wphave-admin' ),
					'',
				),            
			);

			// Group frontend settings with the keys and description
			$this->frontend_group = array(
				array(
					'wp_header_code',
					__( 'Add custom code to the frontend header.', 'wphave-admin' ),
					__( 'Will be inserted into the wp_head hook.', 'wphave-admin' ),
				),
				array(
					'wp_footer_code',
					__( 'Add custom code to the frontend footer.', 'wphave-admin' ),
					__( 'Will be inserted into the wp_footer hook.', 'wphave-admin' ),
				),
				array(
					'meta_referrer_policy',
					__( 'Add the meta referrer tag and select your value.', 'wphave-admin' ),
					__( 'If you use SSL for your website, analytics tools like Google Analytics can not see the referrer by default. For example, if you select "Origin", your referrer will be visible again.', 'wphave-admin' ),
				),        
			);

			// Exception fields are not restorable
			$css_admin = isset( $this->options['css_admin'] ) ? $this->options['css_admin'] : null;
			$css_login = isset( $this->options['css_login'] ) ? $this->options['css_login'] : null;
			$wp_header_code = isset( $this->options['wp_header_code'] ) ? $this->options['wp_header_code'] : null;
			$wp_footer_code = isset( $this->options['wp_footer_code'] ) ? $this->options['wp_footer_code'] : null;

			// Define pre option values (used for initial plugin load and restore options)
			$this->pre_options = array(
				'user_box' => '',
				'company_box' => '',
				'company_box_logo' => '',
				'company_box_logo_size' => 140,
				'thumbnail' => '',
				'post_page_id' => '',
				'hide_help' => '',
				'hide_screen_option' => '',
				'left_menu_width' => 160,
				'left_menu_expand' => '',
				'spacing' => '',
				'spacing_max_width' => 2000,
				'credits' => '',
				'credits_text' => '',
				'google_webfont' => '',
				'google_webfont_weight' => '',
				'toolbar' => '',
				'hide_adminbar_comments' => '',
				'hide_adminbar_new' => '',
				'hide_adminbar_customize' => '',
				'hide_adminbar_search' => '',
				'toolbar_wp_icon' => '',
				'toolbar_icon' => '',
				'toolbar_color' => '#32373c',
				'howdy_greeting' => '',
				'howdy_greeting_text' => '',
				'theme_color' => '#4777CD',
				'theme_background' => '#545c63',
				'theme_background_end' => '#32373c',
				'login_disable' => '',
				'login_title' => __( 'Welcome Back.', 'wphave-admin' ),
				'logo_upload' => '',
				'logo_size' => 250,
				'login_bg' => '',
				'memory_usage' => '',
				'memory_limit' => '',
				'memory_available' => '',
				'php_version' => '',
				'ip_address' => '',
				'wp_version' => '',
				'css_admin' => esc_html( $css_admin ),
				'css_login' => esc_html( $css_login ),
				'wp_svg' => '',
				'wp_ico' => '',
				'disable_page_system' => '',
				'disable_page_wp' => '',
				'disable_page_constants' => '',
				'disable_transient_manager' => '',
				'disable_page_export' => '',
				'disable_page_ms' => '',
				'disable_page_error_log' => '',
				'disable_page_htaccess' => '',
				'disable_page_php_ini' => '',
				'disable_page_robots_txt' => '',
				'disable_theme_options' => '',
				'disable_plugin_subsite' => '',
				'wp_version_tag' => '',
				'wp_emoji' => '',
				'wp_feed_links' => '',
				'wp_rsd_link' => '',
				'wp_wlwmanifest' => '',
				'wp_shortlink' => '',
				'wp_rest_api' => '',
				'wp_oembed' => '',
				'wp_xml_rpc' => '',
				'wp_heartbeat' => '',
				'wp_rel_link' => '',
				'wp_self_pingback' => '',
				'mb_custom_fields' => '',
				'mb_commentstatus' => '',
				'mb_comments' => '',
				'mb_author' => '',
				'mb_category' => '',
				'mb_format' => '',
				'mb_pageparent' => '',
				'mb_postexcerpt' => '',
				'mb_postimage' => '',
				'mb_revisions' => '',
				'mb_slug' => '',
				'mb_tags' => '',
				'mb_trackbacks' => '',
				'dbw_quick_press' => '',
				'dbw_right_now' => '',
				'dbw_activity' => '',
				'dbw_primary' => '',
				'dbw_welcome' => '',
				'dbw_wpat_user_log' => '',
				'dbw_wpat_sys_info' => '',
				'dbw_wpat_count_post' => '',
				'dbw_wpat_count_page' => '',
				'dbw_wpat_count_comment' => '',
				'dbw_wpat_recent_post' => '',
				'dbw_wpat_recent_page' => '',
				'dbw_wpat_recent_comment' => '',
				'dbw_wpat_memory' => '',
				'wt_pages' => '',
				'wt_calendar' => '',
				'wt_archives' => '',
				'wt_meta' => '',
				'wt_search' => '',
				'wt_text' => '',
				'wt_categories' => '',
				'wt_recent_posts' => '',
				'wt_recent_comments' => '',
				'wt_rss' => '',
				'wt_tag_cloud' => '',
				'wt_nav' => '',
				'wt_image' => '',
				'wt_audio' => '',
				'wt_video' => '',
				'wt_gallery' => '',
				'wt_html' => '',
				'wp_header_code' => esc_html( $wp_header_code ),
				'wp_footer_code' => esc_html( $wp_footer_code ),
				'meta_referrer_policy' => 'none',
			);

			// Get registered options
			$this->options = get_option( 'wp_admin_theme_settings_options' );
			
			if( is_multisite() ) {
				$this->options = get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() );
			}		
			
			// Check if options not exist
			if( ! $this->options ) {				
				// Set initial options to the index
				if( is_multisite() ) {
					update_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', $this->pre_options);
				} else {
					update_option( 'wp_admin_theme_settings_options', $this->pre_options, 'yes' );
				}
			}

		}

		/*
		*  initialize
		*
		*  The real constructor to initialize the plugin
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		
		function initialize() {
			
			// Deny page access for sub sites	
			if( wphave_admin_deny_access() ) {
				// Add admin menu item
				add_action( 'admin_menu', array( $this, 'wphave_admin_settings_page' ) );
			}
			
			// Register settings options
			add_action( 'admin_init', array( $this, 'wphave_admin_register_settings') );

			// Register page options
			add_action( 'admin_init', array( $this, 'wphave_admin_register_settings_fields') );		
			

			/****************
			* ACTIONS
			****************/
			
			add_action('init', array( $this, 'wphave_admin_settings_init' ), 5);
			
		}

		/*
		*  init
		*
		*  This function will run after all plugins and theme functions have been included.
		*  To ensure specific functions doesn't undefined, if the plugin call it to early, we can use this init function.
		*
		*  @type	action (init)
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function wphave_admin_settings_init() {			
			
			/****************
			* OPTION WRAPPER
			****************/
			
			function wphave_option( $option ) {

				// Get currently indexed option fields
				$options = get_option( 'wp_admin_theme_settings_options' );
				if( is_multisite() ) {
					$options = get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() );
				}
				
				// Get pre options
				$pre_option = wphave_admin_settings()->pre_options[$option];
				
				// Set the option
				if( ! wphave_admin_activation_status() ) {
					// If no purchase code entered, use the default settings
					$get_option = $pre_option;
				} else {
					// With purchase code, use custom settings
					$get_option = isset( $options[$option] ) ? $options[$option] : $pre_option;
				}
					
				return $get_option;

			}
			
		}


		/*
		 *******************
		 * REGISTER SETTINGS/OPTIONS
		 *******************
		 *
		 *	Register the option settings and its data.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/
		
		function wphave_admin_register_settings() {

			register_setting( 
				'wphave_admin_page', // Option group
				'wp_admin_theme_settings_options', // Option name
				array( $this, 'wphave_admin_validate_settings' ) // Args (sanitize)
			);

		}
		

		/*
		 *******************
		 * PLUGIN SUBPAGE
		 *******************
		 *
		 *	Add a option subpage for this plugin nested in the default WordPress "Tools" menu.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/
		
		function wphave_admin_settings_page() {

			add_submenu_page( 
				'tools.php', // Parent slug
				esc_html__( 'wphave - Admin', 'wphave-admin' ), // Page title
				esc_html__( 'wphave - Admin', 'wphave-admin' ), // Menu title
				'manage_options', // Capability
				'wphave-admin', // Menu slug
				array( $this, 'wphave_admin_settings_page_content' ) // Callback function
			);

		}


		/*
		 *******************
		 * CREATE SUBPAGE CONTENT
		 *******************
		 *
		 *	Create the content for the plugin subpage.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function wphave_admin_settings_page_content() {
			
			$access = wphave_admin_option_access(); ?>

			<div class="wrap">
			
				<h1>
					<?php echo wphave_admin_title(); ?>
				</h1>
				
				<?php // Deny page access for sub sites	
				if( ! wphave_admin_deny_access() ) {
					return wphave_admin_no_access();
				} ?>
				
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						
						<form action="options.php" method="post" enctype="multipart/form-data">
							
							<div id="post-body-content">
								<div class="settings-wrapper">
									<div class="inside">										

										<?php if( wphave_admin_activation_status() ) {

											/****************
											* TAB MENU
											****************/

											$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
											echo wphave_admin_tab_menu( $active_tab );

											/****************
											* TABLE OF CONTENTS
											****************/ 

											?>

											<div class="wpat-page-menu">
												<ul<?php if( is_rtl() ) { echo ' dir="rtl"'; } ?>>
													<li><a href="#index_theme"><?php echo $this->headings['head_theme']; ?></a></li>
													<li><a href="#index_menu"><?php echo $this->headings['head_menu']; ?></a></li>
													<li><a href="#index_toolbar"><?php echo $this->headings['head_toolbar']; ?></a></li>
													<li><a href="#index_view"><?php echo $this->headings['head_view']; ?></a></li>
													<li><a href="#index_footer"><?php echo $this->headings['head_footer']; ?></a></li>
													<li><a href="#index_login"><?php echo $this->headings['head_login']; ?></a></li>
													<li><a href="#index_media"><?php echo $this->headings['head_media']; ?></a></li>
													<li><a href="#index_page"><?php echo $this->headings['head_pages']; ?></a></li>
													<li><a href="#index_metabox"><?php echo $this->headings['head_metabox']; ?></a></li>
													<li><a href="#index_dashboard"><?php echo $this->headings['head_dashboard']; ?></a></li>
													<li><a href="#index_widget"><?php echo $this->headings['head_widget']; ?></a></li>
													<li><a href="#index_frontend"><?php echo $this->headings['head_frontend']; ?></a></li>
													<li><a href="#index_optimize"><?php echo $this->headings['head_optimize']; ?></a></li>
													<li><a href="#index_ms"><?php echo $this->headings['head_ms']; ?></a></li>
												</ul>
											</div>

											<?php 

											/****************
											* ERROR MESSAGE
											****************/

											settings_errors('wphave_admin_page');

											/****************
											* SETTINGS FIELDS
											****************/

											settings_fields('wphave_admin_page');				
											do_settings_sections('wphave_admin_page'); ?>

											<table class="form-table">
												<tbody>
													<tr>
														<th scope="row"></th>
														<td>
															<p class="description">

																<?php
				
																/****************
																* SAVE BUTTON
																****************/

																if( $access ) {
																	submit_button( esc_html__( 'Update', 'wphave-admin' ), 'button button-primary', 'save', false );
																} else { ?>
																	<button class="button" disabled>
																		<?php echo esc_html__( 'You have no permissions to change this options!', 'wphave-admin' ); ?>
																	</button>
																<?php }

																/****************
																* RESTORE BUTTON
																****************/

																if( $access ) { ?>
																	<button class="button restore" id="restore_options">
																		<?php echo esc_html__( 'Restore', 'wphave-admin' ); ?>
																	</button>
																	<div style="display: none">
																		<?php submit_button( esc_html__( 'Restore', 'wphave-admin' ), 'button restore', 'reset', false );  ?>
																	</div>
																<?php } ?>

															</p>
														</td>
													</tr>
												</tbody>
											</table>

										<?php } else {
											echo wphave_admin_plugin_activation_message();
										} ?>

									</div>
								</div>
							</div>		

							<div id="postbox-container-1" class="postbox-container">
								<div id="side-sortables" class="meta-box-sortables ui-sortable">
									<div id="submitdiv" class="postbox ">
										<div class="inside">
											<div class="submitbox" id="submitpost">
												
												<div id="minor-publishing">
													<img src="<?php echo wphave_admin_path( 'assets/img/screenshot.png' ); ?>" width="100%" alt="Plugin Screenshot">
													
													<?php echo wphave_admin_side_menu(); ?>
												</div>
												
												<?php if( $access ) { ?>
												
													<div id="major-publishing-actions">
														<div id="delete-action">
															
															<?php 

															/****************
															* RESTORE BUTTON
															****************/

															?>

															<a href="#" class="submitdelete deletion" id="restore_options_side">
																<?php echo esc_html__( 'Restore', 'wphave-admin' ); ?>
															</a>
															<script>
																jQuery(document).ready( function($) {

																	// Trigger the "Restore" form submit button by clicking the restore link
																	$('#restore_options, #restore_options_side').click(function(){

																		// Show confirmation popup
																		if( ! confirm( '<?php echo esc_html__( 'Are you sure to restore all options?', 'wphave-admin' ); ?>' ) ) {
																			return false;
																		}

																		// Trigger the click on the true restore button
																		$('#reset').trigger("click");
																	}).change();

																});
															</script>

														</div>

														<div id="publishing-action">
															
															<?php 

															/****************
															* SAVE BUTTON
															****************/

															submit_button( esc_html__( 'Update', 'wphave-admin' ), 'button button-primary', 'save', false ); ?>
															
														</div>
														<div class="clear"></div>
													</div>
												
												<?php } ?>
												
											</div>
										</div>
									</div>
								</div>
							</div>
							
						</form>
						
					</div>
				</div>
			</div>

		<?php }

		
		/*
		 *******************
		 * ADD SETTINGS SECTIONS AND FIELDS
		 *******************
		 *
		 *	Add different section with nested fields to manage the plugin settings.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function wphave_admin_register_settings_fields() {

			/****************
			* DESIGN
			****************/
			
			add_settings_section( 
				'section_design', 
				'<span id="index_theme" class="wpat-page-index"></span>' . $this->headings['head_theme'], 
				array( $this, 'wphave_admin_section_design' ), 
				'wphave_admin_page' 
			);

			add_settings_field( 'theme_color',              $this->labels['theme_color'],                   array( $this, 'setting_color' ),                   	'wphave_admin_page', 'section_design' );       
			add_settings_field( 'spacing',                  $this->labels['spacing'],                       array( $this, 'setting_spacing' ),                 	'wphave_admin_page', 'section_design' );
			add_settings_field( 'gradient',            		esc_html__( 'Background Color', 'wphave-admin' ), 	array( $this, 'setting_background' ),      		'wphave_admin_page', 'section_design' );
			add_settings_field( 'google_webfont',           $this->labels['google_webfont'],                array( $this, 'setting_google_webfont' ),          	'wphave_admin_page', 'section_design' );
			add_settings_field( 'css_admin',                $this->labels['css_admin'],                     array( $this, 'setting_css_admin' ),               	'wphave_admin_page', 'section_design' );
			
			/****************
			* MENU
			****************/
			
			add_settings_section( 
				'section_menu', 
				'<span id="index_menu" class="wpat-page-menu"></span>' . $this->headings['head_menu'], 
				array( $this, 'wphave_admin_section_menu' ), 
				'wphave_admin_page' 
			);
			
			add_settings_field( 'left_menu_width',       	$this->labels['left_menu_width'],             	array( $this, 'setting_left_menu_width' ),			'wphave_admin_page', 'section_menu' );
			add_settings_field( 'left_menu_expand',       	$this->labels['left_menu_expand'],             	array( $this, 'setting_left_menu_expand' ),      	'wphave_admin_page', 'section_menu' );
			add_settings_field( 'user_box',                 $this->labels['user_box'],                      array( $this, 'setting_user_box' ),                	'wphave_admin_page', 'section_menu' );
			add_settings_field( 'company_box',              $this->labels['company_box'],                   array( $this, 'setting_company_box' ),             	'wphave_admin_page', 'section_menu' );

			/****************
			* TOOLBAR
			****************/
			
			add_settings_section( 
				'section_toolbar', 
				'<span id="index_toolbar" class="wpat-page-index"></span>' . $this->headings['head_toolbar'], 
				array( $this, 'wphave_admin_section_toolbar' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'toolbar',                  $this->labels['toolbar'],                        array( $this, 'setting_toolbar' ),                 'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'toolbar_color',            $this->labels['toolbar_color'],                  array( $this, 'setting_toolbar_color' ),           'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'hide_adminbar_comments',   $this->labels['hide_adminbar_comments'],         array( $this, 'setting_hide_adminbar_comments' ),  'wphave_admin_page', 'section_toolbar' );   
			add_settings_field( 'hide_adminbar_new',        $this->labels['hide_adminbar_new'],              array( $this, 'setting_hide_adminbar_new' ),       'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'hide_adminbar_customize',  $this->labels['hide_adminbar_customize'],        array( $this, 'setting_hide_adminbar_customize' ), 'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'hide_adminbar_search',     $this->labels['hide_adminbar_search'],           array( $this, 'setting_hide_adminbar_search' ),    'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'toolbar_wp_icon',          $this->labels['toolbar_wp_icon'],                array( $this, 'setting_toolbar_wp_icon' ),         'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'toolbar_icon',             $this->labels['toolbar_icon'],                   array( $this, 'setting_toolbar_icon' ),            'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'howdy_greeting',           $this->labels['howdy_greeting'],                 array( $this, 'setting_howdy_greeting' ),          'wphave_admin_page', 'section_toolbar' );
			add_settings_field( 'howdy_greeting_text',      $this->labels['howdy_greeting_text'],            array( $this, 'setting_howdy_greeting_text' ),     'wphave_admin_page', 'section_toolbar' );

			/****************
			* VIEW
			****************/
			
			add_settings_section( 
				'section_view', 
				'<span id="view_theme" class="wpat-page-view"></span>' . $this->headings['head_view'], 
				array( $this, 'wphave_admin_section_view' ), 
				'wphave_admin_page' 
			);

			add_settings_field( 'thumbnail',                $this->labels['thumbnail'],                     array( $this, 'setting_thumbnail' ),               	'wphave_admin_page', 'section_view' );
			add_settings_field( 'post_page_id',             $this->labels['post_page_id'],                  array( $this, 'setting_post_page_id' ),            	'wphave_admin_page', 'section_view' );
			add_settings_field( 'hide_help',                $this->labels['hide_help'],                     array( $this, 'setting_hide_help' ),               	'wphave_admin_page', 'section_view' );
			add_settings_field( 'hide_screen_option',       $this->labels['hide_screen_option'],            array( $this, 'setting_hide_screen_option' ),      	'wphave_admin_page', 'section_view' );

			/****************
			* FOOTER
			****************/
			
			add_settings_section( 
				'section_footer', 
				'<span id="index_footer" class="wpat-page-index"></span>' . $this->headings['head_footer'], 
				array( $this, 'wphave_admin_section_footer' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'credits',                  $this->labels['credits'],                        array( $this, 'setting_credits' ),                 'wphave_admin_page', 'section_footer' );
			add_settings_field( 'credits_text',             $this->labels['credits_text'],                   array( $this, 'setting_credits_text' ),            'wphave_admin_page', 'section_footer' );
			add_settings_field( 'memory_usage',             $this->labels['memory_usage'],                   array( $this, 'setting_memory_usage' ),            'wphave_admin_page', 'section_footer' );     
			add_settings_field( 'memory_limit',             $this->labels['memory_limit'],                   array( $this, 'setting_memory_limit' ),            'wphave_admin_page', 'section_footer' );
			add_settings_field( 'memory_available',         $this->labels['memory_available'],               array( $this, 'setting_memory_available' ),        'wphave_admin_page', 'section_footer' );
			add_settings_field( 'php_version',              $this->labels['php_version'],                    array( $this, 'setting_php_version' ),             'wphave_admin_page', 'section_footer' );    
			add_settings_field( 'ip_address',               $this->labels['ip_address'],                     array( $this, 'setting_ip_address' ),              'wphave_admin_page', 'section_footer' );       
			add_settings_field( 'wp_version',               $this->labels['wp_version'],                     array( $this, 'setting_wp_version' ),              'wphave_admin_page', 'section_footer' );
			
			/****************
			* LOGIN
			****************/
			
			add_settings_section( 
				'section_login', 
				'<span id="index_login" class="wpat-page-index"></span>' . $this->headings['head_login'], 
				array( $this, 'wphave_admin_section_login' ), 
				'wphave_admin_page' 
			);

			add_settings_field( 'login_disable',            $this->labels['login_disable'],                  array( $this, 'setting_login_disable' ),           'wphave_admin_page', 'section_login' );
			add_settings_field( 'login_title',              $this->labels['login_title'],                    array( $this, 'setting_login_title' ),             'wphave_admin_page', 'section_login' );
			add_settings_field( 'logo_upload',              $this->labels['logo_upload'],                    array( $this, 'setting_logo_upload' ),             'wphave_admin_page', 'section_login' );
			add_settings_field( 'login_bg',                 $this->labels['login_bg'],                       array( $this, 'setting_login_bg' ),                'wphave_admin_page', 'section_login' );
			add_settings_field( 'css_login',                $this->labels['css_login'],                      array( $this, 'setting_css_login' ),               'wphave_admin_page', 'section_login' );

			/****************
			* MEDIA
			****************/
			
			add_settings_section( 
				'section_media', 
				'<span id="index_media" class="wpat-page-index"></span>' . $this->headings['head_media'], 
				array( $this, 'wphave_admin_section_media' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'wp_svg',                   $this->labels['wp_svg'],                         array( $this, 'setting_wp_svg' ),                  'wphave_admin_page', 'section_media' );
			add_settings_field( 'wp_ico',                   $this->labels['wp_ico'],                         array( $this, 'setting_wp_ico' ),                  'wphave_admin_page', 'section_media' );

			/****************
			* PLUGIN PAGES
			****************/
			
			add_settings_section( 
				'section_pages', 
				'<span id="index_page" class="wpat-page-index"></span>' . $this->headings['head_pages'], 
				array( $this, 'wphave_admin_section_plugin_pages' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'disable_page_system',      $this->labels['disable_page_system'],            array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_wp',      	$this->labels['disable_page_wp'],            	 array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_constants',   $this->labels['disable_page_constants'],         array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_transient_manager',$this->labels['disable_transient_manager'],      array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_export',      $this->labels['disable_page_export'],            array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_ms',          $this->labels['disable_page_ms'],                array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_error_log',   $this->labels['disable_page_error_log'],         array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_htaccess',   	$this->labels['disable_page_htaccess'],          array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_php_ini',   	$this->labels['disable_page_php_ini'],           array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );
			add_settings_field( 'disable_page_robots_txt',  $this->labels['disable_page_robots_txt'],        array( $this, 'setting_disable_plugin_pages' ),    'wphave_admin_page', 'section_pages' );

			/****************
			* META BOXES
			****************/
			
			add_settings_section( 
				'section_meta_boxes', 
				'<span id="index_metabox" class="wpat-page-index"></span>' . $this->headings['head_metabox'], 
				array( $this, 'wphave_admin_section_meta_boxes' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'mb_custom_fields',         $this->labels['mb_custom_fields'],               array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_commentstatus',         $this->labels['mb_commentstatus'],               array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_comments',              $this->labels['mb_comments'],                    array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_author',                $this->labels['mb_author'],                      array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_category',              $this->labels['mb_category'],                    array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_format',                $this->labels['mb_format'],                      array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_pageparent',            $this->labels['mb_pageparent'],                  array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_postexcerpt',           $this->labels['mb_postexcerpt'],                 array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_postimage',             $this->labels['mb_postimage'],                   array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_revisions',             $this->labels['mb_revisions'],                   array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_slug',                  $this->labels['mb_slug'],                        array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_tags',                  $this->labels['mb_tags'],                        array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );
			add_settings_field( 'mb_trackbacks',            $this->labels['mb_trackbacks'],                  array( $this, 'setting_meta_box' ),                'wphave_admin_page', 'section_meta_boxes' );

			/****************
			* DASHBOARD WIDGETS
			****************/
			
			add_settings_section( 
				'section_db_widgets', 
				'<span id="index_dashboard" class="wpat-page-index"></span>' . $this->headings['head_dashboard'], 
				array( $this, 'wphave_admin_section_db_widgets' ), 
				'wphave_admin_page' 
			);	

			add_settings_field( 'dbw_quick_press',          $this->labels['dbw_quick_press'],                array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_right_now',            $this->labels['dbw_right_now'],                  array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_activity',             $this->labels['dbw_activity'],                   array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_primary',              $this->labels['dbw_primary'],                    array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_welcome',              $this->labels['dbw_welcome'],                    array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_user_log',        $this->labels['dbw_wpat_user_log'],              array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_sys_info',        $this->labels['dbw_wpat_sys_info'],              array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_count_post',      $this->labels['dbw_wpat_count_post'],            array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_count_page',      $this->labels['dbw_wpat_count_page'],            array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_count_comment',   $this->labels['dbw_wpat_count_comment'],         array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_recent_post',     $this->labels['dbw_wpat_recent_post'],           array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_recent_page',     $this->labels['dbw_wpat_recent_page'],           array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_recent_comment',  $this->labels['dbw_wpat_recent_comment'],        array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );
			add_settings_field( 'dbw_wpat_memory',          $this->labels['dbw_wpat_memory'],                array( $this, 'setting_db_widgets' ),              'wphave_admin_page', 'section_db_widgets' );

			/****************
			* WIDGETS
			****************/
			
			add_settings_section( 
				'section_widgets', 
				'<span id="index_widget" class="wpat-page-index"></span>' . $this->headings['head_widget'], 
				array( $this, 'wphave_admin_section_widgets' ), 
				'wphave_admin_page' 
			);	

			add_settings_field( 'wt_pages',                 $this->labels['wt_pages'],                       array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_archives',              $this->labels['wt_archives'],                    array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_calendar',              $this->labels['wt_calendar'],                    array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_meta',                  $this->labels['wt_meta'],                        array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_search',                $this->labels['wt_search'],                      array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_text',                  $this->labels['wt_text'],                        array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_categories',            $this->labels['wt_categories'],                  array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_recent_posts',          $this->labels['wt_recent_posts'],                array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_recent_comments',       $this->labels['wt_recent_comments'],             array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_rss',                   $this->labels['wt_rss'],                         array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_tag_cloud',             $this->labels['wt_tag_cloud'],                   array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_nav',                   $this->labels['wt_nav'],                         array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_image',                 $this->labels['wt_image'],                       array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_audio',                 $this->labels['wt_audio'],                       array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_video',                 $this->labels['wt_video'],                       array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_gallery',               $this->labels['wt_gallery'],                     array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );
			add_settings_field( 'wt_html',                  $this->labels['wt_html'],                        array( $this, 'setting_widgets' ),                 'wphave_admin_page', 'section_widgets' );

			/****************
			* FRONTEND
			****************/
			
			add_settings_section( 
				'section_frontend', 
				'<span id="index_frontend" class="wpat-page-index"></span>' . $this->headings['head_frontend'], 
				array( $this, 'wphave_admin_section_frontend' ), 
				'wphave_admin_page' 
			);	

			add_settings_field( 'wp_header_code',           $this->labels['wp_header_code'],                 array( $this, 'setting_frontend' ),                'wphave_admin_page', 'section_frontend' );
			add_settings_field( 'wp_footer_code',           $this->labels['wp_footer_code'],                 array( $this, 'setting_frontend' ),                'wphave_admin_page', 'section_frontend' );
			add_settings_field( 'meta_referrer_policy',     $this->labels['meta_referrer_policy'],           array( $this, 'setting_frontend' ),                'wphave_admin_page', 'section_frontend' );

			/****************
			* OPTIMIZIATION
			****************/
			
			add_settings_section( 
				'section_optimization', 
				'<span id="index_optimize" class="wpat-page-index"></span>' . $this->headings['head_optimize'], 
				array( $this, 'wphave_admin_section_optimization' ), 
				'wphave_admin_page' 
			);		

			add_settings_field( 'wp_version_tag',           $this->labels['wp_version_tag'],                 array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_emoji',                 $this->labels['wp_emoji'],                       array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_feed_links',            $this->labels['wp_feed_links'],                  array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' ); 
			add_settings_field( 'wp_rsd_link',              $this->labels['wp_rsd_link'],                    array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_wlwmanifest',           $this->labels['wp_wlwmanifest'],                 array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_shortlink',             $this->labels['wp_shortlink'],                   array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_rest_api',              $this->labels['wp_rest_api'],                    array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_oembed',                $this->labels['wp_oembed'],                      array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_xml_rpc',               $this->labels['wp_xml_rpc'],                     array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_heartbeat',             $this->labels['wp_heartbeat'],                   array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_rel_link',              $this->labels['wp_rel_link'],                    array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			add_settings_field( 'wp_self_pingback',         $this->labels['wp_self_pingback'],               array( $this, 'setting_wp_optimization' ),         'wphave_admin_page', 'section_optimization' );
			
			/****************
			* MULTISITE
			****************/
			
			// Show this option only for blog id "1"
			if( is_multisite() && get_current_blog_id() == '1' ) {
			
				add_settings_section( 
					'section_multisite', 
					'<span id="index_ms" class="wpat-page-index"></span>' . $this->headings['head_ms'], 
					array( $this, 'wphave_admin_section_multisite' ), 
					'wphave_admin_page' 
				);		

				add_settings_field( 'disable_theme_options',    $this->labels['disable_theme_options'],          array( $this, 'setting_disable_theme_options' ),   'wphave_admin_page', 'section_multisite' );
				add_settings_field( 'disable_plugin_subsite',   $this->labels['disable_plugin_subsite'],          array( $this, 'setting_disable_plugin_subsite' ),   'wphave_admin_page', 'section_multisite' );
				
			}
		}


		/*
		 *******************
		 * VALIDATE ALL SETTINGS FIELDS
		 *******************
		 *
		 *	A callback function that sanitizes the option's value.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/
		
		function wphave_admin_validate_settings( $fields ) {

			$validation = array();
			
			foreach( $fields as $key => $value ) {

				$option = isset( $this->options[$key] ) ? $this->options[$key] : $this->pre_options[$key];
				
				// Validate the fields
				$validation[ $key ] = strip_tags( stripslashes( $value ) );
				
				// Validate code fields only
				if( $key === 'wp_header_code' || $key === 'wp_footer_code' ) {
					$validation[ $key ] = $value;
				}
				
				// Validate color fields only
				if( $key === 'theme_color' || $key === 'theme_background' || $key === 'theme_background_end' ) {

					// Check color is empty (or cleared by user)
					if( empty( $value ) ) {
						// Empty value
						$validation[ $key ] = '';

					// Check if is a valid hex color    
					} elseif( false == $this->wphave_admin_check_color( $value ) ) {                    

						// Color code is not valid, so get the pre saved color values
						$validation[ $key ] = isset( $this->options[$key] ) ? $this->options[$key] : $this->pre_options[$key];  
						
						// Invalid color notice
						if( ! empty( $key ) ) {
							add_settings_error(
								'wphave_admin_page', 'save_updated', esc_html__( 'Invalid Color for', 'wphave-admin' ) . ' ' . $this->labels[ $key ] . ' ' . esc_html__( 'with the value' )  . ' "' . $value . '"! ' . esc_html__( 'The old value has been restored.', 'wphave-admin' ), 'error' 
							);
						} 

					// Get validated new hex code
					} else {
						$validation[ $key ] = $value;
					}

				}
				
				if( empty( $validation[ $key ] ) ) {
					$validation[ $key ] = $this->pre_options[$key];
				}				
					
				// Validate code fields only
				if( $key === 'login_title' ) {
					if( empty( $value ) ) {
						// Do not save the pre option, if the custom login title is empty
						$validation[ $key ] = '';	
					}

				}
				
				// Validate google font fields only
				if( $key === 'google_webfont' || $key === 'google_webfont_weight' ) {
					// Clear the string
					$validation[ $key ] = strip_tags( stripslashes( wphave_admin_clear_google_font_string( $value ) ) );
				}	
				
				// Get specific update notice
				if( $validation[ $key ] == $key && $value == $option ) {
					// Specific field has been not updated (new value == old value)
					//add_settings_error('wphave_admin_page', 'save_updated', esc_html__( 'nothing has been updated', 'wphave-admin' ), 'error' );
				}
				
				// Specific field has been updated
				if( $value != $option ) {
					add_settings_error(
						'wphave_admin_page', 'save_updated', $this->labels[ $key ] . ' ' . esc_html__( 'with the value' )  . ' "' . $value . '" ' . esc_html__( 'has been updated.', 'wphave-admin' ), 'updated' 
					);
				}

			}

			// Reset all fields to default theme options
			if( isset( $_POST['reset'] ) ) {

				add_settings_error(
					'wphave_admin_page', 'reset_error', esc_html__( 'All fields has been restored.', 'wphave-admin' ), 'updated' 
				);

				// Restore all options to pre defined values
				return $this->pre_options;

			}

			add_settings_error(
				'wphave_admin_page', 'save_updated', esc_html__('Settings saved.', 'wphave-admin'), 'updated' 
			);

			// Validate all
			return apply_filters( 'wphave_admin_validate_settings', $validation, $fields);

		}

		/*
		 *******************
		 * VALIDATE HEX CODE
		 *******************
		 *
		 *	Function that will check if value is a valid HEX color.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/
		
		function wphave_admin_check_color( $value ) {

			// If user insert a HEX color with #
			if( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) {
				return true;
			}

			return false;
		}


		/*
		 *******************
		 * SETTINGS SECTIONS CALLBACKS
		 *******************
		 *
		 *	Output content at the top of the section (between heading and fields).
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/
		
		function wphave_admin_section_design() {
			/* Leave blank */
		}
		
		function wphave_admin_section_menu() {
			/* Leave blank */
		}

		function wphave_admin_section_toolbar() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_view() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_colors() {
			/* Leave blank */ 
		}

		function wphave_admin_section_login() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_footer() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_css() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_media() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_plugin_pages() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_multisite() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_optimization() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_meta_boxes() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_db_widgets() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_widgets() { 
			/* Leave blank */ 
		}

		function wphave_admin_section_frontend() { 
			/* Leave blank */ 
		}


		/*
		 *******************
		 * SETTINGS CALLBACKS
		 *******************
		 *
		 *	Output content of the setting (after section heading).
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/		

		/****************
		* THEME COLOR
		****************/
		
		function setting_color() {

			$theme_color = wphave_option('theme_color'); ?>

			<input type="text" name="wp_admin_theme_settings_options[theme_color]" value="<?php echo esc_html( $theme_color ); ?>" class="cpa-color-picker">

			<p class="description">
				<?php echo esc_html__( 'Select a custom theme color.', 'wphave-admin' ); ?>
			</p>

		<?php }
		
		/****************
		* THEME GRADIENT COLOR / START + END
		****************/

		function setting_background() {

			$theme_background = wphave_option('theme_background');
			$theme_background_end = wphave_option('theme_background_end'); ?>

			<?php if( ! wphave_option('spacing') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" name="wp_admin_theme_settings_options[theme_background]" value="<?php echo esc_html( $theme_background ); ?>" class="cpa-color-picker">

				<label for="theme_background" class="color-picker">
					<?php echo esc_html__( 'Start Color', 'wphave-admin' ); ?>
				</label>

				<input type="text" name="wp_admin_theme_settings_options[theme_background_end]" value="<?php echo esc_html( $theme_background_end ); ?>" class="cpa-color-picker">

				<label for="theme_background_end" class="color-picker">
					<?php echo esc_html__( 'End Color', 'wphave-admin' ); ?>
				</label>

				<p class="description">
					<?php echo esc_html__( 'Select a custom background gradient color.', 'wphave-admin' ); ?>
				</p>
					
			<?php if( ! wphave_option('spacing') ) { ?>
				</div>
			<?php } ?>

		<?php }
		
		/****************
		* SPACING
		****************/

		function setting_spacing() {

			$spacing = wphave_option('spacing');
			$spacing_max_width = wphave_option('spacing_max_width');
			
			$status = 'hidden';
			$label = esc_html__( 'Disabled', 'wphave-admin' );
			if( $spacing ) { 
				$status = 'visible';
				$label = esc_html__( 'Enabled', 'wphave-admin' );				
			} ?>

			<input type="checkbox" id="spacing" name="wp_admin_theme_settings_options[spacing]"<?php if( $spacing ) { ?> checked="checked"<?php } ?> />

			<label for="spacing">
				<?php echo esc_html__( 'Enable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<small class="wpat-info note">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use plugins with fixed positioned containers, this option is not recommended.', 'wphave-admin' ); ?>
			</small>

			<?php if( ! $spacing ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<br>

				<label class="wpat-nextto-input" for="spacing_max_width">
					<?php echo  esc_html__( 'Max Width', 'wphave-admin' ); ?>
				</label>
				
				<input class="wpat-range-value"  type="range" id="spacing_max_width" name="wp_admin_theme_settings_options[spacing_max_width]" value="<?php echo esc_html( $spacing_max_width ); ?>" min="1000" max="2600" />
				<span class="wpat-input-range"><span>2000</span></span>
				
				<label for="spacing_max_width">
					<?php echo esc_html__( 'Pixel', 'wphave-admin' ); ?>
				</label>

			<?php if( ! $spacing ) { ?>
				</div>
			<?php } ?>

		<?php }
		
		/****************
		* USER BOX
		****************/
		
		function setting_user_box() {
			
			$user_box = wphave_option('user_box');

			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $user_box ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );				
			} ?>

			<input type="checkbox" id="user_box" name="wp_admin_theme_settings_options[user_box]"<?php if( $user_box ) { ?> checked="checked"<?php } ?> />

			<label for="user_box">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Show the currently logged in username and avatar above the menu items.', 'wphave-admin' ); ?>
			</p>

		<?php }

		/****************
		* COMPANY BOX
		****************/

		function setting_company_box() {

			$company_box = wphave_option('company_box');
			$company_box_logo = wphave_option('company_box_logo');
			$company_box_logo_size = wphave_option('company_box_logo_size');
			
			$bg_image = wphave_admin_path('assets/img/no-thumb.jpg');
			if( $company_box_logo ) {
				$bg_image = $company_box_logo;
			}

			if( wphave_option('user_box') ) { ?>
				<div class="wpat-inactive-option">
			<?php }
					
				$status = 'hidden';
				$label = esc_html__( 'Disabled', 'wphave-admin' );
				if( $company_box ) { 
					$status = 'visible';
					$label = esc_html__( 'Enabled', 'wphave-admin' );					
				} ?>

				<input type="checkbox" id="company_box" name="wp_admin_theme_settings_options[company_box]"<?php if( $company_box ) { ?> checked="checked"<?php } ?> />

				<label for="company_box">
					<?php echo esc_html__( 'Enable', 'wphave-admin' ) ?>
					<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
				</label>

				<p class="description">
					<?php echo esc_html__( 'Show a logo box instead of the user box.', 'wphave-admin' ); ?>
				</p>

				<small class="wpat-info">
					<?php echo esc_html__( 'The user box must be enabled.', 'wphave-admin' ); ?>
				</small>
					
				<br>

				<label for="company_box_logo">
					<?php echo esc_html__( 'Logo', 'wphave-admin' ); ?>
				</label>
					
				<input type="text" id="company_box_logo" name="wp_admin_theme_settings_options[company_box_logo]" value="<?php echo esc_html__( $company_box_logo ); ?>" /> 
				<input id="company_box_logo_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wphave-admin' ); ?>" />

				<label class="wpat-nextto-input" for="company_box_logo_size" style="margin-left: 30px">
					<?php echo esc_html__( 'Logo Size', 'wphave-admin' ); ?>
				</label>
					
				<input class="wpat-range-value" type="range" id="company_box_logo_size" name="wp_admin_theme_settings_options[company_box_logo_size]" value="<?php echo esc_html__( $company_box_logo_size ); ?>" min="100" max="300" />
				<span class="wpat-input-range"><span>140</span></span>
					
				<label for="company_box_logo_size">
					<?php echo esc_html__( 'Pixel', 'wphave-admin' ); ?>
				</label>

				<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>

			<?php if( wphave_option('user_box') ) { ?>
				</div>
			<?php }

		}

		/****************
		* THUMBNAILS
		****************/

		function setting_thumbnail() {

			$thumbnail = wphave_option('thumbnail');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $thumbnail ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="thumbnail" name="wp_admin_theme_settings_options[thumbnail]"<?php if( $thumbnail ) { ?> checked="checked"<?php } ?> />

			<label for="thumbnail">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display a thumbnail column before the title for post and page table lists', 'wphave-admin' ); ?>.
			</p>

		<?php }

		/****************
		* POST/PAGE IDS
		****************/

		function setting_post_page_id() {

			$post_page_id = isset( $this->options['post_page_id'] ) ? $this->options['post_page_id'] : $this->pre_options['post_page_id'];
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $post_page_id ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="post_page_id" name="wp_admin_theme_settings_options[post_page_id]"<?php if( $post_page_id ) { ?> checked="checked"<?php } ?> />

			<label for="post_page_id">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display a IDs column for post and page table lists', 'wphave-admin' ); ?>.
			</p>

		<?php }

		/****************
		* HIDE CONTEXTUAL HELP
		****************/

		function setting_hide_help() {

			$hide_help = wphave_option('hide_help');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_help ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="hide_help" name="wp_admin_theme_settings_options[hide_help]"<?php if( $hide_help ) { ?> checked="checked"<?php } ?> />

			<label for="hide_help">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Hide the contextual help at the top right side', 'wphave-admin' ); ?>.
			</p>

		<?php }

		/****************
		* HIDE SCREEN OPTIONS
		****************/

		function setting_hide_screen_option() {

			$hide_screen_option = wphave_option('hide_screen_option');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_screen_option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="hide_screen_option" name="wp_admin_theme_settings_options[hide_screen_option]"<?php if( $hide_screen_option ) { ?> checked="checked"<?php } ?> />

			<label for="hide_screen_option">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Hide the screen options at the top right side', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* LEFT MENU WIDTH
		****************/

		function setting_left_menu_width() {

			$left_menu_width = wphave_option('left_menu_width'); ?>

			<input class="wpat-range-value" type="range" id="left_menu_width" name="wp_admin_theme_settings_options[left_menu_width]" value="<?php echo esc_html( $left_menu_width ); ?>" min="160" max="400" />
			<span class="wpat-input-range"><span>160</span></span>

			<label for="left_menu_width">
				<?php echo esc_html__( 'Pixel', 'wphave-admin' ); ?>
			</label>

			<small class="wpat-info note">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use plugins with fixed positioned containers, this option is not recommended.', 'wphave-admin' ); ?>
			</small>

		<?php }
		
		/****************
		* LEFT MENU EXPANDABLE
		****************/

		function setting_left_menu_expand() {

			$left_menu_expand = wphave_option('left_menu_expand');
			
			$status = 'hidden';
			$label = esc_html__( 'Disabled', 'wphave-admin' );
			if( $left_menu_expand ) { 
				$status = 'visible';
				$label = esc_html__( 'Enabled', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="left_menu_expand" name="wp_admin_theme_settings_options[left_menu_expand]"<?php if( $left_menu_expand ) { ?> checked="checked"<?php } ?> />

			<label for="left_menu_expand">
				<?php echo esc_html__( 'Enable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Use an accordion menu instead of the default menu.', 'wphave-admin' ); ?>
			</p>

		<?php }
		
		/****************
		* GOOGLE FONT
		****************/

		function setting_google_webfont() {

			$google_webfont = wphave_option('google_webfont');
			$google_webfont_weight = wphave_option('google_webfont_weight'); ?>

			<p>
				<input type="text" id="google_webfont" name="wp_admin_theme_settings_options[google_webfont]" value="<?php echo esc_html( $google_webfont ); ?>" size="60" placeholder="Open+Sans" />&nbsp;&nbsp;

				<label for="google_webfont">
					<?php echo esc_html__( 'Font-Family', 'wphave-admin' ); ?>
				</label>
				
			</p>

			<p>
				<input type="text" id="google_webfont_weight" name="wp_admin_theme_settings_options[google_webfont_weight]" value="<?php echo esc_html( $google_webfont_weight ); ?>" size="60" placeholder="300,400,400i,700" />&nbsp;&nbsp;
			
				<label for="google_webfont_weight">
					<?php echo esc_html__( 'Font-Weight', 'wphave-admin' ); ?>
				</label>
			</p>

			<small class="wpat-info">
				<?php $url = '<a target="_blank" rel="noopener" href="https://fonts.google.com/">Google Fonts</a>';			
				printf( wp_kses_post( __( 'Please separate %1$s in Font-Name and Font-Weight like this example: [Font-Family = "Roboto"] and [Font-Weight = "400,400i,700"].', 'wphave-admin' ) ), $url ); ?>
			</small>

		<?php }
		
		/****************
		* TOOLBAR
		****************/

		function setting_toolbar() {

			$toolbar = wphave_option('toolbar');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $toolbar ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="toolbar" name="wp_admin_theme_settings_options[toolbar]"<?php if( $toolbar ) { ?> checked="checked"<?php } ?> />

			<label for="toolbar">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<small class="wpat-info note">
				<?php echo esc_html__( 'This option may affect the design of some WordPress plugins. If you use plugins with fixed positioned containers, this option is not recommended.', 'wphave-admin' ); ?>
			</small>

		<?php }
		
		/****************
		* TOOLBAR COMMENTS MENU
		****************/

		function setting_hide_adminbar_comments() {

			$hide_adminbar_comments = wphave_option('hide_adminbar_comments');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_adminbar_comments ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} 

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_comments" name="wp_admin_theme_settings_options[hide_adminbar_comments]"<?php if( $hide_adminbar_comments ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_comments">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Comments Menu from the upper toolbar', 'wphave-admin' ); ?>.
			</p>
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR NEW CONTENT MENU
		****************/

		function setting_hide_adminbar_new() {

			$hide_adminbar_new = wphave_option('hide_adminbar_new');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_adminbar_new ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} 

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_new" name="wp_admin_theme_settings_options[hide_adminbar_new]"<?php if( $hide_adminbar_new ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_new">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress New Content Menu from the upper toolbar', 'wphave-admin' ); ?>.
			</p>
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR CUSTOMIZER LINK
		****************/

		function setting_hide_adminbar_customize() {

			$hide_adminbar_customize = wphave_option('hide_adminbar_customize');
						
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_adminbar_customize ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} 

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_customize" name="wp_admin_theme_settings_options[hide_adminbar_customize]"<?php if( $hide_adminbar_customize ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_customize">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Customize Link from the upper frontend toolbar', 'wphave-admin' ); ?>.
			</p>
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR SEARCH
		****************/
		
		function setting_hide_adminbar_search() {

			$hide_adminbar_search = wphave_option('hide_adminbar_search');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $hide_adminbar_search ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} 

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="hide_adminbar_search" name="wp_admin_theme_settings_options[hide_adminbar_search]"<?php if( $hide_adminbar_search ) { ?> checked="checked"<?php } ?> />

			<label for="hide_adminbar_search">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Search from the upper frontend toolbar', 'wphave-admin' ); ?>.
			</p>
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR WP ICON
		****************/

		function setting_toolbar_wp_icon() {

			$toolbar_wp_icon = wphave_option('toolbar_wp_icon');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $toolbar_wp_icon ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} 

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="toolbar_wp_icon" name="wp_admin_theme_settings_options[toolbar_wp_icon]"<?php if( $toolbar_wp_icon ) { ?> checked="checked"<?php } ?> />

			<label for="toolbar_wp_icon">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the WordPress Menu and Icon from the upper toolbar', 'wphave-admin' ); ?>.
			</p>
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR CUSTOM ICON
		****************/

		function setting_toolbar_icon() {

			$toolbar_icon = wphave_option('toolbar_icon');

			$bg_image = wphave_admin_path('assets/img/no-thumb.jpg');
			if( $toolbar_icon ) {
				$bg_image = $toolbar_icon;
			}

			if( wphave_option('toolbar') || wphave_option('toolbar_wp_icon') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" id="toolbar_icon" name="wp_admin_theme_settings_options[toolbar_icon]" value="<?php echo esc_html( $toolbar_icon ); ?>" />
				<input id="toolbar_icon_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wphave-admin' ); ?>" />

				<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>
			
				<p class="description">
					<?php echo esc_html__( 'Upload a custom icon instead of the WordPress icon', 'wphave-admin' ); ?>.
				</p>

				<small class="wpat-info">
					<?php echo esc_html__( 'Recommended image size is 26 x 26px.', 'wphave-admin' ); ?>
				</small>

			<?php if( wphave_option('toolbar') || wphave_option('toolbar_wp_icon') ) { ?>
				</div>
			<?php }

		}
		
		/****************
		* TOOLBAR COLOR
		****************/

		function setting_toolbar_color() {

			$toolbar_color = wphave_option('toolbar_color');

			if( wphave_option('toolbar') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" name="wp_admin_theme_settings_options[toolbar_color]" value="<?php echo esc_html( $toolbar_color ); ?>" class="cpa-color-picker">
					
			<?php if( wphave_option('toolbar') ) { ?>
				</div>
			<?php } ?>

		<?php }
		
		/****************
		* HOWDY GREETING
		****************/

		function setting_howdy_greeting() {

			$howdy_greeting = wphave_option('howdy_greeting');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $howdy_greeting ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="howdy_greeting" name="wp_admin_theme_settings_options[howdy_greeting]"<?php if( $howdy_greeting ) { ?> checked="checked"<?php } ?> />

			<label for="howdy_greeting">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the default Wordpress "Howdy" greeting.', 'wphave-admin' ); ?>
			</p>

		<?php }
		
		/****************
		* HOWDY GREETING TEXT
		****************/

		function setting_howdy_greeting_text() {

			$howdy_greeting_text = wphave_option('howdy_greeting_text');
			
			if( wphave_option('howdy_greeting') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" name="wp_admin_theme_settings_options[howdy_greeting_text]" value="<?php echo esc_html( $howdy_greeting_text ); ?>" size="60">

			<?php if( wphave_option('howdy_greeting') ) { ?>
				</div>
			<?php } ?>

			<p class="description">
				<?php echo esc_html__( 'Enter a custom greeting instead of "Howdy".', 'wphave-admin' ); ?>
			</p>

		<?php }
		
		/****************
		* DISABLE LOGIN
		****************/

		function setting_login_disable() {

			$login_disable = wphave_option('login_disable');

			$status = 'visible';
			$label = esc_html__( 'Enabled', 'wphave-admin' );
			if( $login_disable ) { 
				$status = 'hidden';
				$label = esc_html__( 'Disabled', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="login_disable" name="wp_admin_theme_settings_options[login_disable]"<?php if( $login_disable ) { ?> checked="checked"<?php } ?> />

			<label for="login_disable">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'It is useful if you have an other login plugin installed. This is preventing conflicts with other plugins', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* LOGIN TITLE
		****************/

		function setting_login_title() {

			$login_title = wphave_option('login_title');

			if( wphave_option('login_disable') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" name="wp_admin_theme_settings_options[login_title]" value="<?php echo esc_html( $login_title ); ?>" size="60">

			<?php if( wphave_option('login_disable') ) { ?>
				</div>
			<?php }
		
		}
		
		/****************
		* LOGIN LOGO + SIZE
		****************/

		function setting_logo_upload() {

			$logo_upload = wphave_option('logo_upload');
			$logo_size = wphave_option('logo_size');

			$logo_image = wphave_admin_path('assets/img/no-thumb.jpg');
			if( $logo_upload ) {
				$logo_image = $logo_upload;
			}

			if( wphave_option('login_disable') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" id="logo_upload" name="wp_admin_theme_settings_options[logo_upload]" value="<?php echo esc_html( $logo_upload ); ?>" />
			<input id="logo_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wphave-admin' ); ?>" />

			<label class="wpat-nextto-input" for="logo_size" style="margin-left: 30px">
				<?php echo esc_html__( 'Logo Size', 'wphave-admin' ); ?>
			</label>
					
			<input class="wpat-range-value" type="range" id="logo_size" name="wp_admin_theme_settings_options[logo_size]" value="<?php echo esc_html( $logo_size ); ?>" min="100" max="400" />
			<span class="wpat-input-range"><span>200</span></span>
					
			<label for="logo_size" class="logo-size">
				<?php echo esc_html__( 'Pixel', 'wphave-admin' ); ?>
			</label>
					
			<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $logo_image ); ?>)"></div>
			
			<p class="description">
				<?php echo esc_html__( 'Upload an image for your WordPress login page', 'wphave-admin' ); ?>.
			</p>

			<?php if( wphave_option('login_disable') ) { ?>
				</div>
			<?php }
		
		}
		
		/****************
		* LOGIN BACKGROUND IMAGE
		****************/

		function setting_login_bg() {

			$login_bg = wphave_option('login_bg');

			$bg_image = wphave_admin_path('assets/img/no-thumb.jpg');
			if( $login_bg ) {
				$bg_image = $login_bg;
			}

			if( wphave_option('login_disable') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="text" id="login_bg" name="wp_admin_theme_settings_options[login_bg]" value="<?php echo esc_html( $login_bg ); ?>" />
			<input id="login_bg_upload_button" class="button uploader" type="button" value="<?php echo esc_html__( 'Upload Image', 'wphave-admin' ); ?>" />

			<div class="img-upload-container" style="background-image:url(<?php echo esc_url( $bg_image ); ?>)"></div>
			
			<p class="description">
				<?php echo esc_html__( 'Upload a background image for your WordPress login page', 'wphave-admin' ); ?>.
			</p>

			<?php if( wphave_option('login_disable') ) { ?>
				</div>
			<?php }
		
		}
		
		/****************
		* CREDITS
		****************/

		function setting_credits() {

			$credits = wphave_option('credits');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $credits ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="credits" name="wp_admin_theme_settings_options[credits]"<?php if( $credits ) { ?> checked="checked"<?php } ?> />

			<label for="credits">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Remove the credits note from the footer', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* CREDITS TEXT
		****************/

		function setting_credits_text() {

			$credits_text = wphave_option('credits_text');
			
			if( wphave_option('credits') ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

				<input type="text" name="wp_admin_theme_settings_options[credits_text]" value="<?php echo esc_html( $credits_text ); ?>" size="60">

			<?php if( wphave_option('credits') ) { ?>
				</div>
			<?php } ?>

			<p class="description">
				<?php echo esc_html__( 'Enter a custom credits text.', 'wphave-admin' ); ?>
			</p>

		<?php }
		
		/****************
		* MEMORY USAGE
		****************/

		function setting_memory_usage() {

			$memory_usage = wphave_option('memory_usage');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $memory_usage ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="memory_usage" name="wp_admin_theme_settings_options[memory_usage]"<?php if( $memory_usage ) { ?> checked="checked"<?php } ?> />

			<label for="memory_usage">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the currently memory usage of your WordPress installation', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* MEMORY LIMIT
		****************/

		function setting_memory_limit() {

			$memory_limit = wphave_option('memory_limit');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $memory_limit ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="memory_limit" name="wp_admin_theme_settings_options[memory_limit]"<?php if( $memory_limit ) { ?> checked="checked"<?php } ?> />

			<label for="memory_limit">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the memory limit of your WordPress installation', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* MEMORY AVAILABLE
		****************/

		function setting_memory_available() {

			$memory_available = wphave_option('memory_available');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $memory_available ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="memory_available" name="wp_admin_theme_settings_options[memory_available]"<?php if( $memory_available ) { ?> checked="checked"<?php } ?> />

			<label for="memory_available">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the available server memory for your WordPress installation', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* PHP VERSION
		****************/

		function setting_php_version() {

			$php_version = wphave_option('php_version');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $php_version ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="php_version" name="wp_admin_theme_settings_options[php_version]"<?php if( $php_version ) { ?> checked="checked"<?php } ?> />

			<label for="php_version">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the PHP version of your server', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* IP ADDRESS
		****************/

		function setting_ip_address() {

			$ip_address = wphave_option('ip_address');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $ip_address ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="ip_address" name="wp_admin_theme_settings_options[ip_address]"<?php if( $ip_address ) { ?> checked="checked"<?php } ?> />

			<label for="ip_address">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the IP address of your server', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* WP VERSION
		****************/

		function setting_wp_version() {

			$wp_version = wphave_option('wp_version');
			
			$status = 'visible';
			$label = esc_html__( 'Visible', 'wphave-admin' );
			if( $wp_version ) { 
				$status = 'hidden';
				$label = esc_html__( 'Hidden', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="wp_version" name="wp_admin_theme_settings_options[wp_version]"<?php if( $wp_version ) { ?> checked="checked"<?php } ?> />

			<label for="wp_version">
				<?php echo esc_html__( 'Hide', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Display the installed WordPress version', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* THEME CSS
		****************/

		function setting_css_admin() {

			$css_admin = wphave_option('css_admin'); ?>

			<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_admin]" placeholder=".your-class { color: blue }" /><?php echo esc_html( $css_admin ); ?></textarea>

			<p class="description">
				<?php echo esc_html__( 'Add custom CSS for the Wordpress admin theme. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* LOGIN CSS
		****************/

		function setting_css_login() {

			$css_login = wphave_option('css_login'); ?>

			<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[css_login]" placeholder=".your-class { color: blue }" /><?php echo esc_html( $css_login ); ?></textarea>

			<p class="description">
				<?php echo esc_html__( 'Add custom CSS for the Wordpress login page. To overwrite some classes, use "!important". Like this example "border-right: 3px!important"', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* WP SVG
		****************/

		function setting_wp_svg() {

			$wp_svg = wphave_option('wp_svg');
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wphave-admin' );
			if( $wp_svg ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="wp_svg" name="wp_admin_theme_settings_options[wp_svg]"<?php if( $wp_svg ) { ?> checked="checked"<?php } ?> />

			<label for="wp_svg">
				<?php echo esc_html__( 'Enable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Allow the upload of SVG files', 'wphave-admin' ); ?>.
			</p>           

			<small class="wpat-info">
				<?php $code =  '&lsaquo;?xml version="1.0" encoding="utf-8"?&rsaquo;';
				$plugin = '<a href="https://wordpress.org/plugins/safe-svg/" target="_blank" rel="noopener">Safe SVG</a>';
				printf( wp_kses_post( __( 'Please note, allowing the upload is a security risk. The upload only works if the SVG file in the first line of the file contains %1$s. The upload is only allowed for a user with administrator privileges. The safe way to allow the upload of SVG files is to use a plugin like %2$s.', 'wphave-admin' ) ), $code, $plugin ); ?>
			</small>

		<?php }
		
		/****************
		* WP ICO
		****************/

		function setting_wp_ico() {

			$wp_ico = wphave_option('wp_ico');
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wphave-admin' );
			if( $wp_ico ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="wp_ico" name="wp_admin_theme_settings_options[wp_ico]"<?php if( $wp_ico ) { ?> checked="checked"<?php } ?> />

			<label for="wp_ico">
				<?php echo esc_html__( 'Enable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Allow the upload of ICO files', 'wphave-admin' ); ?>.
			</p>

		<?php }
		
		/****************
		* REMOVE PLUGIN PAGES (REPEATER)
		****************/

		function setting_disable_plugin_pages() {

			// Get all meta box settings fields
			$field = array_shift( $this->plugin_pages_group );
			
			$option = wphave_option($field);
			
			$plugin_system_page = ( $field == 'disable_page_ms' && ! is_multisite() );
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wphave-admin' );
			if( $option || $plugin_system_page ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wphave-admin' );					
			}
			
			// Multisite sync page can not be visible, because WordPress multisite is not activated
			if( $plugin_system_page ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>
					
				<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

				<label for="<?php echo esc_html( $field ); ?>">
					<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
					<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
				</label>

			<?php if( $plugin_system_page ) { ?>
				</div>            

				<small class="wpat-info">
					<?php echo esc_html__( 'Activate multisite support for WordPress to use this option', 'wphave-admin' ); ?>.
				</small>
			<?php }


		} 
		
		/****************
		* DISABLE THEME OPTIONS (MULTISITE)
		****************/

		function setting_disable_theme_options() {

			$disable_theme_options = wphave_option('disable_theme_options');
			
			global $blog_id;
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wphave-admin' );
			if( $disable_theme_options ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wphave-admin' );					
			}

			if( is_multisite() && $blog_id == 1 ) { ?>
				<input type="checkbox" id="disable_theme_options" name="wp_admin_theme_settings_options[disable_theme_options]"<?php if( $disable_theme_options ) { ?> checked="checked"<?php } ?> />
			<?php } else { ?>
				<input type="checkbox" id="#" name="#" disabled="disabled"<?php if( $disable_theme_options ) { ?> checked="checked"<?php } ?> />
			<?php } ?>

			<label for="disable_theme_options">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Disable the permissions to change WP Admin Theme options for all other network sites', 'wphave-admin' ); ?>.
			</p>

			<?php if ( ! is_multisite() ) { ?>
				<small class="wpat-info">
					<?php echo esc_html__( 'Activate multisite support for WordPress to use this option', 'wphave-admin' ); ?>.
				</small>
			<?php } 

		}
		
		/****************
		* DISABLE PLUGIN ACCESS FOR SUBSITES (MULTISITE)
		****************/

		function setting_disable_plugin_subsite() {

			$disable_plugin_subsite = wphave_option('disable_plugin_subsite');
			
			global $blog_id;
			
			$status = 'hidden';
			$label = esc_html__( 'Deactivated', 'wphave-admin' );
			if( $disable_plugin_subsite ) { 
				$status = 'visible';
				$label = esc_html__( 'Activated', 'wphave-admin' );					
			}

			if( is_multisite() && $blog_id == 1 ) { ?>
				<input type="checkbox" id="disable_plugin_subsite" name="wp_admin_theme_settings_options[disable_plugin_subsite]"<?php if( $disable_plugin_subsite ) { ?> checked="checked"<?php } ?> />
			<?php } else { ?>
				<input type="checkbox" id="#" name="#" disabled="disabled"<?php if( $disable_plugin_subsite ) { ?> checked="checked"<?php } ?> />
			<?php } ?>

			<label for="disable_plugin_subsite">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html__( 'Disable the full access to this plugin for all other network sites.', 'wphave-admin' ); ?>
			</p>

			<?php if ( ! is_multisite() ) { ?>
				<small class="wpat-info">
					<?php echo esc_html__( 'Activate multisite support for WordPress to use this option', 'wphave-admin' ); ?>.
				</small>
			<?php } 

		}
		
		/****************
		* WP OPTIMIZATION (REPEATER)
		****************/

		function setting_wp_optimization() {

			// Get all optimization settings fields
			$field = array_shift( $this->optimization_group );

			$option = wphave_option($field[0]);
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wphave-admin' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field[0] ); ?>">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>
			</p>

			<small class="wpat-info">
				<?php echo esc_html( $field[2] ); ?>
			</small>

		<?php }
		
		/****************
		* REMOVE META BOXES (REPEATER)
		****************/

		function setting_meta_box() {

			// Get all meta box settings fields
			$field = array_shift( $this->metabox_group );
			
			$option = wphave_option($field[0]);
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wphave-admin' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field[0] ); ?>">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>.
			</p>

			<?php if( $field[2] ) { ?>
				<small class="wpat-info">
					<?php echo esc_html( $field[2] ); ?>
				</small>
			<?php }

		}
		
		/****************
		* REMOVE WP DASHBOARD WIDGETS (REPEATER)
		****************/

		function setting_db_widgets() {

			// Get all meta box settings fields
			$field = array_shift( $this->db_widget_group );
			
			// System info dashboad widget can not be activated, if the plugin system info page is deactivated
			$plugin_system_page = ( $field == 'dbw_wpat_sys_info' && wphave_option('disable_theme_options') || $field == 'dbw_wpat_sys_info' && wphave_option('disable_plugin_subsite') );
			
			$option = wphave_option($field);
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wphave-admin' );
			if( $option || $plugin_system_page ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wphave-admin' );					
			}

			if( $plugin_system_page ) { ?>
				<div class="wpat-inactive-option">
			<?php } ?>

			<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option || $plugin_system_page ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field ); ?>">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>
					
			<?php if( $plugin_system_page ) { ?>
				</div>

				<small class="wpat-info">
					<?php echo esc_html__( 'System info dashboad widget can not be activated, if the plugin system info page is deactivated', 'wphave-admin' ); ?>.
				</small>
			<?php }

		}
		
		/****************
		* REMOVE WP WIDGETS (REPEATER)
		****************/

		function setting_widgets() {

			// Get all meta box settings fields
			$field = array_shift( $this->widget_group );
			
			$option = wphave_option($field);
			
			$status = 'visible';
			$label = esc_html__( 'Activated', 'wphave-admin' );
			if( $option ) { 
				$status = 'hidden';
				$label = esc_html__( 'Removed', 'wphave-admin' );					
			} ?>

			<input type="checkbox" id="<?php echo esc_html( $field ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

			<label for="<?php echo esc_html( $field ); ?>">
				<?php echo esc_html__( 'Disable', 'wphave-admin' ); ?>
				<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
			</label>

		<?php }
		
		/****************
		* FRONTEND (REPEATER)
		****************/

		function setting_frontend() {

			// Get all frontend settings fields
			$field = array_shift( $this->frontend_group );

			$option = wphave_option($field[0]);
			
			// Meta Referrer Policy Field
			if( $field[0] == 'meta_referrer_policy' ) {

				$items = array(
					'none' => esc_html__( 'Disabled', 'wphave-admin' ),
					'no-referrer' => 'No Referrer',
					'no-referrer-when-downgrade' => 'No Referrer When Downgrade',
					'same-origin' => 'Same Origin',
					'origin' => 'Origin',
					'strict-origin' => 'Strict Origin',
					'origin-when-crossorigin' => 'Origin When Crossorigin',
					'strict-origin-when-crossorigin' => 'Strict Origin When Crossorigin',
					'unsafe-url' => 'Unsafe URL',
				); ?>

				<select id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]">

					<?php foreach( $items as $key => $item ) { ?>
						<option value="<?php echo esc_html( $key ); ?>"<?php if( $option == $key ) { ?> selected="selected"<?php } ?>>
							<?php echo esc_html( $item ); ?>
						</option>
					<?php } ?>

				</select>

			<?php // Header + Footer Code
			} elseif( $field[0] == 'wp_header_code' || $field[0] == 'wp_footer_code' ) { ?>

				<textarea class="option-textarea" type="text" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]" placeholder="<script>alert(\'My custom script\');</script> or <style>.my-class {color: red}</style>" /><?php echo $option; ?></textarea>

			<?php // Other Fields
			} else { 

				$status = 'visible';
				$label = esc_html__( 'Activated', 'wphave-admin' );
				if( $option ) { 
					$status = 'hidden';
					$label = esc_html__( 'Removed', 'wphave-admin' );					
				} ?>   

				<input type="checkbox" id="<?php echo esc_html( $field[0] ); ?>" name="wp_admin_theme_settings_options[<?php echo esc_html( $field[0] ); ?>]"<?php if( $option ) { ?> checked="checked"<?php } ?> />

				<label for="<?php echo esc_html( $field[0] ); ?>">
					<?php echo esc_html__( 'Disable', 'wphave-admin' ) . $field_status; ?>
				</label>

			<?php } ?>

			<p class="description">
				<?php echo esc_html( $field[1] ); ?>
			</p>

			<small class="wpat-info">
				<?php echo esc_html( $field[2] ); ?>
			</small>

		<?php }

	} // end class

	/*
	*  wphave admin settings
	*
	*  The main function responsible for returning the one true wphave Instance to functions everywhere.
	*  Use this function like you would a global variable, except without needing to declare the global.
	*
	*  Example: <?php wphave_admin_settings = wphave_admin_settings(); ?>
	*
	*  @type	function
	*  @date	06/18/19
	*  @since	2.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	if ( ! function_exists( 'wphave_admin_settings' ) ) :

		function wphave_admin_settings() {

			// Globals
			global $wphave_admin_settings;

			// Initialize
			if( ! isset( $wphave_admin_settings ) ) {
				$wphave_admin_settings = new wphave_admin_settings();
				$wphave_admin_settings->initialize();
			}

			// Return
			return $wphave_admin_settings;

		}

	endif;

	// Initialize
	wphave_admin_settings();

endif; // END of class_exists check