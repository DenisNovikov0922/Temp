<?php 

/*
 *******************
 * POSTS COUNT DASHBOARD WIDGET
 *******************
 *
 *	Show the counts about posts on WordPress dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_count_posts_widget_content') ) :

    function wphave_admin_count_posts_widget_content() { 

        $count_posts = wp_count_posts();
        $published = $count_posts->publish;
		
		$draft = $count_posts->draft . ' ' . esc_html__( 'Draft', 'wphave-admin' );
		$pending = $count_posts->pending . ' ' . esc_html__( 'Pending', 'wphave-admin' );
		$private = $count_posts->private . ' ' . esc_html__( 'Private', 'wphave-admin' );
		$future = $count_posts->future . ' ' . esc_html__( 'Future', 'wphave-admin' ); 

		if( is_rtl() ) {
			$draft = esc_html__( 'Draft', 'wphave-admin' ) . ' ' . $count_posts->draft;
			$pending = esc_html__( 'Pending', 'wphave-admin' ) . ' ' . $count_posts->pending;
			$private = esc_html__( 'Private', 'wphave-admin' ) . ' ' . $count_posts->private;
			$future = esc_html__( 'Future', 'wphave-admin' ) . ' ' . $count_posts->future; 
		} ?>

        <style>
            .wpat-post-count {margin:0px -15px -15px -15px;text-align:center;}
            .wpat-post-count-focus {line-height:normal;color:#82878c;font-size:40px;border-bottom:1px solid #eee;padding-bottom:20px}
            .wpat-post-count-focus .wpat-post-count-num {display:inline-block}
            .wpat-post-count-focus .wpat-post-count-num ~ div {font-size:16px;font-weight:100;width:100%}
            .wpat-post-count-detail {background:#f8f9fb;padding:12px}
        </style>

        <div class="wpat-post-count">
            <div class="wpat-post-count-focus">
                <div class="wpat-post-count-num">
                    <?php echo esc_html( $published ); ?>
                </div>
                <div><?php esc_html_e( 'Published Posts', 'wphave-admin' ); ?></div>
            </div>
            <div class="wpat-post-count-detail">
                <?php echo esc_html( $draft ) . ' | ' .  esc_html( $pending ) . ' | ' .  esc_html( $private ) . ' | ' .  esc_html( $future ); ?>
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

if( ! function_exists('wphave_admin_count_posts_widget') ) :

	function wphave_admin_count_posts_widget() {

		wp_add_dashboard_widget(
			'wp_count_posts_db_widget', esc_html__( 'Posts', 'wphave-admin' ), 'wphave_admin_count_posts_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_count_posts_widget');