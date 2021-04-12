<?php 

/*
 *******************
 * EX-/IMPORT SUBPAGE
 *******************
 *
 *	Add a subpage for ex-/import without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_export_admin_menu' ) ) :

	function wphave_admin_export_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'Im-/Export', 'wphave-admin' ),
			esc_html__( 'Im-/Export', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-export',
			'wphave_admin_export_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_export_admin_menu' );


/*
 *******************
 * EXPORT OPTION SETTINGS
 *******************
 *
 *	Export all options from the current WordPress installation as JSON file.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_export_settings' ) ) :

	function wphave_admin_export_settings() {
		
		if( ! isset( $_POST['wpat_start_export'] ) || 'export_settings' != $_POST['wpat_start_export'] ) {
			return;
		}
			
		if( ! wp_verify_nonce( $_POST['wpat-export'], 'wpat-export' ) ) {
			return;
		}
		
		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}
			
		// Get all WP options data
		//$options = get_alloptions(); 

		// Get specific options data
		//$options = array( 'test' => get_option('test'), 'test2' => get_option('test2') );

		// Get all options data from WP Admin Theme plugin
		$options = array( 'wp_admin_theme_settings_options' => get_option('wp_admin_theme_settings_options') );
		if( is_multisite() ) {
			$options = array( 'wp_admin_theme_settings_options' => get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() ) );
		}		

		$save_options = array();

		foreach( $options as $key => $value ) {
			$value = maybe_unserialize( $value );
			$save_options[$key] = $value;
		}

		// Encode data into json data
		$json_file = json_encode( $save_options );
		
		// Get the blog name
		$blogname = str_replace(' ', '', get_option('blogname'));

		// Get the blog charset
		$charset = get_option('blog_charset');
		
		// Get the current data
		$date = date('m-d-Y');

		// Namming the filename will be generated
		$json_name = $blogname . '-' . $date;		
		
		header( "Content-Type: application/json; charset=$charset" );
		header( "Content-Disposition: attachment; filename=$json_name.json" );
		header( "Pragma: no-cache" );
    	header( "Expires: 0" );
		echo $json_file;
		
		exit();
		
	}

endif;
	
add_action( 'admin_init', 'wphave_admin_export_settings' );


/*
 *******************
 * IMPORT OPTION SETTINGS
 *******************
 *
 *	Import JSON file to set all options from an other WordPress installation .
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_import_settings' ) ) :

	function wphave_admin_import_settings() {

		if( ! isset( $_POST['wpat_start_import'] ) || $_POST['wpat_start_import'] != 'import_settings' ) {
			return;
		}

		if( ! wp_verify_nonce( $_POST['wpat-import'], 'wpat-import' ) ) {
			return;
		}

		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}		
		
		// Get the name of file
		$file = $_FILES['import']['name'];
		
		// Check for uploaded file
		if( empty( $file ) ) {
			return esc_html__( 'Please upload a file to import.', 'wphave-admin' );
		}		

		// Get extension of file
		$tmp = explode('.', $file);
		$file_extension = strtolower( end( $tmp ) );

		// Check for the correct file extension
		if( $file_extension != 'json' ) {
			return esc_html__( 'Please upload a valid .json file.', 'wphave-admin' );	
		}
		
		// Get size of file
		$file_size = $_FILES['import']['size'];

		// Check for the file size is not to large
		if( $file_size > 500000 ) { // <-- 500000 bytes
			return esc_html__( 'Your uploaded file is too large.', 'wphave-admin' );	
		}

		// Ensure uploaded file is JSON file type and the size not over 500000 bytes
		if( ! empty( $file ) && ( $file_extension == 'json' ) && ( $file_size < 500000 ) ) {
			
			$encode_options = file_get_contents( $_FILES['import']['tmp_name'] );
			$options = json_decode( $encode_options, true );
			
			foreach( $options as $key => $value ) {
				if( is_multisite() ) {
					update_blog_option( get_current_blog_id(), $key, $value);
				} else {
					update_option( $key, $value );
				}									
			}
			
			return esc_html__( 'All options are restored successfully.', 'wphave-admin' );
			
		}
			
		// Redirect to option main page
		//wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin&tab=display-options' ) );
		//exit();
		
	}

endif;

add_action( 'admin_init', 'wphave_admin_import_settings' );


/*
 *******************
 * OUTPUT EX-/IMPORT PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the ex-/import subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_export_page' ) ) :

	function wphave_admin_export_page() { 

		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		} ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( 'Im-/Export', 'wphave-admin' ) ); ?>
			</h1> 

			<?php if( wphave_admin_activation_status() ) {

				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'im-export'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Get options preview
				$options = get_option('wp_admin_theme_settings_options');
				if( is_multisite() ) {
					$options = get_blog_option( get_current_blog_id(), 'wp_admin_theme_settings_options', array() );
				} ?>

				<?php if( wphave_admin_import_settings() ) { ?>
					<div class="updated">
						<p>
							<strong><?php echo esc_html( wphave_admin_import_settings() ); ?></strong>
						</p>
					</div>
				<?php } ?>

				<h2>
					<?php esc_html_e( 'Export', 'wphave-admin' ); ?> <?php esc_html_e( 'Options', 'wphave-admin' ); ?>
				</h2>

				<p>
					<?php esc_html_e( 'When you click the Export button, the system will generate a JSON file for you to save on your computer.', 'wphave-admin' ); ?>
					<?php esc_html_e( 'This backup file contains all "wphave - Admin" configuration and setting options from this WordPress installation.', 'wphave-admin' ); ?>
				</p>
			
				<textarea style="width: 100%; padding: 20px; font-size: 12px; line-height: 1.6; background: #686868; color: #fff; outline: none; resize: none; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', 'monospace'" readonly name="value" rows="10" cols="50"><?php foreach( $options as $key => $value ) { echo esc_html( $key . ': ' . $value . '&#13;&#10;' ); } ?></textarea>
			
				<p>
					<?php esc_html_e( 'After exporting, you can either use the JSON file to restore your settings on this site again or another WordPress site.', 'wphave-admin' ); ?>
				</p>

				<form method="post">
					<p class="submit">
						<?php wp_nonce_field( 'wpat-export', 'wpat-export' ); ?>
						<input type="hidden" name="wpat_start_export" value="export_settings" />
						<?php submit_button( __( 'Export all options', 'wphave-admin' ), 'secondary', 'submit', false ); ?>
					</p>
				</form>

				<h2>
					<?php esc_html_e( 'Import', 'wphave-admin' ); ?> <?php esc_html_e( 'Options', 'wphave-admin' ); ?>
				</h2>

				<p>
					<?php esc_html_e( 'Click the Browse button and choose a JSON file.', 'wphave-admin' ); ?>
				</p>

				<form method="post" enctype="multipart/form-data">
					<p>						
						<input type="file" name="import" />		
					</p>
			
					<p>
						<?php esc_html_e( 'Press the Import button restore all options.', 'wphave-admin' ); ?>
					</p>
					
					<p class="submit">
						<?php wp_nonce_field('wpat-import', 'wpat-import'); ?>
						<input type="hidden" name="wpat_start_import" value="import_settings" />
						<?php submit_button( __( 'Import options', 'wphave-admin' ), 'primary', 'submit', false ); ?>
					</p>
				</form>

			<?php } else {
		
				echo wphave_admin_plugin_activation_message();
		
			} ?>

		</div>

	<?php }

endif;