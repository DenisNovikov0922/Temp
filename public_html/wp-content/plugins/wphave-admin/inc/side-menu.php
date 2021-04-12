<?php 

/*
 *******************
 * WORDPRESS SIDE MENU
 *******************
 *
 *	Outputs a side menu about WordPress debugging and settings.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_side_menu') ) :

    function wphave_admin_side_menu() { 

        $wp = wphave_option('disable_page_wp');
		$constants = wphave_option('disable_page_constants');
		$server = wphave_option('disable_page_system');
		$error_log = wphave_option('disable_page_error_log');
		$htaccess = wphave_option('disable_page_htaccess');
		$php_ini = wphave_option('disable_page_php_ini');
		$robots_txt = wphave_option('disable_page_robots_txt');

		if( $wp && $constants && $server && $error_log && $htaccess && $php_ini && $robots_txt ) {
			return;
		} ?>

        <style>
            .wpat-overview h3 {margin:0;font-weight:600;font-size:15px}
            .wpat-overview ul {margin:0;font-size:12px}
            .wpat-overview li:nth-child(even) {background:#f8f9fb}
            .wpat-overview li {margin:0;padding:10px 15px}
            .wpat-overview li:last-child {border:0}
            .wpat-overview .status-progressbar {width:100%;margin-top:5px}
        </style>

        <div class="wpat-overview">         
            <div class="table listing">				
                <ul>					
					<li>
						<h3><?php echo esc_html__( 'Debug &amp; Settings', 'wphave-admin' ); ?></h3>
					</li>
					
					<?php echo wphave_admin_side_menu_list_items( $wp, $constants, $server, $error_log, $htaccess, $php_ini, $robots_txt ); ?>
                </ul>            
            </div>																 
        </div>

    <?php }

endif;

/*
 *******************
 * WORDPRESS SIDE MENU LIST ITEMS
 *******************
 *
 *	Outputs the side menu list items.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_side_menu_list_items') ) :

    function wphave_admin_side_menu_list_items( $wp, $constants, $server, $error_log, $htaccess, $php_ini, $robots_txt ) { ?>

		<?php

		/****************
		* WORDPRESS
		****************/

		if( ! $wp ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-wp&tab=options' ); ?>">
					<?php echo esc_html__( 'WordPress', 'wphave-admin' ); ?> <?php echo esc_html__( 'Information', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* CONSTANTS
		****************/

		if( ! $constants ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-constants&tab=options' ); ?>">
					<?php echo esc_html__( 'Constants', 'wphave-admin' ); ?> <?php echo esc_html__( 'Overview', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* SERVER
		****************/

		if( ! $server ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-server-info&tab=options' ); ?>">
					<?php echo esc_html__( 'Server', 'wphave-admin' ); ?> <?php echo esc_html__( 'Information', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* HTACCESS
		****************/

		if( ! $htaccess ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-htaccess&tab=options' ); ?>">
					<?php echo esc_html__( '.htaccess', 'wphave-admin' ); ?> <?php echo esc_html__( 'File', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* PHP INI
		****************/

		if( ! $php_ini ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-php-ini&tab=options' ); ?>">
					<?php echo esc_html__( 'php.ini', 'wphave-admin' ); ?> <?php echo esc_html__( 'File', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* ROBOTS TXT
		****************/

		if( ! $robots_txt ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-robots-txt&tab=options' ); ?>">
					<?php echo esc_html__( 'robots.txt', 'wphave-admin' ); ?> <?php echo esc_html__( 'File', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }

		/****************
		* ERROR LOG
		****************/

		if( ! $error_log ) { ?>
			<li>
				<a href="<?php echo admin_url( 'tools.php?page=wphave-admin-error-log&tab=options' ); ?>">
					PHP <?php echo esc_html__( 'Error Log', 'wphave-admin' ); ?>
				</a>
			</li>
		<?php }
	
	}

endif;