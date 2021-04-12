<?php 

/*
 *******************
 * WP SUBPAGE
 *******************
 *
 *	Add a subpage for wp without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_wp_menu' ) ) :

	function wphave_admin_wp_menu() {
			
		add_submenu_page(
			NULL,
			esc_html__( 'WordPress', 'wphave-admin' ),
			esc_html__( 'WordPress', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-wp',
			'wphave_admin_wp_page'
		);
		
	}

	add_action( 'admin_menu', 'wphave_admin_wp_menu' );

endif;


/*
 *******************
 * OUTPUT WP PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the wp subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_wp_page' ) ) :

	function wphave_admin_wp_page() { 
		
		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}
		
		$common = new wphave_admin_server(); 

		$help = '<span class="dashicons dashicons-editor-help"></span>';
		$enabled = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Enabled', 'wphave-admin' ) . '</span>';
		$disabled = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Disabled', 'wphave-admin' ) . '</span>';
		$yes = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Yes', 'wphave-admin' ) . '</span>';
		$no = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'No', 'wphave-admin' ) . '</span>';
		$entered = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Defined', 'wphave-admin' ) . '</span>';
		$not_entered = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Not defined', 'wphave-admin' ) . '</span>';
		$sec_key = '<span class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Please enter this security key in the wp-confiq.php file', 'wphave-admin' ) . '!</span>'; ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( 'WordPress', 'wphave-admin' ) ); ?>
			</h1> 

			<?php if( wphave_admin_activation_status() ) {

				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>

				<h2><?php echo esc_html__( 'Overview', 'wphave-admin' ); ?></h2>

				<p><?php echo __( 'First, you can see the most important information about your WordPress installation at a glance. Learn more about the <a href="https://wordpress.org/about/requirements/" target="_blank" rel="noopener">requirements</a>', 'wphave-admin' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php esc_html_e( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php esc_html_e( 'Result', 'wphave-admin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="25%"><?php esc_html_e( 'WP Version', 'wphave-admin' ); ?>:</td>
							<td><strong><?php bloginfo('version'); ?></strong></td>
						</tr>
						<tr>
							<td><?php esc_html_e('PHP Version', 'wphave-admin'); ?>:</td>
							<td><?php echo $common->getPhpVersion(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'MySQL Version', 'wphave-admin' ); ?>:</td>
							<td><?php echo $common->getMySQLVersion(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'PHP Memory WP-Limit', 'wphave-admin' ); ?>:</td>
							<td><?php
								$memory = $common->memory_size_convert( WP_MEMORY_LIMIT );

								if ($memory < 67108864) {
									echo '<span class="warning"><span class="dashicons dashicons-warning"></span> ' . sprintf(__('%s - For better performance, we recommend setting memory to at least 64MB. See: %s', 'wphave-admin'), size_format($memory), '<a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank" rel="noopener">' . __('Increasing memory allocated to PHP', 'wphave-admin') . '</a>') . '</span>';
								} else {
									echo '<strong>' . size_format($memory) . '</strong>';
								} ?> 
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'PHP Memory Server-Limit', 'wphave-admin' ); ?>:</td>
							<td>
								<?php
								if (function_exists('memory_get_usage')) {
									$system_memory = $common->memory_size_convert(@ini_get('memory_limit'));
									$memory = max($memory, $system_memory);
								}

								if ($memory < 67108864) {
									echo '<span class="warning"><span class="dashicons dashicons-warning"></span> ' . sprintf(__('%s - For better performance, we recommend setting memory to at least 64MB. See: %s', 'wphave-admin'), size_format($memory), '<a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank" rel="noopener">' . __('Increasing memory allocated to PHP', 'wphave-admin') . '</a>') . '</span>';
								} else {
									echo '<strong>' . size_format($memory) . '</strong>';
								} ?> 
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'PHP Memory WP-Usage', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( $common->wp_memory_usage()['MemLimitGet'] == '-1' ) {
									echo $common->wp_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (-1)';
								} else { ?>
									<div class="status-progressbar"><span><?php echo $common->wp_memory_usage()['MemUsageCalc'] . '% '; ?></span><div style="width: <?php echo $common->wp_memory_usage()['MemUsageCalc']; ?>%"></div></div>
									<?php echo $common->wp_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . $common->wp_memory_usage()['MemLimitFormat'];
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'PHP Memory Server-Usage', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( $common->server_memory_usage()['MemLimitGet'] == '-1' ) {
									echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (-1)';
								} else { ?>
									<div class="status-progressbar"><span><?php echo $common->server_memory_usage()['MemUsageCalc'] . '% '; ?></span><div style="width: <?php echo $common->server_memory_usage()['MemUsageCalc']; ?>%"></div></div>
									<?php echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . $common->server_memory_usage()['MemLimitFormat'];
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'PHP Max Upload Size (WP)', 'wphave-admin' ); ?>:</td>
							<td><?php echo (int)ini_get('upload_max_filesize') . ' MB (' . size_format( wp_max_upload_size() ) . ')'; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Home URL', 'wphave-admin'); ?>:</td>
							<td><?php echo get_home_url(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Site URL', 'wphave-admin'); ?>:</td>
							<td><?php echo get_site_url(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('Document Root', 'wphave-admin'); ?>:</td>
							<td><?php echo get_home_path(); ?></td>
						</tr>
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Current Theme', 'wphave-admin' ); ?></h2>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Result', 'wphave-admin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );
						$active_theme = wp_get_theme();
						$theme_version = $active_theme->Version; ?>
						<tr>
							<td><?php esc_html_e( 'Name', 'wphave-admin' ); ?>:</td>
							<td><?php echo esc_html( $active_theme->Name ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Version', 'wphave-admin' ); ?>:</td>
							<td>
								<?php echo esc_html( $theme_version ); ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Author URL', 'wphave-admin' ); ?>:</td>
							<td><?php echo $active_theme->{ 'Author URI' }; ?></td>
						</tr>                    
						<tr>
							<td><?php esc_html_e( 'Image Sizes', 'wphave-admin' ); ?>:</td>
							<td><?php echo implode( ', ', get_intermediate_image_sizes() ); ?></td>
						</tr>     
						<tr>
							<td><?php esc_html_e( 'WooCommerce Compatibility', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( current_theme_supports( 'woocommerce' ) ) { 
									echo $yes; 
								} else {
									echo $no; 
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Child Theme', 'wphave-admin' ); ?>: <a href="https://developer.wordpress.org/themes/advanced-topics/child-themes/" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo is_child_theme() ? '<span class="yes"><span class="dashicons dashicons-yes"></span>Yes</span>' : '<span class="warning"><span class="dashicons dashicons-warning"></span> No. ' . sprintf(__('If you\'re want to modifying a theme, it safe to create a child theme.  See: <a href="%s" target="_blank" rel="noopener">How to create a child theme</a>', 'wphave-admin'), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/') . '</span>'; ?>
							</td>
						</tr>
						<?php if( is_child_theme() ) :
							$parent_theme = wp_get_theme( $active_theme->Template ); ?>
							<tr>
								<td><?php esc_html_e( 'Parent Theme Name', 'wphave-admin' ); ?>:</td>
								<td><?php echo esc_html( $parent_theme->Name ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Parent Theme Version', 'wphave-admin' ); ?>:</td>
								<td>
									<?php echo esc_html( $parent_theme->Version );
									if( version_compare( $parent_theme->Version, $update_theme_version, '<') ) {
										echo ' &ndash; <strong style="color:red;">' . sprintf( __('%s is available', 'wphave-admin'), esc_html( $update_theme_version ) ) . '</strong>';
									} ?>
								</td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'Parent Theme Author URL', 'wphave-admin' ); ?>:</td>
								<td><?php echo $parent_theme->{ 'Author URI' }; ?></td>
							</tr>
						<?php endif ?>                             
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Active Plugins', 'wphave-admin' ); ?></h2>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Name', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Version', 'wphave-admin' ); ?></th>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Author', 'wphave-admin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $active_plugins = (array) get_option( 'active_plugins', array() );

						if( is_multisite() ) {
							$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
							$active_plugins = array_merge( $active_plugins, $network_activated_plugins );
						}

						foreach ( $active_plugins as $plugin ) {

							$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
							$dirname = dirname( $plugin );
							$version_string = '';
							$network_string = '';

							if( ! empty( $plugin_data['Name'] ) ) {

								// Link the plugin name to the plugin url if available.
								$plugin_name = esc_html( $plugin_data['Name'] );

								if( ! empty( $plugin_data['PluginURI'] ) ) {
									$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'wphave-admin' ) . '" target="_blank" rel="noopener">' . $plugin_name . '</a>';
								}

								if( strstr( $dirname, 'wphave-admin-' ) && strstr( $plugin_data['PluginURI'], 'woothemes.com' ) ) {

									if( false === ( $version_data = get_transient( md5( $plugin ) . '_version_data' ) ) ) {
										$changelog = wp_safe_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $dirname . '/changelog.txt' );
										$cl_lines = explode( "\n", wp_remote_retrieve_body( $changelog ) );
										if( ! empty( $cl_lines ) ) {
											foreach ( $cl_lines as $line_num => $cl_line ) {
												if( preg_match( '/^[0-9]/', $cl_line ) ) {
													$date = str_replace( '.', '-', trim( substr( $cl_line, 0, strpos( $cl_line, '-' ) ) ) );
													$version = preg_replace( '~[^0-9,.]~', '', stristr( $cl_line, "version" ) );
													$update = trim(str_replace( "*", "", $cl_lines[$line_num + 1] ) );
													$version_data = array( 'date' => $date, 'version' => $version, 'update' => $update, 'changelog' => $changelog );
													set_transient( md5( $plugin ) . '_version_data', $version_data, DAY_IN_SECONDS );
													break;
												}
											}
										}
									}

									if( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
										$version_string = ' &ndash; <strong style="color:red;">' . esc_html( sprintf( _x('%s is available', 'Version info', 'wphave-admin'), $version_data['version'] ) ) . '</strong>';
									}

									if( $plugin_data['Network'] != false ) {
										$network_string = ' &ndash; <strong style="color:black;">' . __( 'Network enabled', 'wphave-admin' ) . '</strong>';
									}
								} ?>
								<tr>
									<td><?php echo $plugin_name; ?></td>
									<td><?php echo esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
									<td><?php echo sprintf( _x('%s', 'by author', 'wphave-admin' ), $plugin_data['Author'] ); ?></td>
								</tr>
							<?php }
						} ?>
					</tbody>
				</table>

			<?php } else {
				echo wphave_admin_plugin_activation_message();
			} ?>

		</div>

	<?php }

endif;