<?php 

/*
 *******************
 * MEMORY DASHBOARD WIDGET
 *******************
 *
 *	Show some information about the WordPress memory usage.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_memory_widget_content') ) :

    function wphave_admin_memory_widget_content() { 

        $wp_mem_limit = (int)WP_MEMORY_LIMIT; // WP PHP MEMORY LIMIT
        $wp_max_mem_limit = (int)WP_MAX_MEMORY_LIMIT; // WP MAX PHP MEMORY LIMIT
        $server_mem_limit = (int)@ini_get('memory_limit'); // SERVER PHP MEMORY LIMIT
        
        // Define php memory limit
		$memory_limit = $wp_mem_limit;
        if( $wp_mem_limit > $server_mem_limit ) {
            // WP Limit can't be greater than Server Limit
            $memory_limit = $server_mem_limit;
        }
        
        // get real php memory usage
        $memory_usage = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage(true) / 1024 / 1024 ) : 0;
        
        // calculate php memory percentage
        $memory_percent = round( $memory_usage / $memory_limit * 100, 0 );
        
        // calculate php memory usage
        $get_wp_mem_usage = $wp_mem_limit / $server_mem_limit * 100;
		$get_wp_max_mem_usage = $wp_max_mem_limit / $server_mem_limit * 100;
        if( $wp_max_mem_limit > $server_mem_limit ) {
            $get_wp_max_mem_usage = $wp_max_mem_limit / $wp_max_mem_limit * 100;
        }
        $get_server_mem_usage = $server_mem_limit / $server_mem_limit * 100;
        
        // php memory limit status color
        if ( $memory_percent <= 65 ) $memory_status = '#20bf6b';
        if ( $memory_percent > 65 ) $memory_status = '#f7b731';
        if ( $memory_percent > 85 ) $memory_status = '#eb3b5a';
        
        // check if php memory limit is unlimited
		$mem_warning = false;
        if( $server_mem_limit == -1 ) {
            $memory_percent = 100;
            $memory_status = '#2bcbba';
            $mem_warning = true;
        } ?>

        <style>
            .wpat-mem-focus {position:relative;height:40px;margin-bottom:20px;box-shadow:inset 0px 0px 30px rgba(69, 101, 173, 0.1)}
            .wpat-mem {position:relative;height:10px;margin-bottom:8px;box-shadow:inset 0px 0px 30px rgba(69, 101, 173, 0.1);overflow:hidden}
            .wpat-mem-status-count {position:relative;display:table;width:100%;height:100%;text-align:right;overflow:hidden}
            .wpat-mem-status-count span {position:relative;z-index:1;display:table-cell;vertical-align:middle;font-weight:600;padding:0px 20px}
            .wpat-mem-status {position:absolute;top:0px;bottom:0px;left:0px;background:#ccc}
            .wpat-mem-status.wp-mem-php-limit {background:#26de81}
            .wpat-mem-status.wp-max-php-mem-limit {background:#2bcbba}
            .wpat-mem-status.server-php-mem-limit {background:#20bf6b}
            .wpat-memory-detail {color:#82878c;font-size:12px}
            .wpat-memory-warning {margin-top:20px;background:#fdfaf1;padding:12px}
            .wpat-memory-warning .dashicons {color:#ffb900}
            .wpat-memory-infobox {margin-top:15px;box-shadow:inset 0px 0px 30px rgba(69, 101, 173, 0.1);padding:12px}
        </style>

        <div class="wpat-memory">
            <div class="wpat-memory-focus">
                
                <div class="wpat-mem-focus">
                    <div class="wpat-mem-status-count"<?php if( $memory_percent >= 65 ) { ?>style="color:#fff;text-align:center"<?php } ?>>
                        <?php if( $server_mem_limit == -1 ) { ?>
                            <span><?php echo esc_html( $memory_usage ) . ' MB'; ?></span>
                        <?php } else { ?>
                            <span><?php echo esc_html( $memory_percent ) . ' %'; ?> (<?php echo esc_html( $memory_usage ) . ' MB'; ?>)</span>
                        <?php } ?>
                        <div class="wpat-mem-status" style="background:<?php echo esc_html( $memory_status ); ?>;width:<?php echo esc_html( $memory_percent ); ?>%"></div>
                    </div>
                </div>
                
            </div>
            <div class="wpat-memory-detail">
                
                <?php // WP PHP Limit
        
                echo esc_html__( 'WP PHP Limit', 'wphave-admin' ) . ': ';            
                if( $wp_mem_limit != -1 ) {
                    echo esc_html( $wp_mem_limit ) . ' MB <br>'; 
                } else {
                    echo esc_html__( 'Unlimited', 'wphave-admin' ) . ' (' . esc_html( $wp_mem_limit ) . ')<br>'; 
                } ?>
                <div class="wpat-mem">
                    <div class="wpat-mem-status wp-mem-php-limit" style="width:<?php echo esc_html( $get_wp_mem_usage ); ?>%"></div>
                </div>
                
                <?php // WP PHP Limit (WP Admin only)
        
                echo esc_html__( 'WP PHP Limit (WP Admin only)', 'wphave-admin' ) . ': ';            
                if( $wp_max_mem_limit != -1 ) {
                    echo esc_html( $wp_max_mem_limit ) . ' MB <br>'; 
                } else {
                    echo esc_html__( 'Unlimited', 'wphave-admin' ) . ' (' . esc_html( $wp_max_mem_limit ) . ')<br>'; 
                } ?>
                <div class="wpat-mem">
                    <div class="wpat-mem-status wp-max-php-mem-limit" style="width:<?php echo esc_html( $get_wp_max_mem_usage ); ?>%"></div>
                </div>
                
                <?php // Server PHP Limit
        
                echo esc_html__( 'Server PHP Limit', 'wphave-admin' ) . ': ';            
                if( $server_mem_limit != -1 ) {
                    echo esc_html( $server_mem_limit ) . ' MB <br>'; 
                } else {
                    echo esc_html__( 'Unlimited', 'wphave-admin' ) . ' (' . esc_html( $server_mem_limit ) . ')<br>'; 
                } ?>
                <div class="wpat-mem">
                    <div class="wpat-mem-status server-php-mem-limit" style="width:<?php echo esc_html( $get_server_mem_usage ); ?>%"></div>
                </div>
                
                <?php // Memory Notices
        
                if( $mem_warning ) { ?>
                    <div class="wpat-memory-warning">
                        <span class="dashicons dashicons-warning"></span> <?php echo esc_html__( 'Your Server PHP Memory Limit is unlimited. A defined memory limit helps prevent poorly written scripts for eating up all available memory on a server.', 'wphave-admin' ); ?>
                    </div>
                <?php } ?>
                
                <div class="wpat-memory-infobox">
                    <?php // Check php ini value is changable
                    if ( false === wp_is_ini_value_changeable( 'memory_limit' ) ) {
                        echo '<span class="dashicons dashicons-no"></span> ' . esc_html__( 'WordPress <strong>can not</strong> change the PHP ini value at runtime.', 'wphave-admin' );
                    } else {
                        echo '<span class="dashicons dashicons-yes"></span> ' . esc_html__( 'WordPress can change the PHP ini value at runtime.', 'wphave-admin' );
                    } ?> 
                </div>
                
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

if( ! function_exists('wphave_admin_memory_widget') ) :

	function wphave_admin_memory_widget() {

		wp_add_dashboard_widget(
			'wp_memory_db_widget', esc_html__( 'WP Memory Usage', 'wphave-admin' ), 'wphave_admin_memory_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_memory_widget');