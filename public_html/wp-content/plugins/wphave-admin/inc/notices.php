<?php 

/*
 *******************
 * PLUGIN ACTIVATION DATE
 *******************
 *
 *	Set plugin activation date.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_activation_date') ) :	

	function wphave_admin_activation_date() {

		add_option('wp_admin_theme_cd_activation_date', current_time( 'mysql' ), '', 'yes');
		define( 'WPHAVE_ADMIN_ACTIVATION_DATE', get_option('wp_admin_theme_cd_activation_date') );

	}

endif;

add_action('plugins_loaded', 'wphave_admin_activation_date');


/*
 *******************
 * ENVATO RATING NOTICE
 *******************
 *
 *	Show an admin notice to request an envato rating.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists('wphave_admin_envato_rating') ) :	

	function wphave_admin_envato_rating() {

		// Check if the notice was already dismissed
		$dismissed = get_option( 'wp_admin_theme_cd_envato_rating_notice' );
		
		$start_date = WPHAVE_ADMIN_ACTIVATION_DATE; 
		$date = strtotime( $start_date );
		$date = strtotime( '+182 day', $date ); // after a half year of plugin activation
		$reached_time = date( 'Y-m-d H:i:s', $date );		

		// Check if plugin activation date is older than a half year
		if( $dismissed !== 'dismissed' && $reached_time < current_time( 'mysql' ) ) {

			$permission = isset( $permission ) ? $permission : false;
			$apply = apply_filters('wphave_admin_license_accepted', $permission);
			if( $apply === 'accepted_by_theme' ) {
				// Do not show this admin notice, if the plugin is free in combination with "wphave" themes
				return;
			}
			
			if( is_multisite() && get_current_blog_id() != '1' ) {
				// Do not show this admin notice, if the current blog id is not id "1"
				return;
			}

			if ( ! function_exists( 'wphave_admin_envato_rating_notice' ) ) :

				// Show a admin notice to rate for the theme		
				function  wphave_admin_envato_rating_notice() { 

					$star = '<span style="font-size:17px;margin:0px -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span>';
					$author = '<img style="float:left;margin:-6px 10px 0px -6px;border-radius:50%" class="theme-author-img" src="' . wphave_admin_path( "assets/img/avatar-author.jpg" ) . '" width="32" alt="Theme Author">'; ?>

					<div class="notice notice-success notice-envato-rating is-dismissible">
						<p><?php printf( wp_kses_post( __( '%3$s <strong style="color:#4d820c">Hey you! I\'m Martin, the plugin author of WP Admin Theme CD.</strong> Do you like this plugin? Please show your appreciation and rate the plugin. Help me to develop a powerful plugin that will benefit you for a long time. %2$s %2$s %2$s %2$s %2$s <a href="%1$s" target="_blank" rel="noopener">Rate now!</a>', 'wphave-admin' ) ), WPHAVE_ADMIN_ENVATO_REVIEW_URL, $star, $author ); ?></p>
					</div>

					<script>
						jQuery(document).ready(function($) {

							$('.notice.notice-envato-rating.is-dismissible').each( function() {

								var notice = $(this);

								// Build a new dismiss <button>
								var button = $( '<button style="z-index:3" type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'wphave-admin' ); ?></span></button>' );

								// Add the new button to the notice
								notice.append( button );

								button.on( 'click', function(e) {
									e.preventDefault();

									var jsonObjects = [{
										// Dismiss action command
										command : 'dismiss_notice',
									}];

									var jsonData = JSON.stringify( jsonObjects );

									$.ajax({
										type: 'POST',
										dataType: 'json',
										url: ajaxurl,
										data: { 
											action: 'wphave_admin_envato_rating_notice_dismiss',
											security: '<?php echo wp_create_nonce( 'verify_sec_admin_notice_request' ); ?>',
											fieldData: jsonData,
										},
										cache: false,
										success: function(data) {

											//console.log($(data.success));

											// Remove the notice
											notice.fadeTo( 100 , 0, function() {
												$(this).slideUp( 100, function() {
													$(this).remove();
												});
											});

										}
									});
								});

							});

						});
					</script>

				<?php }

			endif;

			add_action('admin_notices', 'wphave_admin_envato_rating_notice');

		}

	}

endif;

add_action('admin_init', 'wphave_admin_envato_rating');


/*
 *******************
 * PLUGIN ENVATO RATING ADMIN NOTICE - AJAX DISMISS
 *******************
 *
 *  This ajax action is saving the Envato rating admin notice "dismissed" status.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_envato_rating_notice_dismiss' ) ) :

	function wphave_admin_envato_rating_notice_dismiss() {

		// --> !!! For security check and verify wp_nonce() before saving new values from AJAX request
		check_ajax_referer( 'verify_sec_admin_notice_request', 'security' );

		if( $_POST ) {		

			// Get dismissed command
			$request = json_decode( stripslashes( $_POST['fieldData'] ) );

			if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$action = $request[0]->command;
			}		

			// Save it, that the notice was already dismissed
			if( $action === 'dismiss_notice' ) {
				update_option( 'wp_admin_theme_cd_envato_rating_notice', 'dismissed', 'yes' );
			}

			wp_send_json( array(
				//'success' => 'SUCCESS',
			) );			

		}

		die();

	}

endif;

add_action( 'wp_ajax_wphave_admin_envato_rating_notice_dismiss', 'wphave_admin_envato_rating_notice_dismiss' );
add_action( 'wp_ajax_nopriv_wphave_admin_envato_rating_notice_dismiss', 'wphave_admin_envato_rating_notice_dismiss' );


/*
 *******************
 * PLUGIN OLD NOTICE
 *******************
 *
 *  Show an admin notice if the old/deprecated "WP Admin Theme CD" plugin is still installed.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_old_plugin_notice' ) ) :
		
	function wphave_admin_old_plugin_notice() { 

		if( ! class_exists('WP_Admin_Theme_CD_Options') ) {
			// Stop here, if the old plugin is already uninstalled
			return;
		}
		
		$plugin_name = 'WP Admin Theme CD'; ?>

		<div class="notice notice-error">
			<p><?php printf( wp_kses_post( __( 'The plugin "%1$s" has detected that the deprecated plugin "%2$s" is still installed. Please deactivate and uninstall this plugin to avoid conflicts!', 'wphave-admin' ) ), WPHAVE_ADMIN_PLUGIN_NAME, $plugin_name ); ?></p>
		</div>

	<?php }

endif;

add_action('admin_notices', 'wphave_admin_old_plugin_notice');


/*
 *******************
 * PLUGIN ACTIVATION MESSAGE
 *******************
 *
 *  Output a plugin activation message if no license code was entered.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_plugin_activation_message' ) ) :

	function wphave_admin_plugin_activation_message() {			
		return esc_html__( 'This plugin is not activated. Please enter your Envato purchase code to enable the plugin settings.', 'wphave-admin' );
	}

endif;