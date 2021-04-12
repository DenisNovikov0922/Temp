<?php 

/*
 *******************
 * ADD PLUGIN SPECIFIC ASSETS
 *******************
 *
 *	Enqueue assets for plugin page only.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_page_assets') ) :

	function wphave_admin_page_assets( $hook ) {

		// Method to get the page hook
		// wp_die($hook);

		// List all plugin pages
		$plugin_pages = array(
			'tools_page_wphave-admin',
			'tools_page_wphave-admin-server-info',
			'tools_page_wphave-admin-wp',
			'tools_page_wphave-admin-constants',
			'tools_page_wphave-admin-error-log',
			'tools_page_wphave-admin-htaccess',
			'tools_page_wphave-admin-php-ini',
			'tools_page_wphave-admin-robots-txt',
			'tools_page_wphave-admin-transient-manager',
			'tools_page_wphave-admin-export',
			'tools_page_wphave-admin-update-network',
			'tools_page_wphave-admin-purchase-code'
		);		
		
		// Load only on admin_toplevel_page?page=mypluginname
		if( ! in_array( $hook, $plugin_pages ) ) {
			return;
		}
		
		// Add admin page css
		wp_enqueue_style( 
			'wphave-admin-page', wphave_admin_path( 'assets/css/less/wphave-admin-page.less' ), array(), null, 'all'
		);
		
		// Add color picker css
		wp_enqueue_style( 'wp-color-picker' );

		// Add media upload js
		wp_enqueue_media();

		// Add plugin js		
		wp_enqueue_script( 
			'wphave-admin-plugin-js', wphave_admin_path( 'assets/js/plugin.js' ), array( 'jquery', 'wp-color-picker' ), null, true 
		);

	}

endif;

add_action( 'admin_enqueue_scripts', 'wphave_admin_page_assets' ); 


/*
 *******************
 * ADD ADMIN ASSETS
 *******************
 *
 *	Enqueue global admin assets.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_assets') ) :

	function wphave_admin_assets() {

		// Add admin css
		wp_enqueue_style( 
			'wphave-admin-main', wphave_admin_path( 'assets/css/less/main.less' ), array(), null, 'all'
		);

		// Add admin rtl css
		if( is_rtl() ) {			
			wp_enqueue_style( 
				'wphave-admin-rtl', wphave_admin_path( 'assets/css/less/rtl.less' ), array(), null, 'all' 
			);		
		}

		// Add custom admin css
		if( wphave_option('css_admin') ) {
			wp_enqueue_style( 
				'wphave-admin-custom', wphave_admin_path( 'assets/css/admin.css' ), array(), filemtime( wphave_admin_dir( 'assets/css/admin.css' ) ), 'all' 
			);
		}

		// Add admin js		
		wp_enqueue_script( 
			'wphave-admin-main-js', wphave_admin_path( 'assets/js/main.js' ), array( 'jquery' ), null, true 
		);

		/*
		 *******************
		 * ADMIN STYLE (DUMMY)
		 *******************
		 *
		 *	Handle for using inline styles.
		 *  ! Notice: Adding a dummy handler is required to assign an inline styles (without dependency) when merging styles into a file.
		*/

		wp_register_style( 'wphave-admin-style', false );
		wp_enqueue_style( 'wphave-admin-style' );	
		
		// Avoiding flickering to reorder the first menu item (User Box) for left toolbar
		$custom_css = "#adminmenu li:first-child { display:none }";
		wp_add_inline_style( 'wphave-admin-style', $custom_css );

		// Add HTML tag style for toolbar hide view
		if( wphave_option('toolbar') && ! wphave_option('spacing') || wphave_option('toolbar') && wphave_option('spacing') ) {
			$toolbar_hide_css = "@media (min-width: 960px) { html.wp-toolbar { padding:0px } }";
			wp_add_inline_style( 'wphave-admin-style', $toolbar_hide_css );
		}

	}

endif;

add_action( 'admin_enqueue_scripts', 'wphave_admin_assets' );


/*
 *******************
 * GOOGLE FONTS
 *******************
 *
 *	Include Google Fonts to WordPress admin.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_google_fonts_url' ) ) :

	function wphave_admin_google_fonts_url( $font_style = '' ) {

		$fonts = '';

		// Get custom font name
		$fonts .= wphave_option('google_webfont');

		// Check if custom font weight exist
		if( ! empty( wphave_option('google_webfont_weight') ) ) {													
			$fonts .= ':' . wphave_option('google_webfont_weight');
		}

		$subset = 'latin,latin-ext';
		
		$font_style = add_query_arg( array(
			'family' => esc_html( $fonts ), // Font url
			'subset' => $subset, // Font script subset
			'display' => 'swap', // Font display
		), 'https://fonts.googleapis.com/css' );
		
		return esc_url_raw( $font_style );
	}

endif;


if ( ! function_exists( 'wphave_admin_include_google_fonts' ) ) :

	function wphave_admin_include_google_fonts() {

		if( ! wphave_option('google_webfont') ) {
			// Stop here, if no fonts are loaded
			return;
		}
		
		wp_enqueue_style( 'wphave_admin_webfonts', wphave_admin_google_fonts_url(), array(), null, 'all' );

	}

endif;

add_action( 'admin_enqueue_scripts', 'wphave_admin_include_google_fonts', 30 );