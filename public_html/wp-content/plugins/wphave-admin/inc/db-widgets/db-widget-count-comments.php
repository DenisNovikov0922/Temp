<?php 

/*
 *******************
 * COMMENTS COUNT DASHBOARD WIDGET
 *******************
 *
 *	Show the counts about comments on WordPress dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_count_comments_widget_content') ) :

    function wphave_admin_count_comments_widget_content() { 

        $count_comments = wp_count_comments();
        $all = $count_comments->all;
		
		$approved = $count_comments->approved . ' ' . esc_html__( 'Approved', 'wphave-admin' );
		$moderated = $count_comments->moderated . ' ' . esc_html__( 'Pending', 'wphave-admin' );
		$spam = $count_comments->spam . ' ' . esc_html__( 'Spam', 'wphave-admin' );
		$trash = $count_comments->trash . ' ' . esc_html__( 'Trash', 'wphave-admin' ); 
		
		if( is_rtl() ) {
			$approved = esc_html__( 'Approved', 'wphave-admin' ) . ' ' . $count_comments->approved;
			$moderated = esc_html__( 'Pending', 'wphave-admin' ) . ' ' . $count_comments->moderated;
			$spam = esc_html__( 'Spam', 'wphave-admin' ) . ' ' . $count_comments->spam;
			$trash = esc_html__( 'Trash', 'wphave-admin' ) . ' ' . $count_comments->trash; 
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
                    <?php echo esc_html( $all ); ?>
                </div>
                <div><?php esc_html_e( 'Comments', 'wphave-admin' ); ?></div>
            </div>
            <div class="wpat-post-count-detail">
                <?php echo esc_html( $approved ) . ' | ' .  esc_html( $moderated ) . ' | ' .  esc_html( $spam ) . ' | ' .  esc_html( $trash ); ?>
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

if( ! function_exists('wphave_admin_count_comments_widget') ) :

	function wphave_admin_count_comments_widget() {

		wp_add_dashboard_widget(
			'wp_count_comments_db_widget', esc_html__( 'Comments', 'wphave-admin' ), 'wphave_admin_count_comments_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_count_comments_widget');