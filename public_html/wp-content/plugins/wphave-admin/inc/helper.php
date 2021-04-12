<?php 

/*
 *******************
 * CREATE PLUGIN PATHS
 *******************
 *
 *	By adding custom wp filter, this plugin can be called from theme folder without installing it manually.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_path' ) ) :

    function wphave_admin_path( $path ) {
        
		// Get custom filter path	
        if( has_filter( 'wphave_admin_path' ) ) {
			return apply_filters( 'wphave_admin_path', $path );
		}
        
		// Get plugin path
		return plugins_url( $path, __DIR__ );
        
    }

endif;


if ( ! function_exists( 'wphave_admin_dir' ) ) :

    function wphave_admin_dir( $path ) {

		// Get custom filter dir path
        if( has_filter( 'wphave_admin_dir' ) ) {
			return apply_filters( 'wphave_admin_dir', $path );	
		}
        
		// Get plugin dir path
		return plugin_dir_path( __DIR__ ) . $path;
        
    }

endif;


/*
 *******************
 * HEX TO RGBA
 *******************
 *
 *	Switch a hex color code to a rgba color code.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_hex2rgba' ) ) :

	function wphave_admin_hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		// Return default if no color provided
		if( empty( $color ) ) {
			return $default;
		}

		// Sanitize $color if "#" is provided 
		if( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		// Check if color has 6 or 3 characters and get values
		if( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		// Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		// Check if opacity is set(rgba or rgb)
		if( $opacity ) {
			if( abs( $opacity ) > 1 ) $opacity = 1.0;
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		// Return rgb(a) color string
		return $output;

		/* Usage example:

		$color = '#ffa226';
		$rgb = hex2rgba($color);
		$rgba = hex2rgba($color, 0.7);

		*/

	}

endif;


/*
 *******************
 * GET THE CURRENT POST TYPE
 *******************
 *
 *	Function to return the current post type.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_current_post_type' ) ) :

	function wphave_admin_get_current_post_type() {

		if( ! is_admin() ) {
			return;
		}
		
		global $post, $typenow, $current_screen, $pagenow;

		// We have a post so we can just get the post type from that
		if( $post && $post->post_type ) {
			return $post->post_type;
			
		// Check the global $typenow - set in admin.php
		} elseif( $typenow ) {
			return $typenow;
			
		// Check the global $current_screen object - set in sceen.php
		} elseif( $current_screen && $current_screen->post_type ) {
			return $current_screen->post_type;
			
		// Check the post_type querystring
		} elseif( isset( $_REQUEST['post_type'] ) ) {
			return sanitize_key( $_REQUEST['post_type'] );
			
		// Lastly check if post ID is in query string
		} elseif( isset( $_REQUEST['post'] ) ) {
    		return get_post_type( $_REQUEST['post'] );
  		}

		return null;

	}

endif;


/*
 *******************
 * GET INSTALLATION URL
 *******************
 *
 *	Function to return the root url.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_root_url' ) ) : 

	function wphave_admin_root_url() {

		// Get the full WordPress installation path (included subfolders)
		if( is_multisite() ) {
			return get_site_url( get_current_blog_id() );
		}
		
		return get_site_url();

	}

endif;


/*
 *******************
 * MYSQLI CONNECTION
 *******************
 *
 *	Create a connection to the WordPress MySQL database.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_mysqli_connect' ) ) : 

	function wphave_admin_mysqli_connect() {

		$mysqli = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		// Check for MySQL connection error
		if( ! $mysqli || $mysqli->connect_error ) {
			
			// Close MySQL connection
			mysqli_close( $mysqli );
			
			// Stop connection
			return false;
		}
		
		// Connection successful
		return $mysqli;

	}

endif;


/*
 *******************
 * PLUGIN USAGE
 *******************
 *
 *	Using the "wphave_admin_license_accepted" filter inside a "wphave" theme, allow us to use the "wphave - admin" plugin for free.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

function wphave_admin_plugin_with_theme() {
	
	$permission = 'denied';	
	
	// Current filter for "wphave - Admin"
	if( has_filter('wphave_admin_license_accepted') ) {
		$apply = apply_filters('wphave_admin_license_accepted', $permission);
		if( $apply === 'accepted_by_theme' || $apply === 'accepted' ) {
			return true;
		}
	}
	
	// Old filter for deprecated "WP Admin Theme CD" version
	if( has_filter('wp_admin_theme_cd_accepted') ) {
		$apply = apply_filters('wp_admin_theme_cd_accepted', $permission);
		if( $apply === 'accepted_by_theme' || $apply === 'accepted' ) {
			return true;
		}
	}
	
	return false;
	
}

add_action('admin_init', 'wphave_admin_plugin_with_theme');


/*
 *******************
 * PLUGIN ACTIVATION REDIRECT
 *******************
 *
 *	Redirect for unlicensed users, who visit the main page of the plugin.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
		
function wphave_admin_activation_redirect() {

	if( wphave_admin_activation_status() ) {
		// Stop here, if the license is activated
		return;
	}

	// Check current page is "wphave-admin"
	if( isset( $_GET['page'] ) && $_GET['page'] == 'wphave-admin' ) {
		// Redirect to plugin "wphave-admin-purchase-code&tab=activation" page to verify the plugin
		wp_redirect( admin_url('tools.php?page=wphave-admin-purchase-code&tab=activation') );
		exit();
	}

}

add_action('admin_init', 'wphave_admin_activation_redirect', 1);


/*
 *******************
 * RESTRICT PLUGIN OPTIONS ACCESS
 *******************
 *
 *	Restrict the access to update options for sub sites only.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
		
function wphave_admin_option_access() {

	$access = true;
	
	if( is_multisite() ) {
		$parent_blog_id = '1';
		$blog_option = get_blog_option( $parent_blog_id, 'wp_admin_theme_settings_options');
		
		// Get pre options
		$pre_option = new wphave_admin_settings();
		
		$resctrict_options = isset( $blog_option['disable_theme_options'] ) ? $blog_option['disable_theme_options'] : $pre_option->pre_options['disable_theme_options'];
		$deny_full_access = isset( $blog_option['disable_plugin_subsite'] ) ? $blog_option['disable_plugin_subsite'] : $pre_option->pre_options['disable_plugin_subsite'];
		
		if( $resctrict_options || $deny_full_access ) { // <-- Only the option of the blog ID 1 is essential here
			$access = ( get_current_blog_id() == $parent_blog_id );
		}
	}
	
	return $access;

}


/*
 *******************
 * DENY FULL PLUGIN OPTIONS ACCESS
 *******************
 *
 *	Deny the full access to for sub sites only.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
		
function wphave_admin_deny_access() {

	$access = true;
	
	if( is_multisite() ) {
		$parent_blog_id = '1';
		$blog_option = get_blog_option( $parent_blog_id, 'wp_admin_theme_settings_options');
		
		// Get pre options
		$pre_option = new wphave_admin_settings();
		
		$deny_full_access = isset( $blog_option['disable_plugin_subsite'] ) ? $blog_option['disable_plugin_subsite'] : $pre_option->pre_options['disable_plugin_subsite'];
		
		if( $deny_full_access ) { // <-- Only the option of the blog ID 1 is essential here
			$access = ( get_current_blog_id() == $parent_blog_id );
		}
	}
	
	return $access;

}


/*
 *******************
 * NO ACCESS TEMPLATE PART
 *******************
 *
 *	Output the no access template part with message.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
		
function wphave_admin_no_access() { ?>

	<div class="wrap">
		<?php echo esc_html__( 'You have no permissions to access this page!', 'wphave-admin' ); ?>
	</div>

<?php }


/*
 *******************
 * CLEAR GOOGLE FONT OPTION VALUE
 *******************
 *
 *	Strip districted characters from the google font value.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	2.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_clear_google_font_string' ) ) : 

	function wphave_admin_clear_google_font_string( $string ) {
		
		// Remove all characters from the string with exception of letters and numbers [^a-zA-Z0-9]
		// --> "\s," means the character "," (comma) is an exception of a valid character
		// --> Add more valid characters by adding "\s;", "\s_" or "\s@" to the pattern.
		return preg_replace("/[^a-zA-Z0-9\s,]/", "", $string);
		
	}

endif;