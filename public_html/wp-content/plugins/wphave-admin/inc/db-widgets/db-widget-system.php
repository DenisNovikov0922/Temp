<?php 

/*
 *******************
 * SYSTEM DASHBOARD WIDGET
 *******************
 *
 *	Show some information about this WordPress installation.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_system_dashboard_widget_content') ) :

    function wphave_admin_system_dashboard_widget_content() { 

        $common = new wphave_admin_server(); ?>

        <style>
            .wpat-server-info ul {margin:0 -15px -15px -15px}
            .wpat-server-info li:nth-child(even) {background:#f8f9fb}
            .wpat-server-info li {margin:0;padding:10px 15px;border-bottom:1px solid #eee}
            .wpat-server-info li:first-child {padding-top:0}
            .wpat-server-info li:last-child {border:0}
            .wpat-server-info .status-progressbar {width:100%;margin-top:5px}
        </style>

        <div class="wpat-server-info">         
            <div class="table listing">
                <ul>
                    <li>
                        <?php esc_html_e( 'WP Version', 'wphave-admin' ); ?>:
                        <strong><?php bloginfo('version'); ?></strong>
                    </li>
                    <li>
                        <?php esc_html_e('PHP Version', 'wphave-admin'); ?>:
                        <strong><?php echo $common->getPhpVersionLite(); ?></strong>
                    </li>
                    <li>
                        <?php esc_html_e( 'MySQL Version', 'wphave-admin' ); ?>:
                        <strong><?php echo $common->getMySQLVersionLite(); ?></strong>
                    </li>
                    <li>
                        <?php esc_html_e( 'PHP Memory Server-Limit', 'wphave-admin' ); ?>: 
                        <?php echo '<strong>' . $common->server_memory_usage()['MemLimitFormat'] . '</strong>'; ?> 
                    </li>
                    <li>
                        <?php esc_html_e( 'PHP Memory Server-Usage', 'wphave-admin' ); ?>: 
                        <?php if( $common->server_memory_usage()['MemLimitGet'] == '-1' ) { ?>
                            <strong><?php echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (-1)'; ?></strong>
                        <?php } else { ?>
                            <strong><?php echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . $common->server_memory_usage()['MemLimitFormat']; ?></strong>
                            <br>
                            <div class="status-progressbar"><span><?php echo $common->server_memory_usage()['MemUsageCalc'] . '% '; ?></span><div style="width: <?php echo $common->server_memory_usage()['MemUsageCalc']; ?>%"></div></div>
                        <?php } ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'PHP Memory WP-Limit', 'wphave-admin' ); ?>: 
                        <?php echo '<strong>' . $common->wp_memory_usage()['MemLimitFormat'] . '</strong>'; ?> 
                    </li>
                    <li>
                        <?php esc_html_e( 'PHP Memory WP-Usage', 'wphave-admin' ); ?>: 
                        <?php if( $common->wp_memory_usage()['MemLimitGet'] == '-1' ) { ?>
                            <strong><?php echo $common->wp_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (-1)'; ?></strong>
                        <?php } else { ?>
                            <strong><?php echo $common->wp_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . $common->wp_memory_usage()['MemLimitFormat']; ?></strong>
                            <br>
                            <div class="status-progressbar"><span><?php echo $common->wp_memory_usage()['MemUsageCalc'] . '% '; ?></span><div style="width: <?php echo $common->wp_memory_usage()['MemUsageCalc']; ?>%"></div></div>
                        <?php } ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'PHP Max Upload Size (WP)', 'wphave-admin' ); ?>: <strong><?php echo (int)ini_get('upload_max_filesize') . ' MB (' . size_format( wp_max_upload_size() ) . ')'; ?></strong>
                    </li>
                    <li>
                        <a href="<?php echo admin_url('tools.php?page=wphave-admin-server-info'); ?>"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e('Full System Information', 'wphave-admin'); ?></a>
                    </li>
                </ul>            
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

if( ! function_exists('wphave_admin_system_dashboard_widget') ) :

	function wphave_admin_system_dashboard_widget() {
        
		wp_add_dashboard_widget(
			'system_info_db_widget', esc_html__( 'System Info', 'wphave-admin' ), 'wphave_admin_system_dashboard_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_system_dashboard_widget');