<?php 

/*
 *******************
 * PHP INI SUBPAGE
 *******************
 *
 *	Add a subpage for php ini without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_php_ini_admin_menu' ) ) :

	function wphave_admin_php_ini_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'php.ini', 'wphave-admin' ),
			esc_html__( 'php.ini', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-php-ini',
			'wphave_admin_php_ini_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_php_ini_admin_menu' );


/*
 *******************
 * GET PHP INI FILE
 *******************
 *
 *	Define the path and get the wp "php.ini" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_php_ini_file' ) ) :

	function wphave_admin_php_ini_file() {
			
		// Call wp file system
		global $wp_filesystem; 
		WP_Filesystem();
		
		// Get the path of wp "php.ini" file
		$file = get_home_path() . 'wp-admin/php.ini';
		
		// Check if "php.ini" file exist
		if( $wp_filesystem->exists( $file ) ) {
			return $file;
		}
		
		// File not exist
		return false;
		
	}

endif;


/*
 *******************
 * GET PHP INI FILE CONTENT
 *******************
 *
 *	Get the content of the wp "php.ini" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_php_ini_file_content' ) ) :

	function wphave_admin_php_ini_file_content( $file ) {

		// Call wp file system
		global $wp_filesystem; 
		WP_Filesystem();

		// Show this notice, if no file exist
		$content = esc_html__( 'php.ini file not found!', 'wphave-admin' );

		// Check if "php.ini" file exist
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
 * OUTPUT PHP INI PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the php ini subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_php_ini_page' ) ) :

	function wphave_admin_php_ini_page() { 

		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( 'php.ini', 'wphave-admin' ) ); ?>
			</h1> 
			
			<?php if( wphave_admin_activation_status() ) {
		
				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>

				<p style="color: #ce2754; font-weight: bold">
					<?php $name = 'php.ini';			
					printf( wp_kses_post( __( 'For security reasons you can only read the %1$s file! Please connect to your server and modify the file in a file editor.', 'wphave-admin' ) ), $name ); ?>
				</p>
			
				<p>
					<strong><?php esc_html_e( 'To affect your custom configuration in the php.ini file, you have to save the file in the "../wp-admin" folder.', 'wphave-admin' ); ?></strong>
				</p>
			
				<p>
					<?php esc_html_e( "The PHP configuration file, php.ini, is the final and most immediate way to affect PHP's functionality. The php.ini file is read each time PHP is initialized.", 'wphave-admin' ); ?>
					<br>
					<a href="https://www.php.net/manual/en/ini.core.php" target="_blank" rel="noopener"><?php esc_html_e( 'Learn more about the php.ini file.', 'wphave-admin' ); ?></a>
				</p>
			
				<?php 
		
				// Get the wp "php.ini" file
				$file = wphave_admin_php_ini_file();
		
				// Get the wp "php.ini" file content
				$file_content = wphave_admin_php_ini_file_content( $file ); 
			
				if( $file ) { ?>
					<p>
						<?php echo esc_html__( 'php.ini file was found at:', 'wphave-admin' ) . ' <strong>' . esc_html( $file ) . '</strong>'; ?>
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