<?php 

/*
 *******************
 * CONSTANTS SUBPAGE
 *******************
 *
 *	Add a subpage for constants without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_constants_menu' ) ) :

	function wphave_admin_constants_menu() {
			
		add_submenu_page(
			NULL,
			esc_html__( 'WordPress', 'wphave-admin' ),
			esc_html__( 'WordPress', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-constants',
			'wphave_admin_constants_page'
		);
		
	}

	add_action( 'admin_menu', 'wphave_admin_constants_menu' );

endif;


/*
 *******************
 * OUTPUT CONSTANTS PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the constants subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_constants_page' ) ) :

	function wphave_admin_constants_page() { 
		
		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}
		
		global $wpdb; 
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
				<?php echo wphave_admin_title( esc_html__( 'Constants', 'wphave-admin' ) ); ?>
			</h1> 

			<?php if( wphave_admin_activation_status() ) {

				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>

				<h2><?php echo esc_html__( 'Overview', 'wphave-admin' ); ?></h2>

				<p><?php echo __( 'Use the following constants to manage important settings of your WordPress installation in the <code>wp-config.php</code> file. Learn more about <a href="https://wordpress.org/support/article/editing-wp-config-php/" target="_blank" rel="noopener">here</a>', 'wphave-admin' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php esc_html_e( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php esc_html_e( 'Result', 'wphave-admin' ); ?></th>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Example', 'wphave-admin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php esc_html_e( 'WP Language', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#language-and-language-directory" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WPLANG') && WPLANG ) : 
									echo WPLANG;
								else :
									echo $not_entered . ' / ' . get_locale();
								endif; ?>
							</td>
							<td><?php echo "define( 'WPLANG', 'de_DE' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Force SSL Admin', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#require-ssl-for-admin-and-logins" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FORCE_SSL_ADMIN') && true === FORCE_SSL_ADMIN ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'FORCE_SSL_ADMIN', true );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP PHP Memory Limit', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( WP_MEMORY_LIMIT == '-1' ) {
									echo '-1 / ' . esc_html__( 'Unlimited', 'wphave-admin' );
								} else {
									echo (int)WP_MEMORY_LIMIT . ' MB';                                                              
								} 
								echo ' (' . esc_html__( 'defined limit', 'wphave-admin' ) . ')'; 

								if( (int)WP_MEMORY_LIMIT < (int)ini_get('memory_limit') && WP_MEMORY_LIMIT != '-1' ) {
									echo ' <span class="warning"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'The WP PHP Memory Limit is less than the %s Server PHP Memory Limit', 'wphave-admin' ), (int)ini_get('memory_limit') . ' MB' ) . '!</span>';
								} ?>
							</td>
							<td><?php echo "define( 'WP_MEMORY_LIMIT', '64M' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP PHP Max Memory Limit', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( WP_MAX_MEMORY_LIMIT == '-1' ) {
									echo '-1 / ' . esc_html__( 'Unlimited', 'wphave-admin' );
								} else {
									echo (int)WP_MAX_MEMORY_LIMIT . ' MB';
								} 
								echo ' (' . esc_html__( 'defined limit', 'wphave-admin' ) . ')'; ?>
							</td>
							<td><?php echo "define( 'WP_MAX_MEMORY_LIMIT', '256M' );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Post Revisions', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-post-revisions" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_POST_REVISIONS') && WP_POST_REVISIONS == false ) {
									esc_html_e( 'Disabled', 'wphave-admin' );
								} else {
									echo WP_POST_REVISIONS;
								} ?>
							</td>
							<td><?php echo "define( 'WP_POST_REVISIONS', false );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Autosave Interval', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#modify-autosave-interval" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('AUTOSAVE_INTERVAL') && AUTOSAVE_INTERVAL ) : 
									echo AUTOSAVE_INTERVAL . ' ' . esc_html__( 'Seconds', 'wphave-admin' );
								endif; ?>
							</td>
							<td><?php echo "define( 'AUTOSAVE_INTERVAL', 160 );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Mail Interval', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('WP_MAIL_INTERVAL') && WP_MAIL_INTERVAL ) : 
									echo WP_MAIL_INTERVAL . ' ' . esc_html__( 'Seconds', 'wphave-admin' );
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_MAIL_INTERVAL', 60 );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Empty Trash', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#empty-trash" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( EMPTY_TRASH_DAYS == 0 ) {
									echo $disabled;
								} else {
									echo EMPTY_TRASH_DAYS . ' ' . 'Days';
								} ?>
							</td>
							<td><?php echo "define( 'EMPTY_TRASH_DAYS', 30 );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Media Trash', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('MEDIA_TRASH') && true === MEDIA_TRASH ) :
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'MEDIA_TRASH', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Cleanup Image Edits', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#cleanup-image-edits" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('IMAGE_EDIT_OVERWRITE') && true === IMAGE_EDIT_OVERWRITE ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'IMAGE_EDIT_OVERWRITE', true );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Multisite', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#enable-multisite-network-ability" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_ALLOW_MULTISITE') && true === WP_ALLOW_MULTISITE ) :
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_ALLOW_MULTISITE', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Main Site Domain', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('DOMAIN_CURRENT_SITE') && DOMAIN_CURRENT_SITE ) : 
									echo DOMAIN_CURRENT_SITE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'DOMAIN_CURRENT_SITE', 'www.domain.com' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Main Site Path', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('PATH_CURRENT_SITE') && PATH_CURRENT_SITE ) : 
									echo PATH_CURRENT_SITE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'PATH_CURRENT_SITE', '/path/to/wordpress/' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Main Site ID', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('SITE_ID_CURRENT_SITE') && SITE_ID_CURRENT_SITE ) : 
									echo SITE_ID_CURRENT_SITE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'SITE_ID_CURRENT_SITE', 1 );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Main Site Blog ID', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('BLOG_ID_CURRENT_SITE') && BLOG_ID_CURRENT_SITE ) : 
									echo BLOG_ID_CURRENT_SITE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'BLOG_ID_CURRENT_SITE', 1 );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Allow Subdomain Install', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('SUBDOMAIN_INSTALL') && true === SUBDOMAIN_INSTALL ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'SUBDOMAIN_INSTALL', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Allow Subdirectory Install', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('ALLOW_SUBDIRECTORY_INSTALL') && true === ALLOW_SUBDIRECTORY_INSTALL ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'ALLOW_SUBDIRECTORY_INSTALL', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Site Specific Upload Directory', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('BLOGUPLOADDIR') && BLOGUPLOADDIR ) : 
									echo BLOGUPLOADDIR;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'BLOGUPLOADDIR', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Upload Base Directory', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('UPLOADBLOGSDIR') && UPLOADBLOGSDIR ) : 
									echo UPLOADBLOGSDIR;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'UPLOADBLOGSDIR', 'wp-content/blogs.dir' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Load Sunrise', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('SUNRISE') && true === SUNRISE ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'SUNRISE', true );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Debug Mode', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wp_debug" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_DEBUG') && WP_DEBUG ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_DEBUG', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Debug Log', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wp_debug" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_DEBUG_LOG') && WP_DEBUG_LOG ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_DEBUG_LOG', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Debug Display', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wp_debug" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_DEBUG_DISPLAY', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Script Debug', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#script_debug" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'SCRIPT_DEBUG', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Save Queries', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#save-queries-for-analysis" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('SAVEQUERIES') && SAVEQUERIES ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'SAVEQUERIES', true );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Automatic Updates', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-wordpress-auto-updates" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('AUTOMATIC_UPDATER_DISABLED') && AUTOMATIC_UPDATER_DISABLED ) : 
									echo $disabled;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'AUTOMATIC_UPDATER_DISABLED', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Core Updates', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-wordpress-core-updates" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_AUTO_UPDATE_CORE') && false === WP_AUTO_UPDATE_CORE ) : 
									echo $disabled;
								elseif( defined('WP_AUTO_UPDATE_CORE') && 'minor' === WP_AUTO_UPDATE_CORE ) : 
									echo $enabled . ' / <span class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Only for minor updates', 'wphave-admin' ) . '</span>';
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_AUTO_UPDATE_CORE', false );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Default Theme Updates', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('CORE_UPGRADE_SKIP_NEW_BUNDLED') && true === CORE_UPGRADE_SKIP_NEW_BUNDLED ) : 
									echo $disabled;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'CORE_UPGRADE_SKIP_NEW_BUNDLED', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Plugin and Theme Editor', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-the-plugin-and-theme-editor" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DISALLOW_FILE_EDIT') && true === DISALLOW_FILE_EDIT ) : 
									echo $disabled;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'DISALLOW_FILE_EDIT', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Plugin and Theme Updates', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-plugin-and-theme-update-and-installation" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DISALLOW_FILE_MODS') && true === DISALLOW_FILE_MODS ) : 
									echo $disabled;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'DISALLOW_FILE_MODS', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Default Theme', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('WP_DEFAULT_THEME') && WP_DEFAULT_THEME ) : 
									echo WP_DEFAULT_THEME;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_DEFAULT_THEME', 'default-theme-folder-name' );" ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Alternate Cron', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#alternative-cron" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('ALTERNATE_WP_CRON') && true === ALTERNATE_WP_CRON ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'ALTERNATE_WP_CRON', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Cron', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-cron-and-cron-timeout" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ) : 
									echo $disabled;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'DISABLE_WP_CRON', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Cron Lock Timeout', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-cron-and-cron-timeout" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_CRON_LOCK_TIMEOUT') && WP_CRON_LOCK_TIMEOUT ) : 
									echo WP_CRON_LOCK_TIMEOUT . ' ' . esc_html__( 'Seconds', 'wphave-admin' );
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_CRON_LOCK_TIMEOUT', 60 );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e('WP Cache', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#cache" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_CACHE') && true === WP_CACHE ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_CACHE', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Concatenate Admin JS/CSS', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#disable-javascript-concatenation" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('CONCATENATE_SCRIPTS') && false === CONCATENATE_SCRIPTS || true === SCRIPT_DEBUG ) :
									echo $disabled;
									if( true === SCRIPT_DEBUG ) :
										echo ' / <span class="warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Not available if WP Script Debug is true', 'wphave-admin' ) . '</span>';
									endif;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'CONCATENATE_SCRIPTS', false );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Compress Admin JS', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( defined('COMPRESS_SCRIPTS') && false === COMPRESS_SCRIPTS || true === SCRIPT_DEBUG ) :
									echo $disabled;
									if( true === SCRIPT_DEBUG ) :
										echo ' / <span class="warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Not available if WP Script Debug is true', 'wphave-admin' ) . '</span>';
									endif;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'COMPRESS_SCRIPTS', false );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Compress Admin CSS', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( defined('COMPRESS_CSS') && false === COMPRESS_CSS || true === SCRIPT_DEBUG ) :
									echo $disabled;
									if( true === SCRIPT_DEBUG ) :
										echo ' / <span class="warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Not available if WP Script Debug is true', 'wphave-admin' ) . '</span>';
									endif;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'COMPRESS_CSS', false );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Enforce GZip Admin JS/CSS', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( ! defined('ENFORCE_GZIP') || defined('ENFORCE_GZIP') && false === ENFORCE_GZIP || true === SCRIPT_DEBUG ) :
									echo $disabled;
									if( true === SCRIPT_DEBUG ) :
										echo ' / <span class="warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Not available if WP Script Debug is true', 'wphave-admin' ) . '</span>';
									endif;
								else :
									echo $enabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'ENFORCE_GZIP', true );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Allow unfiltered HTML', 'wphave-admin' ); ?>: <a href="https://codex.wordpress.org/Editing_wp-config.php#Disable_unfiltered_HTML_for_all_users" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DISALLOW_UNFILTERED_HTML') && true === DISALLOW_UNFILTERED_HTML ) : 
									echo $disabled . ' ' . esc_html__( 'for all users', 'wphave-admin' );
								else :
									echo $enabled . ' ' . esc_html__( 'for users with administrator or editor roles', 'wphave-admin' );
								endif; ?>
							</td>
							<td><?php echo "define( 'DISALLOW_UNFILTERED_HTML', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Allow unfiltered Uploads', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('ALLOW_UNFILTERED_UPLOADS') && true === ALLOW_UNFILTERED_UPLOADS ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'ALLOW_UNFILTERED_UPLOADS', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Block External URL Requests', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_HTTP_BLOCK_EXTERNAL') && true === WP_HTTP_BLOCK_EXTERNAL ) : 
									echo $enabled;
									if( defined('WP_ACCESSIBLE_HOSTS') ) :
										echo ' / ' . esc_html__( 'Accessible Hosts', 'wphave-admin' ) . ': ' . WP_ACCESSIBLE_HOSTS;
									endif; 
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_HTTP_BLOCK_EXTERNAL', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Redirect Nonexistent Blogs', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#redirect-nonexistent-blogs" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('NOBLOGREDIRECT') && NOBLOGREDIRECT != '' ) :
									echo NOBLOGREDIRECT;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'NOBLOGREDIRECT', 'http://example.com' );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Cookie Domain', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#set-cookie-domain" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('COOKIE_DOMAIN') && COOKIE_DOMAIN != '' ) :
									echo COOKIE_DOMAIN;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'COOKIE_DOMAIN', 'www.example.com' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Cookie Hash', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('COOKIEHASH') && COOKIEHASH ) :
									echo COOKIEHASH;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'COOKIEHASH', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Auth Cookie', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('AUTH_COOKIE') && AUTH_COOKIE ) :
									echo AUTH_COOKIE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'AUTH_COOKIE', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Secure Auth Cookie', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('SECURE_AUTH_COOKIE') && SECURE_AUTH_COOKIE ) :
									echo SECURE_AUTH_COOKIE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'SECURE_AUTH_COOKIE', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Cookie Path', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#additional-defined-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('COOKIEPATH') && COOKIEPATH ) :
									echo COOKIEPATH;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'COOKIEPATH', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Site Cookie Path', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#additional-defined-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('SITECOOKIEPATH') && SITECOOKIEPATH ) :
									echo SITECOOKIEPATH;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'SITECOOKIEPATH', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Admin Cookie Path', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#additional-defined-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('ADMIN_COOKIE_PATH') && ADMIN_COOKIE_PATH ) :
									echo ADMIN_COOKIE_PATH;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'ADMIN_COOKIE_PATH', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Plugins Cookie Path', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#additional-defined-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('PLUGINS_COOKIE_PATH') && PLUGINS_COOKIE_PATH ) :
									echo PLUGINS_COOKIE_PATH;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'PLUGINS_COOKIE_PATH', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Logged In Cookie', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('LOGGED_IN_COOKIE') && LOGGED_IN_COOKIE ) :
									echo LOGGED_IN_COOKIE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'LOGGED_IN_COOKIE', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Test Cookie', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('TEST_COOKIE') && TEST_COOKIE ) :
									echo TEST_COOKIE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'TEST_COOKIE', '' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP User Cookie', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( defined('USER_COOKIE') && USER_COOKIE ) :
									echo USER_COOKIE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'USER_COOKIE', '' );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Directory Permission', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#override-of-default-file-permissions" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FS_CHMOD_DIR') && FS_CHMOD_DIR ) : 
									echo 'chmod' . ' ' . FS_CHMOD_DIR;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FS_CHMOD_DIR', ( 0755 & ~ umask() ) );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP File Permission', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#override-of-default-file-permissions" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FS_CHMOD_FILE') && FS_CHMOD_FILE ) : 
									echo 'chmod' . ' ' . FS_CHMOD_FILE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FS_CHMOD_FILE', ( 0644 & ~ umask() ) );" ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP FTP Method', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FS_METHOD') && FS_METHOD ) : 
									echo FS_METHOD;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FS_METHOD', 'ftpext' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Base', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_BASE') && FTP_BASE ) : 
									echo FTP_BASE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_BASE', '/path/to/wordpress/' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Content Dir', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_CONTENT_DIR') && FTP_CONTENT_DIR ) : 
									echo FTP_CONTENT_DIR;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_CONTENT_DIR', '/path/to/wordpress/wp-content/' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Plugin Dir', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_PLUGIN_DIR') && FTP_PLUGIN_DIR ) : 
									echo FTP_PLUGIN_DIR;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_PLUGIN_DIR ', '/path/to/wordpress/wp-content/plugins/' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP SSH Public Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_PUBKEY') && FTP_PUBKEY ) : 
									echo FTP_PUBKEY;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_PUBKEY', '/home/username/.ssh/id_rsa.pub' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP SSH Private Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_PRIKEY') && FTP_PRIKEY ) : 
									echo FTP_PRIKEY;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_PRIKEY', '/home/username/.ssh/id_rsa' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Username', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_USER') && FTP_USER ) : 
									echo FTP_USER;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_USER', 'username' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Password', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_PASS') && FTP_PASS ) : 
									echo '****';
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_PASS', 'password' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP Host', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_HOST') && FTP_HOST ) : 
									echo FTP_HOST;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_HOST', 'ftp.example.org' );" ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP FTP SSL', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wordpress-upgrade-constants" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('FTP_SSL') && true === FTP_SSL ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'FTP_SSL', false );" ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Site URL', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#wp_siteurl" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_SITEURL') && WP_SITEURL ) : 
									echo WP_SITEURL;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_SITEURL', 'http://example.com/wordpress' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Home', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#blog-address-url" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_HOME') && WP_HOME ) : 
									echo WP_HOME;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_HOME', 'http://example.com' );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'WP Uploads Path', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#moving-uploads-folder" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('UPLOADS') && '' != UPLOADS ) : 
									echo UPLOADS;
								else :
									$upload_dir = wp_upload_dir();
									echo $upload_dir['basedir'];
								endif; ?>
							</td>
							<td><?php echo "define( 'UPLOADS', dirname(__FILE__) . 'wp-content/media' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Template Path', 'wphave-admin'); ?>:</td>
							<td>
								<?php echo TEMPLATEPATH; ?>
							</td>
							<td><?php echo "define( 'TEMPLATEPATH', dirname(__FILE__) . 'wp-content/themes/theme-folder' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Stylesheet Path', 'wphave-admin'); ?>:</td>
							<td>
								<?php echo STYLESHEETPATH; ?>
							</td>
							<td><?php echo "define( 'STYLESHEETPATH', dirname(__FILE__) . 'wp-content/themes/theme-folder' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Content Path', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#moving-wp-content-folder" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo WP_CONTENT_DIR; ?>
							</td>
							<td><?php echo "define( 'WP_CONTENT_DIR', dirname(__FILE__) . '/blog/wp-content' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Content URL', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#moving-wp-content-folder" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo WP_CONTENT_URL; ?>
							</td>
							<td><?php echo "define( 'WP_CONTENT_URL', 'http://example/blog/wp-content' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Plugin Path', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#moving-plugin-folder" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo WP_PLUGIN_DIR; ?>
							</td>
							<td><?php echo "define( 'WP_PLUGIN_DIR', dirname(__FILE__) . '/blog/wp-content/plugins' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Plugin URL', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#moving-plugin-folder" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo WP_PLUGIN_URL; ?>
							</td>
							<td><?php echo "define( 'WP_PLUGIN_URL', 'http://example/blog/wp-content/plugins' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Language Path', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#language-and-language-directory" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php echo WP_LANG_DIR; ?>
							</td>
							<td><?php echo "define( 'WP_LANG_DIR', dirname(__FILE__) . '/wordpress/languages' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Temporary Files Path', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( defined('WP_TEMP_DIR') && '' != WP_TEMP_DIR ) : 
									echo WP_TEMP_DIR;
								else :
									echo get_temp_dir();
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_TEMP_DIR', dirname(__FILE__) . 'wp-content/temp' );"; ?></td>
						</tr>
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Database', 'wphave-admin' ); ?></h2>

				<p><?php echo __( 'Use the following constants to manage important database settings of your WordPress installation in the <code>wp-config.php</code> file. Learn more about <a href="https://wordpress.org/support/article/editing-wp-config-php/#configure-database-settings" target="_blank">here</a>', 'wphave-admin' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Result', 'wphave-admin' ); ?></th>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Example', 'wphave-admin' ); ?></th>
						</tr>
					</thead>  
					<tbody>
						<tr>
							<td><?php esc_html_e( 'MySQL Version', 'wphave-admin' ); ?>:</td>
							<td colspan="2"><?php echo $common->getMySQLVersion(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB Name', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#set-database-name" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td><?php echo DB_NAME; ?></td>
							<td><?php echo "define( 'DB_NAME', 'MyDatabaseName' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB User', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#set-database-user" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td><?php echo DB_USER; ?></td>
							<td><?php echo "define( 'DB_USER', 'MyUserName' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB Host', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#set-database-host" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td><?php echo DB_HOST; ?></td>
							<td><?php echo "define( 'DB_HOST', 'MyDatabaseHost' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB Password', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#set-database-password" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td><?php echo '***'; ?></td>
							<td><?php echo "define( 'DB_PASSWORD', 'MyPassWord' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB Charset', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#database-character-set" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td><?php echo DB_CHARSET; ?></td>
							<td><?php echo "define( 'DB_CHARSET', 'utf8' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'DB Collate', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#database-collation" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DB_COLLATE') && empty( DB_COLLATE ) ) {
									echo $not_entered;
								} else {
									echo DB_COLLATE;
								} ?>
							</td>
							<td><?php echo "define( 'DB_COLLATE', 'utf8_general_ci' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Allow DB Repair', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#automatic-database-optimizing" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('WP_ALLOW_REPAIR') && WP_ALLOW_REPAIR ) : 
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'WP_ALLOW_REPAIR', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Disallow Upgrade Global Tables', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#do_not_upgrade_global_tables" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('DO_NOT_UPGRADE_GLOBAL_TABLES') && true === DO_NOT_UPGRADE_GLOBAL_TABLES ) :
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'DO_NOT_UPGRADE_GLOBAL_TABLES', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Custom User Table', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#custom-user-and-usermeta-tables" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('CUSTOM_USER_TABLE') && CUSTOM_USER_TABLE ) :
									echo CUSTOM_USER_TABLE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'CUSTOM_USER_TABLE', &dollar;table_prefix.'my_users' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Custom User Meta Table', 'wphave-admin'); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#custom-user-and-usermeta-tables" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( defined('CUSTOM_USER_META_TABLE') && CUSTOM_USER_META_TABLE ) :
									echo CUSTOM_USER_META_TABLE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'CUSTOM_USER_META_TABLE', &dollar;table_prefix.'my_usermeta' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Display Database Errors', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( defined('DIEONDBERROR') && true === DIEONDBERROR ) :
									echo $enabled;
								else :
									echo $disabled;
								endif; ?>
							</td>
							<td><?php echo "define( 'DIEONDBERROR', true );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e('WP Database Error Log File', 'wphave-admin'); ?>:</td>
							<td>
								<?php if( defined('ERRORLOGFILE') && ERRORLOGFILE ) :
									echo ERRORLOGFILE;
								else :
									echo $not_entered;
								endif; ?>
							</td>
							<td><?php echo "define( 'ERRORLOGFILE', '/absolute-path-to-file/' );"; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'Table Prefix', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#table_prefix" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td colspan="2"><?php echo $common->get_table_prefix()['tablePrefix']; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Table Base Prefix', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#table_prefix" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td colspan="2"><?php echo $common->get_table_prefix()['tableBasePrefix'] . ' (' . esc_html__( 'defined', 'wphave-admin' ) . ')'; ?></td>
						</tr>
					</tbody>
				</table>

				<h2><?php echo esc_html__( 'Security Keys', 'wphave-admin' ); ?></h2>

				<p><?php echo __( 'Use the following constants to set the security keys for your WordPress installation in the <code>wp-config.php</code> file. Learn more about <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener">here</a>', 'wphave-admin' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Result', 'wphave-admin' ); ?></th>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Example', 'wphave-admin' ); ?></th>
						</tr>
					</thead>  
					<tbody>
						<tr>
							<td><?php esc_html_e( 'WP Auth Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( AUTH_KEY ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'AUTH_KEY', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Secure Auth Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( SECURE_AUTH_KEY ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'SECURE_AUTH_KEY', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Logged In Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( LOGGED_IN_KEY ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'LOGGED_IN_KEY', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Nonce Key', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( NONCE_KEY ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'NONCE_KEY', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Auth Salt', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( AUTH_SALT ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'AUTH_SALT', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Secure Auth Salt', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( SECURE_AUTH_SALT ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'SECURE_AUTH_SALT', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Logged In Auth Salt', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( LOGGED_IN_SALT ) ) {
									echo $sec_key;
								} else {
									echo $entered;
								} ?>
							</td>
							<td><?php echo "define( 'LOGGED_IN_SALT', 'MyKey' );"; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'WP Nonce Salt', 'wphave-admin' ); ?>: <a href="https://wordpress.org/support/article/editing-wp-config-php/#security-keys" target="_blank" rel="noopener"><?php echo $help; ?></a></td>
							<td>
								<?php if( empty( NONCE_SALT ) ) {
									echo $sec_key;
								} else {
									echo $entered;;
								} ?>
							</td>
							<td><?php echo "define( 'NONCE_SALT', 'MyKey' );"; ?></td>
						</tr>
					</tbody>
				</table>

			<?php } else {
				echo wphave_admin_plugin_activation_message();
			} ?>

		</div>

	<?php }

endif;