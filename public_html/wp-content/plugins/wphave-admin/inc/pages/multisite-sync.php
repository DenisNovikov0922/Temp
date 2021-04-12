<?php 

/*
 *******************
 * MULTISITE SYNC SUBPAGE
 *******************
 *
 *	Add a subpage for multisite sync without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_multisite_sync_admin_menu' ) ) :

	function wphave_admin_multisite_sync_admin_menu() {

		if( ! is_multisite() ) {
			return false;	
		}

		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'Multisite Sync', 'wphave-admin' ),
			esc_html__( 'Multisite Sync', 'wphave-admin' ),
			'manage_network',
			'wphave-admin-update-network',
			'wphave_admin_update_page'
		);

	}

endif;

add_action('admin_menu', 'wphave_admin_multisite_sync_admin_menu');


/*
 *******************
 * UPDATE ALL NETWORK BLOGS
 *******************
 *
 *	Update all other network blogs with the current setting of the main blog.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_update_blog' ) ) :

	function wphave_admin_update_blog() {

		if( ! is_multisite() ) {
			return;	
		}
		
		$well_data = true;
		$status = array();
		
		// Update a specific blog
		if( ! empty( $_POST['wpat_update_blog'] ) ) {

			if( ! isset( $_POST['wpat_start_update'] ) || $_POST['wpat_start_update'] != 'update_settings' ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}
			
			if( ! wp_verify_nonce( $_POST['wpat-update'], 'wpat-update' ) ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}

			if( ! current_user_can( 'manage_options' ) ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}	
			
			if( isset( $_POST['wpat_update_blog_ids'] ) && empty( $_POST['wpat_update_blog_ids'] ) ) {
				$well_data = false;
				$status['notice'] = 'empty';	
				$status['label'] = 'error';			
			}
			
			if( isset( $_POST['wpat_update_blog_ids'] ) && ! empty( $_POST['wpat_update_blog_ids'] ) ) {
				// Get user entered blog ids
				$blogs_ids = $_POST['wpat_update_blog_ids'];

				// Remove empty spaces
				$blogs_ids = str_replace(' ', '', $blogs_ids);

				// Remove unnecessary comma at the end
				$blogs_ids = rtrim($blogs_ids, ',');

				// Check for the correct characters
				// --> Only allow "numbers" + "comma"
				if( ! preg_match('/^\d+(?:,\d+)*$/', $blogs_ids) ) {
					$well_data = false;
					$status['notice'] = 'wrong-characters';	
					$status['label'] = 'error';
				}
			}
			
			// Build beautiful array
			$blogs = explode(',', $blogs_ids);
			
			if( $well_data ) {			
				foreach( $blogs as $blog ) {

					$blog_id = $blog;

					if( $blog_id ) {
						switch_to_blog( $blog_id );
					}

					// Get options from main blog (ID = 1)
					$blog_id = 1;
					$options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options', array() );

					foreach( $options as $key => $value ) {
						$options[$key] = $options[$key];
					}

					// Update options
					update_option('wp_admin_theme_settings_options', $options, 'yes');

					if ( $blog_id ) {
						restore_current_blog();
					}

				}			
			
				$status['notice'] = 'success';
				$status['label'] = 'updated';
				$status['ids'] = $blogs_ids;
			}

		// Update all network blogs
		} elseif( ! empty( $_POST['wpat_update_all_blogs'] ) ) {

			if( ! isset( $_POST['wpat_start_update_all'] ) || $_POST['wpat_start_update_all'] != 'update_all_settings' ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}
			
			if( ! wp_verify_nonce( $_POST['wpat-update-all'], 'wpat-update-all' ) ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}

			if( ! current_user_can( 'manage_options' ) ) {
				$well_data = false;
				$status['notice'] = 'no-permission';
				$status['label'] = 'error';
			}	
			
			global $wpdb;
			
			$blogs = $wpdb->get_results("
				SELECT blog_id
				FROM {$wpdb->blogs}
				WHERE site_id = '{$wpdb->siteid}'
				AND archived = '0'
				AND spam = '0'
				AND deleted = '0'
			");

			if( $well_data ) {
				
				// Get all blog ids
				$blogs_ids = array();
				
				foreach( $blogs as $blog ) {

					$blog_id = $blog->blog_id;

					$blogs_ids[] = $blog_id;
					
					if( $blog_id ) {
						switch_to_blog( $blog_id );
					}

					// Get options from main blog (ID = 1)
					$blog_id = 1;
					$options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options', array() );

					foreach( $options as $key => $value ) {
						$options[$key] = $options[$key];
					}

					// Update options
					update_option('wp_admin_theme_settings_options', $options, 'yes');

					if ( $blog_id ) {
						restore_current_blog();
					}

				}

				$status['notice'] = 'success-all';
				$status['label'] = 'updated';
				$status['ids'] = implode(',', $blogs_ids );				
			}

		}
		
		return $status;
		
	}

endif;


/*
 *******************
 * OUTPUT MULTISITE SYNC PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the multisite sync subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_update_page' ) ) :

	function wphave_admin_update_page() {
		
		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}

		if( ! is_multisite() ) {
			return;	
		}

		// Get parent blog site data			
		$blog_id = 1;
		$blog_name = get_blog_option( $blog_id, 'blogname' ); ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title(); ?>
			</h1> 

			<?php if( wphave_admin_activation_status() ) {

				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'multisite'; 
				echo wphave_admin_tab_menu( $active_tab ); ?>

				<?php if( ! empty( wphave_admin_update_blog() ) ) { 
					$status = wphave_admin_update_blog(); ?>
					<div class="<?php if( isset( $status['label'] ) ) { echo esc_html( $status['label'] ); } else { ?>updated<?php } ?>">
						<p>
							<strong>
								<?php if( $status['notice'] === 'success' ) {
									printf( wp_kses_post( __( 'Your selected blog(s) with ID "%1$s" has been updated.', 'wphave-admin' ) ), $status['ids'] );
								} elseif( $status['notice'] === 'success-all' ) {
									printf( wp_kses_post( __( 'All network sites with ID "%1$s" has been updated.', 'wphave-admin' ) ), $status['ids'] );
								} elseif( $status['notice'] === 'no-permission' ) {
									echo esc_html__( 'No permission.', 'wphave-admin' );
								} elseif( $status['notice'] == 'empty' ) {
									echo esc_html__( 'Please enter a blog id.', 'wphave-admin' );
								} elseif( $status['notice'] === 'wrong-characters' ) {
									echo esc_html__( 'Wrong characters entered. Only numbers and commas are allowed.', 'wphave-admin' );
								} elseif( $status['notice'] === 'failed' ) {
									echo esc_html__( 'Something went wrong.', 'wphave-admin' );
								} ?>
							</strong>
						</p>
					</div>
				<?php } ?>

				<h2>
					<?php echo esc_html__( 'Share Options', 'wphave-admin' ); ?>
				</h2>

				<p>
					<?php echo esc_html__( 'Update your admin theme options for all network websites together.', 'wphave-admin' ); ?>

					<?php echo esc_html__( 'You will share the following options from Blog ID', 'wphave-admin' ); ?>: <strong><?php echo esc_html( $blog_id ); ?></strong> / <?php echo esc_html__( 'Blog Name', 'wphave-admin' ); ?>: <strong><?php echo esc_html( $blog_name ); ?></strong> <?php echo esc_html__( 'for all network blogs', 'wphave-admin' ); ?>.
				</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th style="width: 20%" class="manage-column"><?php echo esc_html__( 'Option', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Value', 'wphave-admin' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php 

						// Define field status
						$is_visible = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Visible', 'wphave-admin' ) . '</span>';
						$is_hidden = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Hidden', 'wphave-admin' ) . '</span>';
						$is_enabled = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Enabled', 'wphave-admin' ) . '</span>';
						$is_disabled = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Disabled', 'wphave-admin' ) . '</span>';
						$is_none = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Not selected', 'wphave-admin' ) . '</span>';
						$is_not_added = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Not added', 'wphave-admin' ) . '</span>';
						$is_activate = '<span class="sys-status enable"><span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Activated', 'wphave-admin' ) . '</span>';
						$is_deactivate = '<span class="sys-status disable"><span class="dashicons dashicons-no"></span> ' . esc_html__( 'Deactivated', 'wphave-admin' ) . '</span>';

						$wphave_options = new wphave_admin_settings();

						// Get all options
						$options = get_option('wp_admin_theme_settings_options');

						// Get all pre options
						$pre_options = $wphave_options->pre_options;

						// Get all option labels							
						$wphave_labels = $wphave_options->labels;							
						$label = $wphave_labels;

						// List option fields
						foreach( $options as $key => $value ) {                            

							if( $key == 'company_box_logo' ) {

								$status = $is_not_added;
								if( $value ) {
									$status = $options['company_box_logo'];
								}									

							} elseif( $key == 'company_box_logo_size' ) {

								$status = $pre_options['company_box_logo_size'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								if( $value ) {
									$status = $options['company_box_logo_size'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								}

							} elseif( $key == 'meta_referrer_policy' ) {

								$status = $is_disabled;
								if( $value != 'none' ) {
									$status = $options['meta_referrer_policy'];
								}

							} elseif( $key == 'spacing' ) {

								$status = $is_disabled;
								if( $value ) {
									$status = $is_enabled;
								}

							} elseif( $key == 'spacing_max_width' ) {

								$status = $pre_options['spacing_max_width'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								if( $value ) {
									$status = $options['spacing_max_width'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								}

							} elseif( $key == 'left_menu_expand' ) {

								$status = $is_deactivate;
								if( $value ) {
									$status = $is_activate;
								}

							} elseif( $key == 'google_webfont' ) {

								$status = $is_not_added;
								if( $value ) {
									$status = $options['google_webfont'];
								}

							} elseif( $key == 'google_webfont_weight' ) {

								$status = $is_not_added;
								if( $value ) {
									$status = $options['google_webfont_weight'];
								}

							} elseif( $key == 'toolbar_icon' ) {

								$status = $is_not_added;
								if( $value ) {
									$status = $options['toolbar_icon'];
								}

							} elseif( $key == 'theme_color' ) {

								$status = $pre_options['theme_color'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $pre_options['theme_color'] ) . '"></span>';
								if( $value ) {
									$status = $options['theme_color'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $options['theme_color'] ) . '"></span>';
								}

							} elseif( $key == 'theme_background' ) {

								$status = $pre_options['theme_background'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $pre_options['theme_background'] ) . '"></span>';
								if( $value ) {
									$status = $options['theme_background'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $options['theme_background'] ) . '"></span>';
								}

							} elseif( $key == 'theme_background_end' ) {

								$status = $pre_options['theme_background_end'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $pre_options['theme_background_end'] ) . '"></span>';
								if( $value ) {
									$status = $options['theme_background_end'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $options['theme_background_end'] ) . '"></span>';
								}

							} elseif( $key == 'toolbar_color' ) {

								$status = $pre_options['toolbar_color'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $pre_options['toolbar_color'] ) . '"></span>';
								if( $value ) {
									$status = $options['toolbar_color'] . ' <span style="display:inline-block;width:10px;height:10px;background-color:' . esc_html( $options['toolbar_color'] ) . '"></span>';
								}

							} elseif( $key == 'login_title' ) {

								$status = $pre_options['login_title'];
								if( $value ) {
									$status = $options['login_title'];
								}

							} elseif( $key == 'logo_upload' ) {

								$status = $is_none;
								if( $value ) {
									$status = $options['logo_upload'];
								}

							} elseif( $key == 'logo_size' ) {

								$status = $pre_options['logo_size'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								if( $value ) {
									$status = $options['logo_size'] . ' ' . esc_html__( 'Pixel', 'wphave-admin' );
								}

							} elseif( $key == 'login_bg' ) {

								$status = $is_none;
								if( $value ) {
									$status = $options['login_bg'];
								}

							} elseif( $key == 'css_admin' ) {

								$textarea_start = '<textarea class="option-textarea" readonly>';
								$textarea_end = '</textarea>';

								$status = $is_not_added;
								if( $value ) {
									$status = $textarea_start . wp_kses( $options['css_admin'], array() ) . $textarea_end;
								}

							} elseif( $key == 'css_login' ) {

								$textarea_start = '<textarea class="option-textarea" readonly>';
								$textarea_end = '</textarea>';

								$status = $is_not_added;
								if( $value ) {
									$status = $textarea_start . wp_kses( $options['css_login'], array() ) . $textarea_end;
								}

							} elseif( $key == 'wp_header_code' ) {

								$textarea_start = '<textarea class="option-textarea" readonly>';
								$textarea_end = '</textarea>';

								$status = $is_not_added;
								if( $value ) {
									$status = $textarea_start . wp_kses( $options['wp_header_code'], array() ) . $textarea_end;
								}

							} elseif( $key == 'wp_footer_code' ) {

								$textarea_start = '<textarea class="option-textarea" readonly>';
								$textarea_end = '</textarea>';

								$status = $is_not_added;
								if( $value ) {
									$status = $textarea_start . wp_kses( $options['wp_footer_code'], array() ) . $textarea_end;
								}

							} elseif( $key == 'login_disable' || $key == 'wp_svg' || $key == 'wp_ico' ) {

								$status = $is_deactivate;
								if( $value ) {
									$status = $is_activate;
								}

							} elseif( $key == 'disable_page_system' || $key == 'disable_page_export' || $key == 'disable_page_ms' || $key == 'disable_theme_options' || $key == 'wp_version_tag' || $key == 'wp_emoji' || $key == 'wp_feed_links' || $key == 'wp_rsd_link' || $key == 'wp_wlwmanifest' || $key == 'wp_shortlink' || $key == 'wp_rest_api' || $key == 'wp_oembed' || $key == 'wp_xml_rpc' || $key == 'wp_heartbeat' || $key == 'wp_rel_link' || $key == 'wp_self_pingback' || $key == 'mb_custom_fields' || $key == 'mb_commentstatus' || $key == 'mb_comments' || $key == 'mb_author' || $key == 'mb_category' || $key == 'mb_format' || $key == 'mb_pageparent' || $key == 'mb_postexcerpt' || $key == 'mb_postimage' || $key == 'mb_revisions' || $key == 'mb_slug' || $key == 'mb_tags' || $key == 'mb_trackbacks' || $key == 'dbw_quick_press' || $key == 'dbw_right_now' || $key == 'dbw_activity' || $key == 'dbw_primary' || $key == 'dbw_welcome' || $key == 'dbw_wpat_user_log' || $key == 'dbw_wpat_sys_info' || $key == 'dbw_wpat_count_post' || $key == 'dbw_wpat_count_page' || $key == 'dbw_wpat_count_comment' || $key == 'dbw_wpat_recent_post' || $key == 'dbw_wpat_recent_page' || $key == 'dbw_wpat_recent_comment' || $key == 'dbw_wpat_memory' || $key == 'wt_pages' || $key == 'wt_calendar' || $key == 'wt_archives' || $key == 'wt_meta' || $key == 'wt_search' || $key == 'wt_text' || $key == 'wt_categories' || $key == 'wt_recent_posts' || $key == 'wt_recent_comments' || $key == 'wt_rss' || $key == 'wt_tag_cloud' || $key == 'wt_nav' || $key == 'wt_image' || $key == 'wt_audio' || $key == 'wt_video' || $key == 'wt_gallery' || $key == 'wt_html' ) {

								$status = $is_activate;
								if( $value ) {
									$status = $is_deactivate;
								}

							} else {

								$status = $is_visible;
								if( $value ) {
									$status = $is_hidden;
								}

							} ?>

							<tr>
								<td><?php echo esc_html( $label[ $key ] ); ?>:</td>
								<td><?php echo $status; ?></td>
							</tr>

						<?php } ?>
					</tbody>
				</table>         

				<h2>
					<?php esc_html_e( 'Update only specific network blogs', 'wphave-admin' ); ?>
				</h2>
			
				<form method="post">
					<p style="margin-top: 20px">
						<?php echo esc_html__( 'You can update only a specific network sites by entering the ID.', 'wphave-admin' ); ?>
						<?php echo esc_html__( 'Please separate several IDs with commas.', 'wphave-admin' ); ?>
					</p>

					<p>
						<label for="wpat_update_blog_ids">
							<?php echo esc_html__( 'Blog ID(s)', 'wphave-admin' ); ?>: 
						</label>
						<input type="text" name="wpat_update_blog_ids" id="wpat_update_blog_ids" placeholder="1,22,6">
					</p>

					<p class="submit">
						<?php wp_nonce_field( 'wpat-update', 'wpat-update' ); ?>
						<input type="hidden" name="wpat_start_update" value="update_settings" />
						<input type="submit" name="wpat_update_blog" class="button" value="<?php esc_attr_e( 'Update specific network blog(s)', 'wphave-admin' ); ?>" onclick="return confirm('<?php esc_html_e( 'Are you sure you want to run the update for the entered blog ID(s)?', 'wphave-admin' ); ?>');" />
					</p>
				</form>          

				<h2>
					<?php esc_html_e( 'Update all network blogs', 'wphave-admin' ); ?>
				</h2>   

				<form method="post">
					<p style="margin-top: 20px">
						<?php echo esc_html__( 'You will update the following network sites', 'wphave-admin' ) . ':'; ?>
					</p>

					<p>
						<?php 

						// Get all multisite pages and set max. limit to 500 pages (default pages is 100)
						$subsites = get_sites( 
							array(
								'number' => 0, //--> Set to unlimited subsites
							) 
						);

						foreach( $subsites as $subsite ) {
							$subsite_id = get_object_vars( $subsite )['blog_id'];
							$subsite_name = get_blog_details( $subsite_id )->blogname;
							echo esc_html__( 'Blog Name', 'wphave-admin' ) . ': <strong>'. $subsite_name . '</strong> (' . esc_html__( 'ID', 'wphave-admin' ) . ': ' . $subsite_id . ')<br/>';
						} ?>
					</p>

					<p class="submit">
						<?php wp_nonce_field( 'wpat-update-all', 'wpat-update-all' ); ?>
						<input type="hidden" name="wpat_start_update_all" value="update_all_settings" />
						<input type="submit" name="wpat_update_all_blogs" class="button-primary" value="<?php esc_attr_e( 'Update all network blogs', 'wphave-admin' ); ?>" onclick="return confirm('<?php esc_html_e( 'Are you sure you want to run the update for all blogs?', 'wphave-admin' ); ?>');" />
					</p>
				</form>

			<?php } else {
			
				echo wphave_admin_plugin_activation_message();
			
			} ?>

		</div>

	<?php }

endif;