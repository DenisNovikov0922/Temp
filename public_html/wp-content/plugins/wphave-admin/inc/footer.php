<?php

/*
 *******************
 * LEFT FOOTER TEXT
 *******************
 *
 *	Change the content of the left admin footer text.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_footer_text' ) ) :

	function wphave_admin_footer_text( $text ) {
		
		if( ! wphave_option('credits') ) {
			
			if( wphave_option('credits_text') ) {
				return wphave_option('credits_text');
			}
			
			$url = '<a target="_blank" rel="noopener" href="https://wphave.com">wphave</a>';			
			printf( wp_kses_post( __( 'This admin theme was designed by %1$s.', 'wphave-admin' ) ), $url );	

		}
		
		// Blank
		return;
	}	

endif;

add_filter('admin_footer_text', 'wphave_admin_footer_text');


/*
 *******************
 * RIGHT FOOTER TEXT
 *******************
 *
 *	Change the content of the right admin footer text.
 *
 *  @type	filter
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_footer' ) ) :

	function wphave_admin_footer( $text ) {

		if( ! wphave_option('memory_usage') || ! wphave_option('memory_limit') || ! wphave_option('ip_address') || ! wphave_option('php_version') || ! wphave_option('wp_version') ) {
			$text = wphave_admin_footer_data();
			return $text;
		}

		// Blank
		return;
	}

endif;

add_filter('update_footer', 'wphave_admin_footer', 11);


/*
 *******************
 * WP MEMORY USAGE (MB)
 *******************
 *
 *	Output the memory usage of the WordPress installation in megabyte.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_memory_usage' ) ) : 

	function wphave_admin_memory_usage() {

		$memory_usage = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage(true) / 1024 / 1024 ) : 0;
		
		if( $memory_usage ) {			
			return $memory_usage;
		}
		
		return 'N/A';

	}

endif;


/*
 *******************
 * WP MEMORY USAGE (PERCENTAGE)
 *******************
 *
 *	Output the percentage memory usage of the WordPress installation.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_memory_usage_percent' ) ) : 

	function wphave_admin_memory_usage_percent() {

		$memory_limit = (int)WP_MEMORY_LIMIT; 
		if( ini_get( 'memory_limit' ) == '-1' ) {
			$memory_limit = '-1';
		}

		$memory_usage = wphave_admin_memory_usage();

		if( $memory_usage && $memory_limit ) {

			$memory_percent = round( $memory_usage / $memory_limit * 100, 0 );
			if( ini_get( 'memory_limit' ) == '-1' ) {
				$memory_percent = esc_html__( 'Unlimited', 'wphave-admin' );
			}
			
			return $memory_percent;

		}
		
		return 'N/A';

	}

endif;


/*
 *******************
 * WP MEMORY USAGE (PERCENTAGE WITH UNIT)
 *******************
 *
 *	Output the percentage memory usage of the WordPress installation with the unit.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_memory_usage_percent_unit' ) ) : 

	function wphave_admin_memory_usage_percent_unit() {		

		$memory_usage = wphave_admin_memory_usage_percent();

		if( is_numeric( $memory_usage ) ) {			
			return $memory_usage . '%';
		}
		
		return $memory_usage;

	}

endif;
	

/*
 *******************
 * GET WP MEMORY LIMIT
 *******************
 *
 *	Output the memory limit of the WordPress installation.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_memory_limit' ) ) : 

	function wphave_admin_memory_limit( $size ) {

		$value = substr( $size, -1 );
		$wp_limit = substr( $size, 0, -1 );
		$wp_limit = (int)$wp_limit;

		switch ( strtoupper( $value ) ) {
			case 'P' :
				$wp_limit*= 1024;
			case 'T' :
				$wp_limit*= 1024;
			case 'G' :
				$wp_limit*= 1024;
			case 'M' :
				$wp_limit*= 1024;
			case 'K' :
				$wp_limit*= 1024;
		}

		return $wp_limit;
	}  

endif;


/*
 *******************
 * CHECK WP MEMORY LIMIT
 *******************
 *
 *	Check the memory limit of the WordPress installation.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/
	
if ( ! function_exists( 'wphave_admin_check_memory_limit' ) ) : 

	function wphave_admin_check_memory_limit() {

		$memory_limit = wphave_admin_memory_limit( WP_MEMORY_LIMIT );
		$memory_limit = size_format( $memory_limit );

		return ($memory_limit) ? $memory_limit : esc_html__( 'N/A', 'wphave-admin' );

	}

endif;
	

/*
 *******************
 * CREATE FOOTER DATA
 *******************
 *
 *	Output the data for the right admin footer text.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_footer_data' ) ) : 

	function wphave_admin_footer_data() { ?>
        
        <span class="wpat-footer-info">
        
			<?php 

			/****************
			* IP ADDRESS
			****************/

			if( ! wphave_option('ip_address') ) {

				// Get ip address
				$ip = isset( $_SERVER[ 'SERVER_ADDR' ] ) ? $_SERVER[ 'SERVER_ADDR' ] : '';
				if( ! $ip ) { 
					$ip = isset( $_SERVER[ 'LOCAL_ADDR' ] ) ? $_SERVER[ 'LOCAL_ADDR' ] : '';
				} ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo esc_html( $ip ) . ' :' . esc_html__( 'IP', 'wphave-admin' );
					} else {
						echo esc_html__( 'IP', 'wphave-admin' ) . ' ' . esc_html( $ip );
					} ?>
				</span>

			<?php }

			/****************
			* PHP VERSION
			****************/

			if( ! wphave_option('php_version') ) { ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo PHP_VERSION . ' :' . esc_html__( 'PHP', 'wphave-admin' );
					} else {
						echo esc_html__( 'PHP', 'wphave-admin' ) . ' ' . PHP_VERSION;
					} ?>
				</span>

			<?php }

			/****************
			* WP VERSION
			****************/

			if( ! wphave_option('wp_version') ) { 
			
				global $wp_version; ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo esc_html( $wp_version ) . ' :' . esc_html__( 'WP', 'wphave-admin' );
					} else {
						echo esc_html__( 'WP', 'wphave-admin' ) . ' ' . esc_html( $wp_version );
					} ?>
				</span>

			<?php } ?>

		</span>

		<br>

		<span class="wpat-footer-info">
		
			<?php 

			/****************
			* MEMORY USAGE
			****************/

			if( ! wphave_option('memory_usage') ) {

				$memory_limit = wphave_admin_check_memory_limit();
				$memory_usage = wphave_admin_memory_usage();
				$memory_usage_percent = wphave_admin_memory_usage_percent();
				$memory_usage_percent_unit = wphave_admin_memory_usage_percent_unit();

				if( $memory_usage_percent <= 65 ) {
					$memory_status = '#20bf6b';
				} elseif( $memory_usage_percent > 65 ) {
					$memory_status = '#f7b731';
				} elseif( $memory_usage_percent > 85 ) { 
					$memory_status = '#eb3b5a';
				} ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo '<span class="memory-status" style="background:' . esc_html( $memory_status ) . '"><strong>' . esc_html( $memory_usage_percent_unit ) . '</strong></span>';
						echo esc_html( $memory_limit ) . esc_html__( ' of ', 'wphave-admin' );
						echo esc_html( $memory_usage ) . ': ' . esc_html__( 'Memory Usage', 'wphave-admin' );
					} else {
						echo esc_html__( 'WP Memory Usage', 'wphave-admin' ) . ': ' . esc_html( $memory_usage );
						echo esc_html__( ' of', 'wphave-admin' ) . ' ' . esc_html( $memory_limit );
						echo '<span class="memory-status" style="background:' . esc_html( $memory_status ) . '"><strong>' . esc_html( $memory_usage_percent_unit ) . '</strong></span>';
					} ?>
				</span>

			<?php }

			/****************
			* MEMORY LIMIT
			****************/

			if( ! wphave_option('memory_limit') ) {

				$memory_limit = wphave_admin_check_memory_limit(); ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo esc_html( $memory_limit ) . ' :' . esc_html__( 'WP Memory Limit', 'wphave-admin' );
					} else {
						echo esc_html__( 'WP Memory Limit', 'wphave-admin' ) . ': ' . esc_html( $memory_limit );
					} ?>
				</span>

			<?php }

			/****************
			* MEMORY AVAIALABLE
			****************/

			if( ! wphave_option('memory_available') ) {

				$memory_available = (int)@ini_get( 'memory_limit' ); ?>

				<span class="wpat-footer-info-sep">
					<?php if( is_rtl() ) {
						echo 'MB ' . esc_html( $memory_available ) . ' :' . esc_html__( 'Memory Available', 'wphave-admin' );
					} else {
						echo esc_html__( 'Memory Available', 'wphave-admin' ) . ': ' . esc_html( $memory_available ) . ' MB';
					} ?>
				</span>

			<?php } ?>
        
        </span>

	<?php }

endif;