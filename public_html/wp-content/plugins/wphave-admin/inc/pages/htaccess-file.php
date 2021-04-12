<?php 

/*
 *******************
 * HTACCESS SUBPAGE
 *******************
 *
 *	Add a subpage for htaccess without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_htaccess_admin_menu' ) ) :

	function wphave_admin_htaccess_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'htaccess', 'wphave-admin' ),
			esc_html__( 'htaccess', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-htaccess',
			'wphave_admin_htaccess_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_htaccess_admin_menu' );


/*
 *******************
 * GET HTACCESS FILE
 *******************
 *
 *	Define the path and get the wp ".htaccess" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_htaccess_file' ) ) :

	function wphave_admin_htaccess_file() {
			
		// Call wp file system
		global $wp_filesystem; 
		WP_Filesystem();
		
		// Get the path of wp ".htaccess" file
		$file = get_home_path() . '.htaccess';
		
		// Check if ".htaccess" file exist
		if( $wp_filesystem->exists( $file ) ) {
			return $file;
		}
		
		// File not exist
		return false;
		
	}

endif;


/*
 *******************
 * GET HTACCESS FILE CONTENT
 *******************
 *
 *	Get the content of the wp ".htaccess" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_htaccess_file_content' ) ) :

	function wphave_admin_htaccess_file_content( $file ) {

		// Call wp file system
		global $wp_filesystem; 
		WP_Filesystem();

		// Show this notice, if no file exist
		$content = esc_html__( '.htaccess file not found!', 'wphave-admin' );

		// Check if ".htaccess" file exist
		if( $wp_filesystem->exists( $file ) ) {
			$content = $wp_filesystem->get_contents( $file );
			
			// Check if the file content is empty
			if( $wp_filesystem->get_contents( $file ) == '' ) {
				$content = esc_html__( 'File content is empty.', 'wphave-admin' );
			}
		}
		
		return $content;
		
	}

endif;


/*
 *******************
 * OUTPUT HTACCESS PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the htaccess subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_htaccess_page' ) ) :

	function wphave_admin_htaccess_page() { 

		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		} ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( '.htaccess', 'wphave-admin' ) ); ?>
			</h1> 
			
			<?php if( wphave_admin_activation_status() ) {
		
				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>
			
				<h2>
					<?php esc_html_e( '.htaccess file', 'wphave-admin' ); ?>
				</h2>

				<p style="color: #ce2754; font-weight: bold">
					<?php $name = '.htaccess';			
					printf( wp_kses_post( __( 'For security reasons you can only read the %1$s file! Please connect to your server and modify the file in a file editor.', 'wphave-admin' ) ), $name ); ?>
				</p>
			
				<p>
					<?php esc_html_e( 'The .htaccess is a distributed configuration file, and is how Apache server handles configuration changes on a per-directory basis.', 'wphave-admin' ); ?>
					<br>
					<a href="https://wordpress.org/support/article/htaccess/" target="_blank" rel="noopener"><?php esc_html_e( 'Learn more about the .htaccess file in WordPress.', 'wphave-admin' ); ?></a>
				</p>
			
				<?php 
		
				// Get the wp ".htaccess" file
				$file = wphave_admin_htaccess_file();
		
				// Get the wp ".htaccess" file content
				$file_content = wphave_admin_htaccess_file_content( $file ); 
			
				if( $file ) { ?>
					<p>
						<?php echo esc_html__( '.htaccess file was found at:', 'wphave-admin' ) . ' <strong>' . esc_html( $file ) . '</strong>'; ?>
						<br>
					</p>
				<?php } ?>
			
				<textarea id="error_log_area" style="width: 100%; height: 1000px; padding: 20px; font-size: 12px; line-height: 1.6; background: #686868; color: #fff; outline: none; resize: none; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', 'monospace'" readonly><?php echo esc_html( $file_content ); ?></textarea>
			
			<?php } else {
		
				echo wphave_admin_plugin_activation_message();
		
			} ?>

		</div>

	<?php }

endif;