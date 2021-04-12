<?php

/*
 *******************
 * ADD ADMIN BODY CLASSES
 *******************
 *
 *	Add custom classes to the admin body only.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_body_class' ) ) :

	function wphave_admin_body_class( $classes ) {
        
		$new_classes = array();
		
		$new_classes[] = 'wpat';
		
		// spacing enabled
		if( wphave_option('spacing') ) { 
			$new_classes[] = 'wpat-spacing-on';
		}
		
		// spacing disabled
		if( ! wphave_option('spacing') ) { 
			$new_classes[] = 'wpat-spacing-off';
		}
		
		// toolbar enabled
        if( ! wphave_option('toolbar') ) { 
			$new_classes[] = 'wpat-toolbar-on';
		}
        
		// toolbar disabled
        if( wphave_option('toolbar') ) { 
			$new_classes[] = 'wpat-toolbar-off';
		}
		
		// Left menu is expandable
		if( wphave_option('left_menu_expand') ) { 
			$new_classes[] = 'wpat-menu-left-expand';
		}
		
		// Custom toolbar icon
		if( wphave_option('toolbar_icon') ) {
			$new_classes[] = 'wpat-toolbar-icon';
		}
		
		// Custom web font
		if( wphave_option('google_webfont') ) {
			$new_classes[] = 'wpat-web-font';
		}
		
		// Custom left menu width
		if( ( wphave_option('left_menu_width') >= 190 ) ) {
			$new_classes[] = 'wpat-left-menu-width';
		}		
		
		return $classes . ' ' . implode( ' ', $new_classes );

	}

endif;

add_filter( 'admin_body_class', 'wphave_admin_body_class' );


/*
 *******************
 * WRAP WP ADMIN CONTENT
 *******************
 *
 *	Wrap the entire content with enabled spacing option.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_replace_content' ) ) :

	function wphave_admin_replace_content( $output ) {

		$find = array('/<div id="wpwrap">/', '#</body>#');
		$replace = array('<div class="body-spacer"><div id="wpwrap">', '</div></body>');
		$result = preg_replace( $find, $replace, $output );

		return $result;
		
	}

endif;

if ( ! function_exists( 'wphave_admin_wrap_content' ) ) :

	function wphave_admin_wrap_content() {

		if( ! wphave_option('spacing') ) {
			// Stop here, if disabled
			return;
		}

		ob_start( 'wphave_admin_replace_content' );
	}

endif;

add_action( 'admin_init', 'wphave_admin_wrap_content', 0 );


/*
 *******************
 * EXTRA MENU
 *******************
 *
 *	Show an extra extra menu, if the WordPress toolbar is hidden.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_extra_menu' ) ) :

	function wphave_admin_extra_menu() {

		if( ! wphave_option('toolbar') ) {
			// Stop here, if the toolbar is visible
			return;
		} ?>

		<div class="wpat-logout">
			
			<div class="wpat-logout-button"></div>
			<div class="wpat-logout-content">
				<a target="_blank" rel="noopener" class="btn home-btn" href="<?php echo home_url(); ?>">
					<?php echo esc_html__( 'Home', 'wphave-admin' ); ?>
				</a>
				<?php if( is_multisite() ) { ?>
					<a class="btn multisite-btn" href="<?php echo network_admin_url(); ?>">
						<?php echo esc_html__( 'My Sites', 'wphave-admin' ); ?>
					</a>
				<?php } ?>
				<a class="btn logout-btn" href="<?php echo wp_logout_url(); ?>">
					<?php echo esc_html__( 'Logout', 'wphave-admin' ); ?>
				</a>
			</div>
				
		</div>
			
	<?php }

endif;

add_action('admin_head', 'wphave_admin_extra_menu');


/*
 *******************
 * USER BOX
 *******************
 *
 *	Add a user box to the left menu of WordPress.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_userbox' ) ) :

	function wphave_admin_userbox() {

		if( wphave_option('user_box') || wphave_option('company_box') ) {
			// Stop here, if disabled
			return;
		}

		global $menu, $user_id, $scheme;

		// Get user name and avatar
		$current_user = wp_get_current_user();
		$user_name = $current_user->display_name ;
		$user_avatar = get_avatar( $current_user->user_email, 74 );

		// Get user profile link
		$url = get_dashboard_url( $user_id, 'profile.php', $scheme );
		if ( is_user_admin() ) {
			$url = user_admin_url( 'profile.php', $scheme );
		} elseif ( is_network_admin() ) {
			$url = network_admin_url( 'profile.php', $scheme );
		}		
		
		// Change or remove the greeting
		$disable_greeting = wphave_option('howdy_greeting');
		$custom_greeting = wphave_option('howdy_greeting_text');		
		
		$greeting_text = esc_html__('Howdy', 'wphave-admin');
		if( $custom_greeting ) {
			$greeting_text = $custom_greeting;
		}
		
		$greeting = $greeting_text . ', ';
		if( is_rtl() ) {
			$greeting = ', ' . $greeting_text;
		}		
		
		if( $disable_greeting ) {
			$greeting = '';
		}

		if( is_rtl() ) {
			$html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wphave-admin' ) . '</div></div><div class="adminmenu-user-name"><span>' . esc_html__( $user_name ) . $greeting . '</span></div>';
		} else {
			$html = '<div class="adminmenu-avatar">' . $user_avatar . '<div class="adminmenu-user-edit">' . esc_html__( 'Edit', 'wphave-admin' ) . '</div></div><div class="adminmenu-user-name"><span>' . $greeting . esc_html__( $user_name ) . '</span></div>';
		}

		$menu[0] = array( $html, 'read', $url, 'user-box', 'adminmenu-container');

	}

endif;

add_action('admin_menu', 'wphave_admin_userbox');
	

/*
 *******************
 * COMPANY BOX
 *******************
 *
 *	Add a logo box to the left menu of WordPress.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
	
if ( ! function_exists( 'wphave_admin_companybox' ) ) :

	function wphave_admin_companybox() {

		if( wphave_option('user_box') || ! wphave_option('company_box') ) {
			// Stop here, if disabled
			return;
		}

		global $menu, $user_id, $scheme;

		$blog_name = get_bloginfo( 'name' );
		$site_url = get_bloginfo( 'wpurl' ) . '/';

		$image = esc_html__( 'No image selected.', 'wphave-admin' );
		if( wphave_option('company_box_logo') ) {
			$image = '<img style="width:' . esc_html( wphave_option('company_box_logo_size') ) . 'px" class="company-box-logo" src="' . esc_url( wphave_option('company_box_logo') ) . '" alt="' . esc_attr( $blog_name ) . '">';
		}
		
		$html = '<div class="adminmenu-avatar">';
			$html .= $image;
			$html .= '<div class="adminmenu-user-edit">' . esc_html__( 'Home', 'wphave-admin' ) . '</div>';
		$html .= '</div>';
		
		$html .= '<div class="adminmenu-user-name">';
			$html .= '<span>' . esc_html( $blog_name ) . '</span>';
		$html .= '</div>';

		$menu[0] = array( $html, 'read', $site_url, 'user-box', 'adminmenu-container');

	}

endif;

add_action('admin_menu', 'wphave_admin_companybox');


/*
 *******************
 * FEATURED IMAGE COLUMN
 *******************
 *
 *	Add a featured image column to the post overview of WordPress.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_featured_image_column' ) ) :

	function wphave_admin_featured_image_column() {	

		if( wphave_option('thumbnail') ) {
			// Stop here, if disabled
			return;	
		}		

		/****************
		* GET THE IMAGE
		****************/

		function wphave_admin_featured_image( $post_id ) {

			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			if( $post_thumbnail_id ) {
				$image = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
				return $image[0];
			}

		}

		/****************
		* ADD COLUMN HEAD
		****************/

		function wphave_admin_column_head( $column ) {

			$column['featured_image'] = __( 'Image', 'wphave-admin' );
			return $column;

		}

		/****************
		* ADD COLUMN CONTENT
		****************/

		function wphave_admin_column_content( $column_name, $post_id ) {

			if( $column_name === 'featured_image' ) {
				$post_featured_image = wphave_admin_featured_image( $post_id );
				if( $post_featured_image ) {
					echo '<img src="' . esc_url( $post_featured_image ) . '" />';
				} else {
					echo '<img style="width:55px;height:55px" src="' . wphave_admin_path( 'assets/img/no-thumb.jpg' ) . '" alt="' . __( 'No Thumbnail', 'wphave-admin' ) . '"/>';
				}
			}

		}		

		/****************
		* MOVE THE COLUMN
		****************/

		// Move image column before title column
		function wphave_admin_column_move( $columns ) {

			$new = array();
			foreach( $columns as $key => $title ) {
				if( $key === 'title' ) {
					$new['featured_image'] = __( 'Image', 'wphave-admin' );
				}								
				$new[$key] = $title;
			}
			return $new;

		}
		
		/****************
		* DEFINE ALLoWEd POST TYPES
		****************/
	
		// Get all the custom post types
		$post_types = get_post_types( array( 'public' => true ), 'names', 'and' );
	
		// Create array of allowed post types
		$post_types_with_thumbnail = array();
		
		// Inlcude WP default post types
		$post_types_with_thumbnail[] = 'post';
		//$post_types_with_thumbnail[] = 'page';
		
		foreach( $post_types as $post_type ) {			
			// Check if the post type supports thumbnails
			if( post_type_supports( $post_type, 'thumbnail' ) ) {
				// The include this post type to allow the image column
				$post_types_with_thumbnail[] = $post_type;				
			}			
		}
		
		// Restrict the custom column to post_types with thumbnail support
		$post_types = $post_types_with_thumbnail;
		
		// Exclude product post type, because WooCommerce has own thumbnail column
		if( wphave_admin_get_current_post_type() === 'product' ) {
			return;
		}		
		
		// Add custom column filter and action
		foreach( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", 'wphave_admin_column_head' );
			add_filter( "manage_{$post_type}_posts_columns", 'wphave_admin_column_move' );
			add_action( "manage_{$post_type}_posts_custom_column", 'wphave_admin_column_content', 10, 2 );
		}
		
	}

endif;

add_action('admin_init', 'wphave_admin_featured_image_column');


/*
 *******************
 * POST ID COLUMN
 *******************
 *
 *	Add a post id column to the post overview of WordPress.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_id_column' ) ) :

	function wphave_admin_id_column() {	

		if( wphave_option('post_page_id') ) {
			// Stop here, if disabled
			return;	
		}

		/****************
		* ADD COLUMN HEAD
		****************/
		
		function wphave_admin_post_id_column_head( $defaults ) {

			$defaults['wps_post_id'] = esc_html__('ID', 'wphave-admin');
			return $defaults;

		}

		/****************
		* ADD COLUMN CONTENT
		****************/
		
		function wphave_admin_post_id_column_content( $column_name, $id ) {

			if( $column_name === 'wps_post_id' ) {
				echo esc_html( $id );
			}

		}

		// Restrict the custom column to specific post_types
		$post_types = array( 'post', 'page', 'recipe', 'portfolio', 'product' );
		if( empty( $post_types ) ) {
			return;
		}

		// Add custom column filter and action
		foreach( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", 'wphave_admin_post_id_column_head' );
			add_action( "manage_{$post_type}_posts_custom_column", 'wphave_admin_post_id_column_content', 10, 2 );
		}
		
	}

endif;

add_action('admin_init', 'wphave_admin_id_column');


/*
 *******************
 * TAXONOMY ID COLUMN
 *******************
 *
 *	Add a taxonomy id column to the taxonomy overview of WordPress.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_tax_id_column' ) ) :

	function wphave_admin_tax_id_column() {

		if( wphave_option('post_page_id') ) {
			// Stop here, if disabled
			return;	
		}	

		/****************
		* ADD COLUMN HEAD
		****************/
		
		function wphave_admin_tax_id_column_head( $column ) {
			
			$column['tax_id'] = esc_html__( 'ID', 'wphave-admin' );
			return $column;
			
		}

		/****************
		* ADD COLUMN CONTENT
		****************/
		
		function wphave_admin_tax_id_column_content( $value, $name, $id ) { 
			
			return 'tax_id' === $name ? $id : $value;
			
		}

		// Restrict the custom column to specific tax_types
		$tax_types = array( 
			'category', 
			'post_tag', 
			'product_cat', 
			'product_tag', 
			'page_category', 
			'page_tag', 
			'recipe_category', 
			'recipe_tag', 
			'recipe_ingredient', 
			'recipe_feature', 
			'recipe_cuisine',
			'portfolio_category', 
			'portfolio_tag', 
			'portfolio_client', 
		);

		if( empty( $tax_types ) ) {
			return;
		}

		// Add custom column filter and action
		foreach( $tax_types as $taxonomy ) {
			add_action( "manage_edit-{$taxonomy}_columns", 'wphave_admin_tax_id_column_head' );
			add_filter( "manage_edit-{$taxonomy}_sortable_columns", 'wphave_admin_tax_id_column_head' );
			add_filter( "manage_{$taxonomy}_custom_column", 'wphave_admin_tax_id_column_content', 11, 3 );
		}
		
	}

endif;

add_action('admin_init', 'wphave_admin_tax_id_column');


/*
 *******************
 * PLUGIN PAGE TITLE
 *******************
 *
 *	Create the "wphave - admin" plugin page title.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_title') ) :

	function wphave_admin_title( $title = '' ) {
		
		$title = isset( $title ) && ! empty( $title ) ? $title : WPHAVE_ADMIN_PLUGIN_NAME;
		echo $title;
		
		if ( is_multisite() ) { 
			$text = ' | ' . esc_html__( 'Current Blog ID', 'wphave-admin' ) . ': '. get_current_blog_id(); ?>
			<?php echo wphave_admin_subtitle( $text ); ?>
		<?php } ?>

		<img src="<?php echo esc_url( wphave_admin_path( 'assets/img/wphave-logo.svg' ) ); ?>" alt="wphave Logo" style="width: 160px; float: right">
	
	<?php }

endif;


/*
 *******************
 * PLUGIN PAGE SUBTITLE
 *******************
 *
 *	Create the "wphave - admin" plugin page subtitle.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_subtitle') ) :

	function wphave_admin_subtitle( $text ) { ?>

		<span style="color:#8b959e;"<?php if( is_rtl() ) { echo ' dir="rtl"'; } ?>>
			<?php echo esc_html( $text ); ?>
		</span>
	
	<?php }

endif;


/*
 *******************
 * PLUGIN PAGE TAB MENU
 *******************
 *
 *	Create the "wphave - admin" plugin page tab menu.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_tab_menu') ) :

	function wphave_admin_tab_menu( $active_tab ) { ?>
		
		<div class="nav-tab-wrapper">
			
			<?php if( wphave_admin_activation_status() ) { 
			
				/****************
				* OPTIONS
				****************/
			
				?>
			
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin&tab=options' ); ?>" class="nav-tab<?php echo esc_html( $active_tab == 'options' ? ' nav-tab-active' : '' ); ?>">
					<?php echo esc_html__( 'Options', 'wphave-admin' ); ?>
				</a>
			
				<?php 
						
				/****************
				* TRANSIENT MANAGER
				****************/
		
				if( ! wphave_option('disable_transient_manager') ) { ?>
					<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient' ); ?>" class="nav-tab<?php echo esc_html( $active_tab == 'transient' ? ' nav-tab-active' : '' ); ?>">
						<?php echo esc_html__( 'Transient Manager', 'wphave-admin' ); ?>
					</a>
				<?php }
				
				/****************
				* IM- / EXPORT
				****************/
		
				if( ! wphave_option('disable_page_export') ) { ?>
					<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-export&tab=im-export' ); ?>" class="nav-tab<?php echo esc_html( $active_tab == 'im-export' ? ' nav-tab-active' : '' ); ?>">
						<?php echo esc_html__( 'Im-/Export', 'wphave-admin' ); ?>
					</a>
				<?php }										   
						
				/****************
				* MULTISITE SYNC
				****************/
		
				// Hide the tab for sub sites	
				if( is_multisite() && get_current_blog_id() == '1' ) { ?>
					<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-update-network&tab=multisite' ); ?>" class="nav-tab<?php echo esc_html( $active_tab == 'multisite' ? ' nav-tab-active' : '' ); ?>">
						<?php echo esc_html__( 'Multisite Sync', 'wphave-admin' ); ?>
					</a>
				<?php }
			} 
			
			/****************
			* ACTIVATION
			****************/

			// Hide the tab for sub sites	
			if( ! is_multisite() || is_multisite() && get_current_blog_id() == '1' ) { ?>
			
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-purchase-code&tab=activation' ); ?>" class="nav-tab<?php echo esc_html( $active_tab == 'activation' ? ' nav-tab-active' : '' ); ?>">
					<?php if( ! wphave_admin_activation_status() ) { ?>
						<span style="color:#d63316" class="dashicons dashicons-no-alt"></span>
					<?php } else { ?>
						<span style="color:#8db51e" class="dashicons dashicons-yes"></span>
					<?php }
					echo esc_html__( 'Activation', 'wphave-admin' ); ?>
				</a>
			
			<?php } ?>
			
		</div>

	<?php }

endif;


/*
 *******************
 * PLUGIN SUB PAGE MENU
 *******************
 *
 *	Create the "wphave - admin" plugin subpage menu.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_subpage_menu') ) :

	function wphave_admin_subpage_menu() { 

		$wp = wphave_option('disable_page_wp');
		$constants = wphave_option('disable_page_constants');
		$server = wphave_option('disable_page_system');
		$error_log = wphave_option('disable_page_error_log');
		$htaccess = wphave_option('disable_page_htaccess');
		$php_ini = wphave_option('disable_page_php_ini');
		$robots_txt = wphave_option('disable_page_robots_txt'); ?>
		
		<div class="wpat-page-menu">
			<ul<?php if( is_rtl() ) { echo ' dir="rtl"'; } ?>>
				<?php echo wphave_admin_side_menu_list_items( $wp, $constants, $server, $error_log, $htaccess, $php_ini, $robots_txt ); ?>
			</ul>
		</div>

	<?php }

endif;