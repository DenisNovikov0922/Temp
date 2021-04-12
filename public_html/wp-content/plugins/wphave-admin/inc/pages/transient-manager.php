<?php 

/*
 *******************
 * TRANSIENT MANAGER SUBPAGE
 *******************
 *
 *	Add a subpage for Transient Manager without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_transient_manager_admin_menu' ) ) :

	function wphave_admin_transient_manager_admin_menu() {
			
		add_submenu_page(
			NULL, // <-- Disable the menu item
			esc_html__( 'Transient Manager', 'wphave-admin' ),
			esc_html__( 'Transient Manager', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-transient-manager',
			'wphave_admin_transient_manager_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_transient_manager_admin_menu' );


/*
 *******************
 * GET ALL TRANSIENTS
 *******************
 *
 *	Get all transients from the database.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transients' ) ) :

	function wphave_admin_get_transients( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'offset' => 0,
			'number' => 30,
			'search' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		$cache_key = md5( serialize( $args ) );
		$transients = wp_cache_get( $cache_key );

		if( false === $transients ) {

			$sql = "SELECT * FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%'";

			if( ! empty( $args['search'] ) ) {
				$search = esc_sql( $args['search'] );
				$sql .= " AND option_name LIKE '%{$search}%'";
			}

			$offset = absint( $args['offset'] );
			$number = absint( $args['number'] );
			$sql .= " ORDER BY option_id DESC LIMIT $offset,$number;";

			$transients = $wpdb->get_results( $sql );

			wp_cache_set( $cache_key, $transients, '', 3600 );

		}

		return $transients;

	}

endif;


/*
 *******************
 * GET TOTAL TRANSIENTS
 *******************
 *
 *	Get the number of total transients from the database.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_total_transients' ) ) :

	function wphave_admin_get_total_transients( $search = '' ) {

		global $wpdb;

		if( ! empty( $search ) ) {

			$count = wp_cache_get( 'wpat_transients_count_' . sanitize_key( $search ) );

			if( false === $count ) {
				$search = esc_sql( $search );
				$count = $wpdb->get_var( "SELECT count(option_id) FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%' AND option_name LIKE '%{$search}%'" );
				wp_cache_set( 'wpat_transients_' . sanitize_key( $search ), $count, '', 3600 );
			}

		} else {

			$count = wp_cache_get( 'wpat_transients_count' );

			if( false === $count ) {
				$count = $wpdb->get_var( "SELECT count(option_id) FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%'" );
				wp_cache_set( 'wpat_transients_count', $count, '', 3600 );
			}

		}

		return $count;

	}

endif;


/*
 *******************
 * GET EXPIRED TRANSIENTS
 *******************
 *
 *	Get the number of expired transients from the database.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_total_expired_transients' ) ) :

	function wphave_admin_get_total_expired_transients() {

		global $wpdb;
		
		$time_now = time();
		$expired = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '%_transient_timeout_%' AND option_value+0 < $time_now" );

		if( empty( $expired ) ) {
			return '0';
		}
		
		return count( $expired );
		
	}

endif;


/*
 *******************
 * GET ID OF TRANSIENTS
 *******************
 *
 *	Get the id of all transients from the database.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_by_id' ) ) :

	function wphave_admin_get_transient_by_id( $id = 0 ) {

		global $wpdb;

		$id = absint( $id );

		if( empty( $id ) ) {
			return false;
		}

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_id = %d", $id ) );

	}

endif;


/*
 *******************
 * GET NAME OF TRANSIENTS
 *******************
 *
 *	Get the name of the transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_name' ) ) :

	function wphave_admin_get_transient_name( $transient ) {
		
		$length = false !== strpos( $transient->option_name, 'site_transient_' ) ? 16 : 11;		
		return substr( $transient->option_name, $length, strlen( $transient->option_name ) );
		
	}

endif;


/*
 *******************
 * GET FULL NAME OF TRANSIENTS
 *******************
 *
 *	Get the full name of the transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_full_name' ) ) :

	function wphave_admin_get_transient_full_name( $transient ) {
		
		return $transient->option_name;
		
	}

endif;


/*
 *******************
 * GET VALUE OF TRANSIENTS
 *******************
 *
 *	Get the value of the transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_value' ) ) :

	function wphave_admin_get_transient_value( $transient ) {

		$value = maybe_unserialize( $transient->option_value );

		// Check the transient value is an array
		if( is_array( $value ) ) {
			$value = 'array';
			
		// Check the transient value is an object
		} elseif( gettype( $value ) == 'object' ) {
			$value = 'object';
		}

		return wp_trim_words( $value, 5 );

	}

endif;


/*
 *******************
 * GET EXPIRATION TIME OF TRANSIENTS
 *******************
 *
 *	Get the expiration time of the transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_expiration_time' ) ) :

	function wphave_admin_get_transient_expiration_time( $transient ) {

		$time = get_option( '_transient_timeout_' . wphave_admin_get_transient_name( $transient ) );
		
		if( false !== strpos( $transient->option_name, 'site_transient_' ) ) {
			$time = get_option( '_site_transient_timeout_' . wphave_admin_get_transient_name( $transient ) );
		}

		return $time;

	}

endif;


/*
 *******************
 * GET READABLE EXPIRATION TIME OF TRANSIENTS
 *******************
 *
 *	Get the readable expiration time of the transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_get_transient_expiration_time_readable' ) ) :

	function wphave_admin_get_transient_expiration_time_readable( $transient ) {

		$time_now = time();
		$expiration = wphave_admin_get_transient_expiration_time( $transient );

		if( empty( $expiration ) ) {
			return __( 'Does not expire', 'wphave-admin' );
		}

		if( $time_now > $expiration ) {
			return __( 'Expired', 'wphave-admin' );
		}
		
		return human_time_diff( $time_now, $expiration );

	}

endif;


/*
 *******************
 * DEFINE TRANSIENT PROCESS ACTIONS
 *******************
 *
 *	Manage different transient manager actions like removeing or updating transients.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_transient_process_actions' ) ) :

	function wphave_admin_transient_process_actions() {

		if( empty( $_REQUEST['action'] ) ) {
			return;
		}

		if( empty( $_REQUEST['transient'] ) && ( 'wpat_suspend_transients' !== $_REQUEST['action'] && 'wpat_unsuspend_transients' !== $_REQUEST['action'] ) ) {
			return;
		}

		if( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if( ! wp_verify_nonce( $_REQUEST['_wpnonce'] , 'wpat_transient_manager' ) ) {
			return;
		}

		if( 'wpat_suspend_transients' !== $_REQUEST['action'] && 'wpat_unsuspend_transients' !== $_REQUEST['action'] ) {

			$search = ! empty( $_REQUEST['s'] ) ? urlencode( $_REQUEST['s'] ) : '';
			$transient = $_REQUEST['transient'];
			$site_wide = isset( $_REQUEST['name'] ) && false !== strpos( $_REQUEST['name'], '_site_transient' );

		}

		switch( $_REQUEST['action'] ) {

			case 'wpat_delete_transient' :
				wphave_admin_delete_transient( $transient, $site_wide );
				wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient&s=' . $search ) ); 
				exit;
				break;

			case 'wpat_update_transient' :
				wphave_admin_update_transient( $transient, $site_wide );
				wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient&s=' . $search ) ); 
				exit;
				break;

			case 'wpat_delete_expired_transients' :
				wphave_admin_delete_all_expired_transients();
				wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient' ) ); 
				exit;
				break;

			case 'wpat_delete_transients_with_expiration' :
				wphave_admin_delete_all_expiration_transients();
				wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient' ) ); 
				exit;
				break;

			case 'wpat_suspend_transients' :
				update_option( 'wpat_tm_suspend', 1, 'yes' );
				wp_safe_redirect( remove_query_arg( array( 'action', '_wpnonce' ) ) ); 
				exit;
				break;

			case 'wpat_unsuspend_transients' :
				delete_option( 'wpat_tm_suspend', 1, 'yes' );
				wp_safe_redirect( remove_query_arg( array( 'action', '_wpnonce' ) ) ); 
				exit;
				break;

			case 'wpat_delete_all_transients' :
				wphave_admin_delete_all_transients();
				wp_safe_redirect( admin_url( 'tools.php?page=wphave-admin-transient-manager&tab=transient' ) ); 
				exit;
				break;

		}

	}

endif;

add_action( 'admin_init', 'wphave_admin_transient_process_actions' );


/*
 *******************
 * DELETE TRANSIENT PROCESS
 *******************
 *
 *	Create the process to delete a transient.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_delete_transient' ) ) :

	function wphave_admin_delete_transient( $transient = '', $site_wide = false ) {

		if( empty( $transient ) ) {
			return false;
		}

		if( false !== $site_wide ) {
			return delete_site_transient( $transient );
		}
		
		return delete_transient( $transient );

	}

endif;


/*
 *******************
 * BULK DELETE TRANSIENT PROCESS
 *******************
 *
 *	Create the bulk process to delete a transients.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_bulk_delete_transients' ) ) :

	function wphave_admin_bulk_delete_transients( $transients = array() ) {

		if( empty( $transients ) ) {
			return false;
		}

		foreach( $transients as $transient ) {
			$site_wide = ( strpos( $transient, '_site_transient' ) !== false );
			$name = str_replace( $site_wide ? '_site_transient_timeout_' : '_transient_timeout_', '', $transient );
			wphave_admin_delete_transient( $name, $site_wide );
		}

		return true;

	}

endif;


/*
 *******************
 * DELETE ALL EXPIRED TRANSIENTS PROCESS
 *******************
 *
 *	Create the process to delete all expired transients.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_delete_all_expired_transients' ) ) :

	function wphave_admin_delete_all_expired_transients() {

		global $wpdb;

		$time_now = time();
		$expired = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '%_transient_timeout_%' AND option_value+0 < $time_now" );

		return wphave_admin_bulk_delete_transients( $expired );

	}

endif;


/*
 *******************
 * DELETE ALL EXPIRATION TRANSIENTS PROCESS
 *******************
 *
 *	Create the process to delete all expiration transients.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_delete_all_expiration_transients' ) ) :

	function wphave_admin_delete_all_expiration_transients() {

		global $wpdb;

		$will_expire = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '%_transient_timeout_%'" );

		return wphave_admin_bulk_delete_transients( $will_expire );

	}

endif;


/*
 *******************
 * DELETE ALL TRANSIENTS PROCESS
 *******************
 *
 *	Create the process to delete all transients.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_delete_all_transients' ) ) :

	function wphave_admin_delete_all_transients() {

		global $wpdb;

		$count = $wpdb->query(
			"DELETE FROM $wpdb->options
			WHERE option_name LIKE '\_transient\_%'"
		);

		return $count;
	}

endif;


/*
 *******************
 * UPDATE TRANSIENT PROCESS
 *******************
 *
 *	Create the process to update all transients.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_update_transient' ) ) :

	function wphave_admin_update_transient( $transient = '', $site_wide = false ) {

		if( empty( $transient ) ) {
			return false;
		}

		$value = sanitize_text_field( $_POST['value'] );
		$expiration = sanitize_text_field( $_POST['expires'] );
		$expiration = $expiration - time();

		if( false !== $site_wide ) {
			return set_site_transient( $transient, $value, $expiration );
		}
		
		return set_transient( $transient, $value, $expiration );

	}

endif;


/*
 *******************
 * BLOCK UPDATE TRANSIENT PROCESS
 *******************
 *
 *	Prevent transient from being updated if transients are suspended.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_maybe_block_update_transient' ) ) :

	function wphave_admin_maybe_block_update_transient( $value, $option, $old_value ) {

		if( ! get_option( 'wpat_tm_suspend' ) ) {
			return $value;
		}

		if( false === strpos( $option, '_transient' ) ) {
			return $value;
		}

		return false;

	}

endif;

add_filter( 'pre_update_option', 'wphave_admin_maybe_block_update_transient', -1, 3 );
add_filter( 'pre_get_option', 'wphave_admin_maybe_block_update_transient', -1, 3 );


/*
 *******************
 * BLOCK SET TRANSIENT PROCESS
 *******************
 *
 *	Prevent transient from being updated if transients are suspended.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_maybe_block_set_transient' ) ) :

	function wphave_admin_maybe_block_set_transient( $option, $value ) {

		if( ! get_option( 'wpat_tm_suspend' ) ) {
			return;
		}

		if( false === strpos( $option, '_transient' ) ) {
			return;
		}

		delete_option( $option );

	}

endif;

add_action( 'added_option', 'wphave_admin_maybe_block_set_transient', -1, 2 );


/*
 *******************
 * OUTPUT TRANSIENT MANAGER PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the transient manager subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_transient_manager_page' ) ) :

	function wphave_admin_transient_manager_page() { 
		
		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}

		$search = ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		$page = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		$per_page = 20;
		$offset = $per_page * ( $page - 1 );
		$count = wphave_admin_get_total_transients( $search );
		$pages = ceil( $count / $per_page );
		$expired_count = wphave_admin_get_total_expired_transients();
		
		$args = array(
			'search' => $search,
			'offset' => $offset,
			'number' => $per_page,
		);
		
		$pagination = paginate_links( array(
			'base' => 'tools.php?%_%',
			'format' => '&paged=%#%',
			'total' => $pages,
			'current' => $page,
			'prev_text' => '‹',
			'next_text' => '›',
			'show_all' => false,
			'before_page_number' => '',
			'after_page_number' => '',
		));
		
		$transients = wphave_admin_get_transients( $args ); ?>

		<div class="wrap">

			<h1>
				<?php echo wphave_admin_title( esc_html__( 'Transient Manager', 'wphave-admin' ) ); ?>
			</h1> 

			<?php if( wphave_admin_activation_status() ) {

				// Output the tab menu
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'transient'; 
				echo wphave_admin_tab_menu( $active_tab ); ?>

				<?php if( ! empty( $_GET['action'] ) && 'wpat_edit_transient' == $_GET['action'] ) {
					$transient = wphave_admin_get_transient_by_id( absint( $_GET['trans_id'] ) ); ?>

					<h2>
						<?php echo esc_html__( 'Edit Transient', 'wphave-admin' ); ?>
					</h2>

					<form method="post">

						<table class="form-table">
							<tbody>
								<tr>
									<th>
										<?php echo esc_html__( 'Name', 'wphave-admin' ); ?>
									</th>
									<td>
										<input type="text" class="large-text" name="name" value="<?php echo esc_attr( $transient->option_name ); ?>" />
									</td>
								</tr>
								<tr>
									<th>
										<?php echo esc_html__( 'Expires In', 'wphave-admin' ); ?>
									</th>
									<td>
										<input type="text" class="large-text" name="expires" value="<?php echo wphave_admin_get_transient_expiration_time( $transient ); ?>"/>
									</td>
								</tr>
								<tr>
									<th>
										<?php echo esc_html__( 'Value', 'wphave-admin' ); ?>
									</th>
									<td>
										<textarea class="large-text" name="value" rows="10" cols="50"><?php echo esc_textarea( $transient->option_value ); ?></textarea>
									</td>
								</tr>
						</table>

						<input type="hidden" name="transient" value="<?php echo esc_attr( wphave_admin_get_transient_name( $transient ) ); ?>"/>
						<input type="hidden" name="action" value="wpat_update_transient"/>
						<?php wp_nonce_field( 'wpat_transient_manager' ); ?>
						<?php submit_button(); ?>

					</form>

					<button class="button-secondary" onclick="history.back();">
						<?php echo esc_html__( 'Cancel', 'wphave-admin' ); ?>
					</button>

				<?php } else { ?>

					<br>
			
					<ul class="subsubsub">
						<li class="all">
							<a href="edit.php?post_type=post" class="current" aria-current="page">
								<?php echo esc_html__( 'All Transients', 'wphave-admin' ); ?> 
								<span class="count" style="display: inline-block;"><?php echo esc_html( $count ); ?></span>
							</a> |
						</li>
						<li class="publish">
							<a href="edit.php?post_status=publish&amp;post_type=post">
								<?php echo esc_html__( 'Expired Transients', 'wphave-admin' ); ?> 
								<span class="count" style="display: inline-block;"><?php echo esc_html( $expired_count ); ?></span>
							</a>
						</li>
					</ul>
			
					<form method="get">
						<p class="search-box">
							<button style="margin-left: 6px;" class="alignright button-secondary" onclick="window.location.reload();">
								<?php echo esc_html__( 'Refresh', 'wphave-admin' ); ?>
							</button>
							<input type="hidden" name="page" value="wphave-admin-transient_manager"/>
							<label class="screen-reader-text" for="transient-search-input">
								<?php echo esc_html__( 'Search', 'wphave-admin' ); ?>
							</label>
							<input type="search" id="transient-search-input" name="s" value="<?php echo esc_attr( $search ); ?>"/>
							<input type="submit" class="button-secondary" value="<?php echo esc_html__( 'Search Transients', 'wphave-admin' ); ?>"/>
						</p>
					</form>
			
					<div class="tablenav top">
						<div class=" actions bulkactions">
							
							<form method="post" style="display: inline-block">
								<input type="hidden" name="action" value="wpat_delete_expired_transients" />
								<input type="hidden" name="transient" value="all" />
								<?php wp_nonce_field( 'wpat_transient_manager' ); ?>
								<input type="submit" class="button secondary" value="<?php echo esc_html__( 'Delete Expired Transients', 'wphave-admin' ); ?>" />
							</form>
							
							<form method="post" style="display: inline-block">
								<input type="hidden" name="action" value="wpat_delete_transients_with_expiration" />
								<input type="hidden" name="transient" value="all" />
								<?php wp_nonce_field( 'wpat_transient_manager' ); ?>
								&nbsp;
								<input type="submit" class="button secondary" value="<?php echo esc_html__( 'Delete Transients with an Expiration', 'wphave-admin' ); ?>" />
							</form>
							
						</div>
					</div>
			
					<h2 class="screen-reader-text">
						<?php echo esc_html__( 'Transient', 'wphave-admin' ); ?>
					</h2>

					<table class="wp-list-table widefat fixed striped posts">
						
						<thead>
							<tr>
								<th style="width: 45%"><?php echo esc_html__( 'Name', 'wphave-admin' ); ?></th>
								<th style="width: 40%"><?php echo esc_html__( 'Value', 'wphave-admin' ); ?></th>
								<th style="width: 15%"><?php echo esc_html__( 'Expires In', 'wphave-admin' ); ?></th>
								<th style="width: 40px"><?php echo esc_html__( 'ID', 'wphave-admin' ); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php if( $transients ) { 
								foreach( $transients as $transient ) {

									$delete_url = wp_nonce_url( add_query_arg( array( 
										'action' => 'wpat_delete_transient', 
										'transient' => wphave_admin_get_transient_name( $transient ), 
										'name' => $transient->option_name 
									) ), 'wpat_transient_manager' );
							  
									$edit_url = add_query_arg( array( 
										'action' => 'wpat_edit_transient', 
										'trans_id' => $transient->option_id 
									) ); ?>

									<tr>
										<td>
											<a href="<?php echo esc_url( $edit_url ); ?>">
												<strong><?php echo wphave_admin_get_transient_name( $transient ); ?></strong>
												<br>
												<small style="color: #686868"><?php echo wphave_admin_get_transient_full_name( $transient ); ?></small>
											</a>
											<div class="row-actions">
												<span class="edit">
													<a href="<?php echo esc_url( $edit_url ); ?>" class="edit"><?php echo esc_html__( 'Edit', 'wphave-admin' ); ?></a> | 
												</span>
												
												<span class="trash">
													<a href="<?php echo esc_url( $delete_url ); ?>" class="trash"><?php echo esc_html__( 'Delete', 'wphave-admin' ); ?></a>
												</span>
											</div>
										</td>
										<td>
											<?php echo wphave_admin_get_transient_value( $transient ); ?>
										</td>
										<td>
											<?php echo wphave_admin_get_transient_expiration_time_readable( $transient ); ?>
										</td>
										<td>
											<?php echo $transient->option_id; ?>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr>
									<td colspan="5">
										<?php echo esc_html__( 'No transients found', 'wphave-admin' ); ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
						
					</table>			

					<div class="tablenav bottom">

						<div class="alignleft actions bulkactions">
							<form method="post" class="alignleft">
								<input type="hidden" name="action" value="wpat_delete_all_transients" />
								<input type="hidden" name="transient" value="all" />
								<?php wp_nonce_field( 'wpat_transient_manager' ); ?>
								<input type="submit" class="button button-primary" value="<?php echo esc_html__( 'Delete All Transients', 'wphave-admin' ); ?>" />
							</form>
						</div>							

						<?php if( $pages > 1 ) { ?>
							<div class="tablenav-pages">
								<span class="displaying-num"><?php printf( _n( '%d Transient', '%d Transients', $count, 'wphave-admin' ), $count ); ?></span>
								<span class="pagination-links">
									<?php 
										$pagination = str_replace(
											array("<a class='page-numbers'", 		"next page-numbers", 	"prev page-numbers"),
    										array("<a class='next-page button'", 	"next-page button", 	"next-page button"), 
											$pagination
										);
										echo $pagination; ?>
								</span>
							</div>
						<?php } ?>

					</div>
			
				<?php }

			} else {
		
				echo wphave_admin_plugin_activation_message();
		
			} ?>

		</div>

	<?php }

endif;