<?php 

/*
 *******************
 * SET DATE / TIME
 *******************
 *
 *	Update user last login date/time.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_set_date_time') ) :

    function wphave_admin_set_date_time() {
        
        // Update user last login date/time
        $timezone_string = get_option( 'timezone_string' );
        if ( ! empty( $timezone_string ) ) {
			date_default_timezone_set( $timezone_string ); // --> Set default timezone by wp settings
		}
        
        // Get date / time in wp format
        $date = date_i18n( get_option( 'date_format' ), strtotime( date( 'Y-m-d', current_time( 'timestamp', 1 ) ) ) );
        $time = date_i18n( get_option( 'time_format' ), strtotime( date( 'H:i:s', current_time( 'timestamp', 1 ) ) ) );
		
        return $date . ' ' . $time;
        
    }
    
endif;


/*
 *******************
 * SET USER LOGGED IN STATUS FOR LOGGED IN USERS
 *******************
 *
 *	Update user last login date/time, if the user is already logged in.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_first_login_status') ) :

    function wphave_admin_first_login_status() {
        
        // Get current logged in user ID
        $current_user = wp_get_current_user();
        
        // Check if user login status is false
        if( get_user_meta( $current_user->ID, '_logged_in', true ) ) {
            // update user login status to logged in
            update_user_meta( $current_user->ID, '_logged_in', 1 ); 
        }
        
        // Check if user last login is empty
        if( empty( get_user_meta( $current_user->ID, '_last_login', true ) ) ) {
            // Update user last login date/time
            update_user_meta( $current_user->ID, '_last_login', wphave_admin_set_date_time() ); 
        }
        
    }
    
endif;

add_action( 'admin_init', 'wphave_admin_first_login_status', 1 );


/*
 *******************
 * SET USER LOGGED IN STATUS AFTER LOG IN
 *******************
 *
 *	Update user last login date/time, after the latest login.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_store_last_user_login') ) :

    function wphave_admin_store_last_user_login( $user ) {
        
        // Get current logged in user ID
        $current_user = get_user_by('login', $user);
        
        update_user_meta( $current_user->ID, '_last_login', wphave_admin_set_date_time() ); 
        
        // Update user login status to logged in
        update_user_meta( $current_user->ID, '_logged_in', 1 ); 
    }
    
endif;

add_action('wp_login', 'wphave_admin_store_last_user_login', 10, 2);


/*
 *******************
 * SET USER LOGGED IN STATUS AFTER LOG OUT
 *******************
 *
 *	Update user last login date/time, after the latest logout.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_user_logged_in') ) :

    function wphave_admin_user_logged_in( $current_user ) {

        // Get current logged in user ID
        $current_user = wp_get_current_user();

        // Update user last logout date/time
        update_user_meta( $current_user->ID, '_last_logout', wphave_admin_set_date_time() ); 
        
        // Update user login status to logged out
        update_user_meta( $current_user->ID, '_logged_in', 0 ); 
    }

endif;

add_action('wp_logout', 'wphave_admin_user_logged_in');


/*
 *******************
 * SET USER LOGGED IN STATUS AFTER LOG OUT IF SESSION IS EXPIRED
 *******************
 *
 *	Update user stauts, after expired user login session.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

add_action('auth_cookie_expired', function( $user ) {
    
    $user = get_user_by('login', $user['username']);
    
    if( $user ) {
        
        // Update user last logout date/time
        update_user_meta( $user->ID, '_last_logout', wphave_admin_set_date_time() ); 
        
        // Update user login status to logged out
        update_user_meta( $user->ID, '_logged_in', 0 ); 
        
    }
    
}, 10, 1);


/*
 *******************
 * USER ACTIVITY LIST
 *******************
 *
 *	Output the user activity list with pagination.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_list_online_users') ) :

    function wphave_admin_list_online_users() {
        
		$user_list = array();
		
		$blog_id = '1';
		
        // Check for multisite
        if( is_multisite() ) {				
            $blog_id = get_current_blog_id();
            $options = get_blog_option( $blog_id, 'wp_admin_theme_settings_options' );
        }
		
		// Build the user count query
		$user_count_args  = array(
			'blog_id' => $blog_id,
			'number' => 999999, 
		);
		
		$user_count_query = new WP_User_Query( $user_count_args );
		$user_count = $user_count_query->get_results();

		// Count the number of users found in the query
		$total_users = isset( $user_count ) ? count( $user_count ) : 1;

		// Grab the current page number and set to 1 if no page number is set
		$page = isset( $_GET['p'] ) ? $_GET['p'] : 1;

		// Limit users to show per page
		$users_per_page = 10;

		// Calculate the total number of pages
		$total_pages = 1;
		$offset = $users_per_page * ( $page - 1 );
		$total_pages = ceil( $total_users / $users_per_page );
		
		// Build the user query
		$args  = array(
			'blog_id' => $blog_id,
			'orderby' => 'login',
			'order' => 'ASC',
			'number' => $users_per_page,
			'offset' => $offset,
		);
		
		// Create the WP_User_Query object
		$wp_user_query = new WP_User_Query($args);

		// Get the user results
		$users = $wp_user_query->get_results();
		
        foreach( $users as $current_user ) {   
            
            // check last login date/time is false
            $getLastLogin = get_user_meta( $current_user->ID, '_last_login', true );
            
			$last_user_login = $getLastLogin;
            if( empty( $getLastLogin ) ) {
                $last_user_login = esc_html__( 'N/A', 'wphave-admin' );
            }
            
            // check last logout date/time is false
            $getLastLogout = get_user_meta( $current_user->ID, '_last_logout', true );
            
			$last_user_logout = $getLastLogout;
            if( empty( $getLastLogout ) ) {
                $last_user_logout = esc_html__( 'N/A', 'wphave-admin' );
            }            
			
            // check if user login status is false 
			$is_logged_in = '0';
            if( get_user_meta( $current_user->ID, '_logged_in', true ) ) {
                // check user is logged in
                $get_user_logged_in_status = get_user_meta( $current_user->ID );
                $is_logged_in = $get_user_logged_in_status['_logged_in'][0];
            }
			
            // get logged in status
            if( $is_logged_in != '1' ) {
				$login_status = esc_html__( 'is', 'wphave-admin' ) . '<span class="user-status" style="background:#a5b1c2">' . esc_html__( 'logged out', 'wphave-admin' ) . '</span>';
				if( is_rtl() ) {
					$login_status = '<span class="user-status" style="background:#a5b1c2">' . esc_html__( 'logged out', 'wphave-admin' ) . '</span>' . esc_html__( 'is', 'wphave-admin' );
				}
            } else {
				$login_status = esc_html__( 'is', 'wphave-admin' ) . '<span class="user-status" style="background:#20bf6b">' . esc_html__( 'logged in', 'wphave-admin' ) . '</span>';
				if( is_rtl() ) {
					$login_status = '<span class="user-status" style="background:#20bf6b">' . esc_html__( 'logged in', 'wphave-admin' ) . '</span>' . esc_html__( 'is', 'wphave-admin' );
				}
            }
            
            // show logged in status
			if( is_rtl() ) {
				$user_list['users'][] = '<tr><td class="listing-img">' . get_avatar( $current_user->user_email, 64 ) . '</td><td>' . $login_status . '<strong> ' . esc_html( $current_user->display_name ) . '</strong><br><small>' . esc_html( $last_user_login ) . ': ' . esc_html__( 'Last Login', 'wphave-admin' ) . '<br>' . esc_html( $last_user_logout ) . ': ' . esc_html__( 'Last Logout', 'wphave-admin' ) . '</small></td>';
			} else {
            	$user_list['users'][] = '<tr><td class="listing-img">' . get_avatar( $current_user->user_email, 64 ) . '</td><td><strong>' . esc_html( $current_user->display_name ) . '</strong> ' . $login_status . '<br><small>' . esc_html__( 'Last Login', 'wphave-admin' ) . ': ' . esc_html( $last_user_login ) . '<br>' . esc_html__( 'Last Logout', 'wphave-admin' ) . ': ' . esc_html( $last_user_logout ) . '</small></td>';
			}
        }
		
		// Grab the current query parameters
		$query_string = $_SERVER['QUERY_STRING'];
		
		// If in the admin, your base should be the admin URL + your page
		$base = admin_url('index.php') . '?' . remove_query_arg('p', $query_string) . '%_%';
		
		$user_list['pagination'] = paginate_links( array(
			'base' => $base, // the base URL, including query arg
			'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
			'total' => $total_pages, // the total number of pages we have
			'current' => $page, // the current page
			'prev_text' => '‹',
			'next_text' => '›',
			'show_all' => false,
			'before_page_number' => '',
			'after_page_number' => '',
		));
		
		return $user_list;

    }
    
endif;
 

/*
 *******************
 * CHANGE SESSION EXPIRATION FOR TESTING
 *******************
 *
 *	Manipulate the user session.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

/*
function wphave_dev_login_session( $user_id ) {
    return 10; //--> Set login session limit in seconds
}

add_filter( 'auth_cookie_expiration', 'wphave_dev_login_session' );
*/


/*
 *******************
 * LOGGED IN USERS DASHBOARD WIDGET
 *******************
 *
 *	Show a list of logged in and logged out users on WordPress dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_logged_in_users_dashboard_widget_content') ) :

    function wphave_admin_logged_in_users_dashboard_widget_content() { ?>

        <style>
            .wp-admin-users .table {margin:-15px}
            .wp-admin-users table {width:100%;border:0px;border-collapse:collapse}
            .wp-admin-users tr:nth-child(even) {background:#f8f9fb}
            .wp-admin-users th, .wp-admin-users td {padding:15px 16px;border-bottom:1px solid #eee}
            .wp-admin-users tr:last-child th, .wp-admin-users tr:last-child td {border-bottom:0px}
            .wp-admin-users td.listing-img {padding:10px 0px 10px 16px;width:44px}
            .rtl .wp-admin-users td.listing-img {padding:10px 16px 10px 0px}
            .wp-admin-users td img {vertical-align:bottom;width:44px;height:44px;border-radius:50%}
            .wp-admin-users .user-status {padding:3px 6px;margin-left:5px;color:#fff;font-size:11px;border-radius:3px;line-height:1.1;display:inline-block}
            .rtl .wp-admin-users .user-status {margin-left:0px;margin-right:5px;display:unset}
            .wp-admin-users small {font-size:12px;color:#82878c}
			.wp-admin-users .user-pagination {background:#f8f9fb;border-top:1px solid #eee;padding:5px 10px;text-align:center}
			.wp-admin-users .user-pagination .page-numbers {padding:1px 5px}
			.wp-admin-users .user-pagination a.page-numbers:hover {background:#fff}
        </style>

        <div class="wp-admin-users">                
            <div class="table listing">                    
                <table>
                    <?php 
						$users = isset( wphave_admin_list_online_users()['users'] ) ? wphave_admin_list_online_users()['users'] : false;
						foreach( $users as $current_user ) {
							echo $current_user;
						} 
					?>                        
                </table>   
				<?php 
					$pagination = isset( wphave_admin_list_online_users()['pagination'] ) ? wphave_admin_list_online_users()['pagination'] : false;
					if( $pagination ) { ?>
						<div class="user-pagination">
							<?php echo $pagination; ?>
						</div>
					<?php }
				?>  
            </div>            
        </div>

    <?php }

endif;

/*
 *******************
 * INCLUDE DASHBOARD WIDGET
 *******************
 *
 *	Include this db widget to WordPress admin dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_logged_in_users_dashboard_widget') ) :

	function wphave_admin_logged_in_users_dashboard_widget() {
        
		wp_add_dashboard_widget(
			'logged_in_db_widget', esc_html__( 'User Activities', 'wphave-admin' ), 'wphave_admin_logged_in_users_dashboard_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_logged_in_users_dashboard_widget');