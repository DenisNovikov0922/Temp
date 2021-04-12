<?php 

/*
 *******************
 * ADD PLUGIN ACTIVATION SUBPAGE
 *******************
 *
 *	Create a subpage for plugin activation.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_page_activation' ) ) :

	function wphave_admin_page_activation() {
			
		add_submenu_page(
			NULL,
			WPHAVE_ADMIN_PLUGIN_NAME . ' ' . esc_html__( 'Activation', 'wphave-admin' ),
			esc_html__( 'Activation', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-purchase-code',
			'wphave_admin_purchase_code_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_page_activation' );


/*
 *******************
 * TEMPLATE PART - PURCHASE CODE FIELD
 *******************
 *
 *	Template part to output the field for entering the purchase code.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_code_field' ) ) :

	function wphave_admin_code_field() { 

		// Get activation status
		$is_activated = wphave_admin_activation_status();
		
		// Get input value
		$input_value = '';
		if( $is_activated ) {
			$purchase_code = get_option( wphave_admin_envato_purchase_code() );
			$input_value = isset( $purchase_code ) ? $purchase_code : 'n/a';
		}
		
		// Manage input type
		$input_type = 'text';
		if( $is_activated ) {
			$input_type = 'password';
		} 

		/****************
		* PURCHASE CODE - INPUT FIELD
		****************/

		?>
			
		<input id="purchase_code" name="purchase_code" type="<?php echo esc_html( $input_type ); ?>" placeholder="<?php echo esc_html__( 'Enter your Purchase Code', 'wphave-admin' ); ?>" value="<?php echo esc_html( $input_value ); ?>" size="40" required />
		<div id="purchase_code_show" class="button">
			<span class="dashicons dashicons-visibility"></span>
			<span class="dashicons dashicons-hidden" style="display: none"></span>
		</div>

		<input id="purchase_root_url" name="purchase_root_url" type="hidden" value="<?php echo wphave_admin_root_url(); ?>" size="40" disabled />										
		<input id="purchase_client_mail" name="purchase_client_mail" type="hidden" size="40" placeholder="<?php echo esc_html__( 'E-mail address', 'wphave-admin' ); ?>" />
		
	<?php }

endif;


/*
 *******************
 * TEMPLATE PART - ACTIVATION BUTTON
 *******************
 *
 *	Template part to output the button for activating the purchase code.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_activation_button' ) ) :

	function wphave_admin_activation_button() { 

		// Get activation status
		$is_activated = wphave_admin_activation_status();
		
		/****************
		* ACTIVATION - BUTTON
		****************/

		?>
			
		<input id="btn_purchase" type="submit" class="button button-primary" value="<?php echo esc_html__( 'Verify and install license', 'wphave-admin' ); ?>"<?php if( $is_activated ) { ?> disabled<?php } ?> />
		
	<?php }

endif;


/*
 *******************
 * TEMPLATE PART - UNLOCK/RESET BUTTON
 *******************
 *
 *	Template part to output the button for unlocking/reseting the purchase code.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_unlock_reset' ) ) :

	function wphave_admin_unlock_reset() { 

		// Get purchase data from the users purchase code
		$purchase_data = wphave_admin_get_purchase_theme_details();
		
		/****************
		* UNLOCK - BUTTON
		****************/
		
		?>
			
		<div id="btn_delete_license" class="button">
			<?php echo esc_html__( 'Unlock license', 'wphave-admin' ); ?>
		</div>

		<?php
		
		/****************
		* RESET - BUTTON
		****************/
		
		// By calling URL parameter "reset-purchase-code=on" (http://domain.com/wp-admin/tools.php?page=wphave-admin-purchase-code&tab=activation&reset-purchase-code=on)
		// We can show the "reset license" dialog
		$show_reset_button = isset( $_GET['reset-purchase-code'] ) ? $_GET['reset-purchase-code'] : '';
		$visibility = 'none';
		if( $show_reset_button === 'on' ) {
			$visibility = 'block';
		} ?>

		<div class="license-reset" style="display: <?php echo esc_html( $visibility ); ?>">
			<p>
				<?php $author_mail = '<a href="mailto:' . esc_html( WPHAVE_ADMIN_AUTHOR_MAIL ) . '?subject=' . esc_html__( 'Request to unlock the plugin license for', 'wphave-admin' ) . ' ' . WPHAVE_ADMIN_PLUGIN_NAME . '&amp;body=' . esc_html__( 'Please unlock my purchase code for the following domain:', 'wphave-admin' )  . ' ' . wphave_admin_root_url() . '%0D%0A %0D%0A' . esc_html__( 'Purchase code:', 'wphave-admin' ) . ' ' . $purchase_data['purchase_code'] . '">' . esc_html__( 'contact the author', 'wphave-admin' ) . '</a>';
				printf( wp_kses_post( __( 'In some cases unlocking the license is not possible. Therefore, you can reset the license key. If you can not reactivate after resetting the license, %1$s of the plugin to manually unlock the license.', 'wphave-admin' ) ), $author_mail ); ?>	
			</p>
			<div id="btn_reset_license" class="button">
				<?php echo '(!) ' . esc_html__( 'Reset license', 'wphave-admin' ); ?>
			</div>
		</div>
		
	<?php }

endif;


/*
 *******************
 * PLUGIN ACTIVATION SUBPAGE CONTENT
 *******************
 *
 *	Output the content for plugin activation subpage.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_purchase_code_page' ) ) :

	function wphave_admin_purchase_code_page() { 

		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}
		
		// Get purchase data from the users purchase code
		$purchase_data = wphave_admin_get_purchase_theme_details();
		
		// Get activation status
		$is_activated = wphave_admin_activation_status();

		// Get the purchase code
		$purchase_code = get_option( wphave_admin_envato_purchase_code() );

		$activation_label = '<span style="color:#d63316">(' . esc_html__( 'Deactivated', 'wphave-admin' ) . ')</span>';
		if( $is_activated ) {
			$activation_label = '<span style="color:#8db51e">(' . esc_html__( 'Activated', 'wphave-admin' ) . ')</span>';							
		} ?>
	
		<div class="wrap about-wrap wpat-plugin-welcome">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="settings-wrapper">
							<div class="inside">
								
								<h1>
									<?php echo wphave_admin_title(); ?>
								</h1> 

								<br><br>
								
								<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'activation'; 
								echo wphave_admin_tab_menu( $active_tab ); ?>

								<h3>
									<?php echo esc_html__( 'Plugin Activation', 'wphave-admin' ) . ' ' . wp_kses_post( $activation_label ); ?>
								</h3>

								<?php 
		
								$show_license_box = ( ! wphave_admin_plugin_with_theme() );
								if( is_multisite() ) {
									$blog_id = 1; // <-- Option from main site
									$show_license_box = ( ! wphave_admin_plugin_with_theme() && get_current_blog_id() == $blog_id );
								}
		
								if( $show_license_box ) { ?>

									<p class="about-text">
										<?php echo esc_html__( 'You will need your Envato item purchase code to activate this plugin. If you do not activate this plugin, you will not be able to access the plugin settings page.', 'wphave-admin' ); ?>
										<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank" rel="noopener">
											<?php echo esc_html__( 'How to find your purchase code?', 'wphave-admin' ); ?>	
										</a>
									</p>

									<form method="post" id="purchase_verify">
										<div class="activation-notice">									
											<div class="activation-notice-content">
												
												<?php
													  
												/****************
												* LOCAL TEST INSTALLATION
												****************/

												if( wphave_admin_wp_is_local_installation() ) { ?>

													<span id="license_notice" class="theme-notice" style="background:#e49519;">
														<span class="notice-holder">
															<?php echo esc_html__( 'Local / Developement Environment', 'wphave-admin' ); ?>
														</span>
													</span>

													<div class="license-fields">
														<?php 
														
														// In case, the purchase code was entered, before "local/developement" check was included
														// --> Provide the possibility to unlock the purchase code by the user
														if( $purchase_code ) {
															
															echo wphave_admin_code_field();
															
														// Show the dummy field
														} else { ?>
														
															<input id="input_dummy" type="text" placeholder="<?php echo esc_html__( 'Enter your Purchase Code', 'wphave' ); ?>" value="" size="40" readonly />
														
														<?php } ?>
													</div>

													<?php 
													
													// In case, the purchase code was entered, before "local/developement" check was included
													// --> Provide the possibility to unlock the purchase code by the user
													if( $purchase_code ) { 
														
														/****************
														* ACTIVATION - BUTTON
														****************/	
														
														echo wphave_admin_activation_button();
														
														/****************
														* UNLOCK/RESET - BUTTON
														****************/
														
														echo wphave_admin_unlock_reset();
														
													// Show the dummy field
													} else { ?>
												
														<input id="btn_dummy" type="submit" class="button button-primary" value="<?php echo esc_html__( 'Verify and install license', 'wphave-admin' ); ?>" disabled />
												
													<?php } ?>
												
													<p>
														<small>
															<?php echo esc_html__( 'This theme can be used under local / developement installations without entering a purchase code.', 'wphave-admin' ); ?>
														</small>
													</p>
										
												<?php 
												
												/****************
												* PLUGIN ACTIVATION BOX
												****************/									   
																							   
												} else { ?>

													<span id="license_notice" class="theme-notice" style="display: none">
														<span class="notice-holder">
															<?php // Placeholder for license notice. ?>
														</span>
													</span>

													<div class="license-fields">
														<?php echo wphave_admin_code_field(); ?>														
													</div>

													<?php 
													
													/****************
													* PURCHASE DETAILS
													****************/					
														
													if( $is_activated ) { ?>
												
														<div class="license-details">
															
															<div class="license-details-label">
																<?php echo esc_html__( 'License Details', 'wphave-admin' ); ?>:
															</div>
															
															<?php 
															
															// ENVATO - PURCHASE CODE
															if( wphave_admin_purchase_code_verify( $purchase_data['purchase_code'] ) == 'valid' ) { ?>
															
																<ul>
																	<li><?php echo esc_html__( 'Plugin', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['theme_name'] ); ?></li>
																	<li><?php echo esc_html__( 'Buyer', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['buyer'] ); ?></li>
																	<li><?php echo esc_html__( 'License', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['license'] ); ?></li>
																	<li><?php echo esc_html__( 'Purchase Count', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['purchase_count'] ); ?></li>
																	<li><?php echo esc_html__( 'Sold at', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['sold_at'] ); ?></li>
																	<li><?php echo esc_html__( 'Support until', 'wphave-admin' ); ?>: <strong><?php echo esc_html( $purchase_data['supported_until'] ); ?></strong></li>
																</ul>
															
															<?php 
															
															// PERSONAL - PURCHASE CODE
															} else { ?>
															
																<ul>
																	<li><?php echo esc_html__( 'Plugin', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['theme_name'] ); ?></li>
																	<li><?php echo esc_html__( 'License', 'wphave-admin' ); ?>: <?php echo esc_html( $purchase_data['license'] ); ?></li>
																</ul>
															
															<?php } ?>
															
														</div>
												
													<?php }
													
													/****************
													* ACTIVATION - BUTTON
													****************/	
														
													echo wphave_admin_activation_button();
													
													/****************
													* UNLOCK/RESET - BUTTON
													****************/	
														
													if( $is_activated ) {
														echo wphave_admin_unlock_reset();
													}
														
													/****************
													* LOGIN ATTEMPTS - NOTICE
													****************/		
														
													if( ! $is_activated ) { ?>
														<p>
															<small>
																<?php echo esc_html__( 'You have five attempts to enter the purchase code. Otherwise, your website will be blocked to avoid too many server requests.', 'wphave-admin' ); ?>
																<a href="<?php echo WPHAVE_ADMIN_ENVATO_URL; ?>" target="_blank" rel="noopener">
																	<?php echo esc_html__( 'Contact the theme author for help.', 'wphave-admin' ); ?>	
																</a>
															</small>
														</p>
													<?php } 
												
													/****************
													* ENTER CODE - NOTICE
													****************/
												
													?>

													<p>
														<small>
															<?php echo esc_html__( 'Why do you have to enter a purchase code? The license verification protects this premium plugin against unauthorized use without a license or multiple use with only one license purchased.', 'wphave-admin' ); ?>
															<a href="https://codecanyon.net/licenses/standard" target="_blank" rel="noopener">
																<?php echo esc_html__( 'Read more about the Envato Licenses.', 'wphave-admin' ); ?>	
															</a>
														</small>
													</p>
										
												<?php } ?>

											</div>
										</div>
									</form>

									<?php 
														 
									/****************
									* PURCHASE LICENSE - NOTICE
									****************/
														 
									if( ! $is_activated ) { ?>
										<p>
											<?php echo esc_html__( 'You do not have a plugin license? Then you can buy one anytime.', 'wphave-admin' ); ?>
											<a href="<?php echo WPHAVE_ADMIN_ENVATO_URL; ?>" target="_blank" rel="noopener">
												<?php echo esc_html__( 'Get a license.', 'wphave-admin' ); ?>
											</a>
										</p>
									<?php } 
								
									/****************
									* UPDATE PLUGIN - MESSAGE
									****************/
								
									?>

									<h3>
										<?php printf( wp_kses_post( __( 'How would you like to update the %1$s plugin in the future?', 'wphave-admin' ) ), WPHAVE_ADMIN_PLUGIN_NAME ); ?>
									</h3>

									<p>
										<?php $envato_market_plugin_url = '<a href="https://envato.com/market-plugin/" target="_blank" rel="noopener"><strong>Envato Market plugin</strong></a>';
										printf( wp_kses_post( __( 'There are two ways to update this plugin when a new version is released. In the first way, you can install updates manually after receiving an email notification from Envato about a new version. In the second and recommended way, you can update this plugin automatically by installing the %1$s.', 'wphave-admin' ) ), $envato_market_plugin_url ); ?>
										
										<br><br>
										
										<img src="<?php echo wphave_admin_path( 'assets/img/envato-market-logo.svg' ); ?>" alt="Envato Market Logo" style="width: 200px">
										
										<br><br>
										
										<?php $envato_market_plugin_url = '<a href="https://envato.com/market-plugin/" target="_blank" rel="noopener">https://envato.com/market-plugin/</a>';
										printf( wp_kses_post( __( 'To activate automatic updates for the %1$s plugin, follow the instructions on %2$s.', 'wphave-admin' ) ), WPHAVE_ADMIN_PLUGIN_NAME, $envato_market_plugin_url ); ?>
									</p>	

									<?php
														 
									/****************
									* RECOMMENDED PLUGINS - MESSAGE
									****************/
								
									?>
									
									<h4>
										<?php echo esc_html__( 'Recommended WordPress Developer Plugins', 'wphave-admin' ); ?>
									</h4>

									<ul>
										<li>
											<a href="https://wordpress.org/plugins/query-monitor/" target="_blank" rel="noopener">Query Monitor</a> - 
											<?php echo esc_html__( 'Enable debugging of database queries, PHP errors, hooks and actions, block editor blocks, enqueued scripts and stylesheets, HTTP API calls, and more.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/debug-bar/" target="_blank" rel="noopener">Debug Bar</a> - 
											<?php echo esc_html__( 'Adds a debug menu to the admin bar that shows query, cache, and other helpful debugging information.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank" rel="noopener">Regenerate Thumbnails</a> - 
											<?php echo esc_html__( 'Regenerate all thumbnail sizes for one or more images that have been uploaded to your media library.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/user-switching/" target="_blank" rel="noopener">User Switching</a> - 
											<?php echo esc_html__( 'This plugin allows you to quickly swap between user accounts in WordPress at the click of a button.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/redirection/" target="_blank" rel="noopener">Redirection</a> - 
											<?php echo esc_html__( 'Easily add and manage 301 redirections to prevent 404 errors.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/antispam-bee/" target="_blank" rel="noopener">Antispam Bee</a> - 
											<?php echo esc_html__( 'Protect your WordPress website against spam comments.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/wp-fastest-cache/" target="_blank" rel="noopener">WP Fastest Cache</a> - 
											<?php echo esc_html__( 'Speed up your website loading time with WordPress caching.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/rocket-lazy-load/" target="_blank" rel="noopener">Lazy Load by WP Rocket</a> - 
											<?php echo esc_html__( 'Lazy Load by WP Rocket displays images and/or iframes on a page only when they are visible to the user.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/webp-express/" target="_blank" rel="noopener">WebP Express</a> - 
											<?php echo esc_html__( 'Much faster load time for images in browsers that supports webp.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/backwpup/" target="_blank" rel="noopener">BackWPup</a> - 
											<?php echo esc_html__( 'Backup your WordPress website in regular intervals.', 'wphave-admin' ); ?>
										</li>
										<li>
											<a href="https://wordpress.org/plugins/brute-force-login-protection/" target="_blank" rel="noopener">Brute Force Login Protection</a> - 
											<?php echo esc_html__( 'Protect your WordPress website against brute force login attacks.', 'wphave-admin' ); ?>
										</li>
									</ul>

								<?php 
														 
								/****************
								* ACTIVATION - NOTICE
								****************/	 
														 
								} else {
									
									if( has_filter('wphave_admin_license_accepted') ) {
										
										$permission = 'denied';	
										$apply = apply_filters('wphave_admin_license_accepted', $permission);
										if( $apply === 'accepted_by_theme' || $apply === 'accepted' ) {
											echo esc_html__( 'This plugin can be used in combination with this theme for free.', 'wphave-admin' );
										}
										
									} else {
										
										if( $is_activated ) {
											echo esc_html__( 'This plugin can be used on other multisite instances without the need for an additional license.', 'wphave-admin' );
										} else {
											echo esc_html__( 'Entering the purchase code is required to access the settings. Please enter the purchase code on the main page of this multisite.', 'wphave-admin' );
										}	
										
									}
									
								} ?>

							</div>
						</div>
					</div>		

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="inside">

									<img src="<?php echo wphave_admin_path( 'assets/img/screenshot.png' ); ?>" width="100%" alt="Plugin Screenshot">

									<p>
										<a href="https://themeforest.net/user/creativedive/portfolio" target="_blank" rel="noopener">
											<?php echo esc_html__( 'Get to know my WordPress themes.', 'wphave-admin' ); ?>
										</a>
									</p>

								</div>
							</div>

							<div class="postbox">
								<div class="inside">							

									<p>
										<img class="theme-author-img" src="<?php echo wphave_admin_path( 'assets/img/avatar-author.jpg' ); ?>" width="100%" alt="Theme Author">
										<strong><?php echo esc_html__( "Hey, I'm Martin, the plugin author from CreativeDive.", 'wphave-admin' ); ?></strong>
										<br>
										<br>
										<?php echo esc_html__( 'This plugin already includes more than 1000 hours of work to redesign a impressive WordPress backend for users like you. Great new features are planned for the future.', 'wphave-admin' ); ?>
									</p>

									<p>
										<?php echo esc_html__( 'Help me to develop a powerful plugin that will benefit you for a long time.', 'wphave-admin' ); ?>
									</p>

									<p>
										<?php echo esc_html__( 'Please show your appreciation and rate the plugin.', 'wphave-admin' ); ?>
									</p>

									<p>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
									</p>

									<p>
										<?php echo esc_html__( 'Thank you and best regards, Martin.', 'wphave-admin' ); ?>
									</p>

									<a class="button" href="<?php echo WPHAVE_ADMIN_ENVATO_REVIEW_URL; ?>" target="_blank" rel="noopener">
										<?php echo esc_html__( 'Rate now!', 'wphave-admin' ); ?>
									</a>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
		
	<?php }

endif;