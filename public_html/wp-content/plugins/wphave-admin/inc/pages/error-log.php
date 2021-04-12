<?php 

/*
 *******************
 * ERROR LOG SUBPAGE
 *******************
 *
 *	Add a subpage for error log without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_error_log_admin_menu' ) ) :

	function wphave_admin_error_log_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'Error Log', 'wphave-admin' ),
			esc_html__( 'Error Log', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-error-log',
			'wphave_admin_error_log_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_error_log_admin_menu' );


/*
 *******************
 * GET ERROR LOG FILE
 *******************
 *
 *	Define the path and get the wp "debug.log" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_error_log_file' ) ) :

	function wphave_admin_error_log_file() {
			
		// Get the path of wp "debug.log"
		$file = get_home_path() . 'wp-content/debug.log';

		// Check for custom "debug.log" file path
		$custom_file_path = ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG != false && WP_DEBUG_LOG != 1 );
		if( $custom_file_path ) {
			$file = WP_DEBUG_LOG;
		}
		
		return $file;
		
	}

endif;


/*
 *******************
 * GET ERROR LOG FILE CONTENT
 *******************
 *
 *	Get the content of the wp "debug.log" file.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_error_log_file_content' ) ) :

	function wphave_admin_error_log_file_content( $file ) {

		// Call wp file system
		global $wp_filesystem; 
		WP_Filesystem();

		// Show this notice, if no file exist
		$content = esc_html__( 'debug.log file not found!', 'wphave-admin' );

		// Check if "debug.log" file exist
		if( $wp_filesystem->exists( $file ) ) {
			$content = $wp_filesystem->get_contents( $file );
			
			// Check if the file content is empty
			if( $wp_filesystem->get_contents( $file ) == '' ) {
				$content = esc_html__( 'File content is empty. No errors logged.', 'wphave-admin' );
			}
		}
		
		return $content;
		
	}

endif;


/*
 *******************
 * INCLUDE ERROR LOG PAGE ASSETS
 *******************
 *
 *	Enqueue assest for error log page only.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

function wphave_admin_error_log_file_js( $hook ) {

	// method to get the page hook
	//wp_die($hook);

	// Load the following JS files only on plugin page
	if( $hook != 'tools_page_wphave-admin-error-log' ) {
		return;
	}

	wp_enqueue_script( 
		'wphave-admin-error-log-js', wphave_admin_path( 'assets/js/error_log.js' ), array( 'jquery' ), null, true 
	);

	wp_localize_script( 
		'wphave-admin-error-log-js', 'wp_ajax_data', array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
	));

	// Localize the script with new data
	$vars = array(
		// --> !!! For security check and verify wp_nonce() before sending AJAX request
		'verify_security_nonce' => wp_create_nonce( 'verify_sec_request' ),
		'label_update' => esc_html__( 'Will be updated', 'wphave-admin' ) . ' ...',
		'label_clear' => esc_html__( 'Will be cleared', 'wphave-admin' ) . ' ...',
		'label_done' => esc_html__( 'Done', 'wphave-admin' ) . '!',
	);
	wp_localize_script( 'wphave-admin-error-log-js', 'WP_JS_Error_Log', $vars );

}

add_action( 'admin_enqueue_scripts', 'wphave_admin_error_log_file_js', 30 );


/*
 *******************
 * REFRESH ERROR LOG FILE CONTENT (AJAX)
 *******************
 *
 *	Refresh the error log file content if the user is clicking on refresh button.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/


function wphave_admin_error_log_file_content_refresh() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get user action to refresh error log file
		$request = json_decode( stripslashes( $_POST['fileData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$file_content = '';
		
		// Check for user refreshing request
		if( $action == 'refresh_error_log' ) {

			// Get the wp "debug.log" file
			$file = wphave_admin_error_log_file();
			
			// Get the wp "debug.log" file content
			$file_content = wphave_admin_error_log_file_content( $file );
			
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'file_content' => $file_content,
		) );			

	}

	die();

}

add_action( 'wp_ajax_wphave_admin_error_log_file_content_refresh', 'wphave_admin_error_log_file_content_refresh' );
add_action( 'wp_ajax_nopriv_wphave_admin_error_log_file_content_refresh', 'wphave_admin_error_log_file_content_refresh' );


/*
 *******************
 * CLEAR ERROR LOG FILE CONTENT (AJAX)
 *******************
 *
 *	Clear the error log file content if the user is clicking on clear button.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

function wphave_admin_error_log_file_content_clear() {

	// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
	check_ajax_referer( 'verify_sec_request', 'security' );

	if( $_POST ) {		

		// Get user action to clear error log file
		$request = json_decode( stripslashes( $_POST['fileData'] ) );

		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$action = $request[0]->command;
		}
		
		$file_content = '';
		
		// Check for user clearing request
		if( $action == 'clear_error_log' ) {

			// Get the wp "debug.log" file
			$file = wphave_admin_error_log_file();
			
			// Call wp file system
			global $wp_filesystem; 
			WP_Filesystem();
			
			// Save no content to "debug.log" file the clear the file content
			$wp_filesystem->put_contents( $file, '', 0644);
			
			// Get the wp "debug.log" file content
			$file_content = wphave_admin_error_log_file_content( $file );			
			
		}

		wp_send_json( array(
			//'success' => 'SUCCESS',
			'file_content' => $file_content,
		) );			

	}

	die();

}

add_action( 'wp_ajax_wphave_admin_error_log_file_content_clear', 'wphave_admin_error_log_file_content_clear' );
add_action( 'wp_ajax_nopriv_wphave_admin_error_log_file_content_clear', 'wphave_admin_error_log_file_content_clear' );


/*
 *******************
 * OUTPUT ERROR LOG PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the error log subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_error_log_page' ) ) :

	function wphave_admin_error_log_page() { 

		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		} ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( 'Error Log', 'wphave-admin' ) ); ?>
			</h1> 
			
			<?php if( wphave_admin_activation_status() ) {
		
				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>

				<?php if( ( defined('WP_DEBUG') && ! WP_DEBUG ) || ( defined('WP_DEBUG_LOG') && ! WP_DEBUG_LOG ) ) { ?>
					<p style="color: #ce2754; font-weight: bold">
						<?php echo esc_html__( 'You have to set and enable "WP_DEBUG" and "WP_DEBUG_LOG" in your "wp-config.php" file, to show the error log here!', 'wphave-admin' ); ?>
					</p>
				<?php } ?>
			
				<p>
					<?php esc_html_e( 'Note that debugging (the process of finding and resolving issues in software) is disabled by default in WordPress and should not be enabled on live websites. However it can be enabled temporarily to troubleshoot issues.', 'wphave-admin' ); ?>
					<br>
					<a href="https://wordpress.org/support/article/debugging-in-wordpress/" target="_blank" rel="noopener"><?php esc_html_e( 'Learn more about debugging in WordPress.', 'wphave-admin' ); ?></a>
				</p>
			
				<?php 
		
				// Get the wp "debug.log" file
				$file = wphave_admin_error_log_file();
		
				// Get the wp "debug.log" file content
				$file_content = wphave_admin_error_log_file_content( $file );
			
				// Check for custom "debug.log" file path
				$custom_file_path = ( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG != false && WP_DEBUG_LOG != 1 );
		
				// Check for depug modes
				$debug_modes = array();
		
				$debug_modes['WP_DEBUG'] = '';
				if( defined('WP_DEBUG') && WP_DEBUG ) {
					$debug_modes['WP_DEBUG'] = true;
				}
		
				$debug_modes['WP_DEBUG_DISPLAY'] = '';
				if( defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY ) {
					$debug_modes['WP_DEBUG_DISPLAY'] = true;
				}
		
				$debug_modes['WP_DEBUG_LOG'] = '';
				if( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) {
					$debug_modes['WP_DEBUG_LOG'] = true;
				}
		
				$debug_modes['SCRIPT_DEBUG'] = '';
				if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
					$debug_modes['SCRIPT_DEBUG'] = true;
				}
		
				$debug_modes['SAVEQUERIES'] = '';
				if( defined('SAVEQUERIES') && SAVEQUERIES ) {
					$debug_modes['SAVEQUERIES'] = true;
				} ?>
			
				<br>
			
				<div class="wpat-page-menu">
					<ul>
						<?php foreach( $debug_modes as $debug_mode => $value ) {

							$status = 'hidden';
							$label = esc_html__( 'Disabled', 'wphave-admin' );
							if( $value ) {
								$status = 'visible';
								$label = esc_html__( 'Enabled', 'wphave-admin' );
							} ?>

							<li>
								<span>
									<?php echo esc_html( $debug_mode ) . ' '; ?>
									<span class="field-status <?php echo esc_html( $status ); ?>"><?php echo esc_html( $label ); ?></span>
									<?php if( $debug_mode === 'WP_DEBUG_LOG' && $custom_file_path ) { ?>
										<span class="field-status"><?php echo esc_html__( 'Custom path', 'wphave-admin' ); ?></span>
									<?php } ?>
								</span>
							</li>
						<?php } ?>
					</ul>
				</div>
			
				<button id="error_log_refresh" class="button button-primary" style="float: right;">
					<?php echo esc_html__( 'Refresh', 'wphave-admin' ); ?>
				</button> 
			
				<button id="error_log_clear" class="button" style="float: right; margin-right: 10px;">
					<?php echo esc_html__( 'Clear file content', 'wphave-admin' ); ?>
				</button>
			
				<p>
					<?php echo esc_html__( 'Debug log file was found at:', 'wphave-admin' ) . ' <strong>' . esc_html( $file ) . '</strong>'; ?>
					<br>
				</p>
			
				<textarea id="error_log_area" style="width: 100%; height: 1000px; padding: 20px; font-size: 12px; line-height: 1.6; background: #686868; color: #fff; outline: none; resize: none; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', 'monospace'" readonly><?php echo esc_html( $file_content ); ?></textarea>
			
			<?php } else {
		
				echo wphave_admin_plugin_activation_message();
		
			} ?>

		</div>

	<?php }

endif;