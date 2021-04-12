<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('wphave_admin_server') ) :

	class wphave_admin_server {

		/*
		*  __construct
		*
		*  A dummy constructor to ensure the plugin is only initialized once
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function __construct() {
			
			/* Do nothing here */
			
		}
		
		/*
		*  initialize
		*
		*  The real constructor to initialize the plugin
		*
		*  @type	function
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		
		function initialize() {
			
			/****************
			* ACTIONS
			****************/
			
			add_action('init', array( $this, 'wphave_admin_server_init' ), 5);
			
		}

		/*
		*  init
		*
		*  This function will run after all plugins and theme functions have been included.
		*  To ensure specific functions doesn't undefined, if the plugin call it to early, we can use this init function.
		*
		*  @type	action (init)
		*  @date	06/18/19
		*  @since	2.0
		*
		*  @param	N/A
		*  @return	N/A
		*/

		function wphave_admin_server_init() {
			
			/* Nothing to do here, yet */
			
		}

		/*
		 *******************
		 * CONVERT MEMORY SIZE
		 *******************
		 *
		 *	Convert the memory size to a readable format.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function memory_size_convert( $size ) {

			$l = substr( $size, -1 );
			$ret = substr( $size, 0, -1 );

			switch ( strtoupper( $l ) ) {
				case 'P':
					$ret *= 1024;
				case 'T':
					$ret *= 1024;
				case 'G':
					$ret *= 1024;
				case 'M':
					$ret *= 1024;
				case 'K':
					$ret *= 1024;
			}

			return $ret;

		}

		/*
		 *******************
		 * SERVER / WP MEMORY LIMIT
		 *******************
		 *
		 *	Get the php memory limit of the server and WordPress.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerWPMemoryLimit() { 

			$memory_limit = (int)@ini_get( 'memory_limit' ) . ' MB' . ' (' . (int)WP_MEMORY_LIMIT . ' MB)';
			if( @ini_get( 'memory_limit' ) == '-1' ) {
				$memory_limit = '-1 / ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (' . (int)WP_MEMORY_LIMIT . ' MB)';
			} 

			if( (int)WP_MEMORY_LIMIT < (int)@ini_get('memory_limit') && WP_MEMORY_LIMIT != '-1' || (int)WP_MEMORY_LIMIT < (int)@ini_get('memory_limit') && @ini_get('memory_limit') != '-1' ) {
				$memory_limit .= ' <span class="warning"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'The WP PHP Memory Limit is less than the %s Server PHP Memory Limit', 'wphave-admin' ), (int)@ini_get('memory_limit') . ' MB' ) . '!</span>';
			}

			return $memory_limit;

		}

		/*
		 *******************
		 * PHP VERSION
		 *******************
		 *
		 *	Get the php version of the server with warning.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getPhpVersion() {

			$php_version = 'N/A';

			if( function_exists('phpversion') ) {
				$php_version = phpversion();
			}

			if( defined('PHP_VERSION') ) {
				$php_version = PHP_VERSION;
			}

			if( $php_version != 'N/A' && version_compare( $php_version, '7.3', '<' ) ) {
				$php_version = '<span class="warning"><span class="dashicons dashicons-warning"></span> ' . sprintf(__('%s - Recommend  PHP version of 7.3. See: %s', 'wphave-admin'), esc_html( $php_version ), '<a href="https://wordpress.org/about/requirements/" target="_blank" rel="noopener">' . __('WordPress Requirements', 'wphave-admin') . '</a>') . '</span>';
			}

			return $php_version;
		}

		/*
		 *******************
		 * PHP VERSION LITE
		 *******************
		 *
		 *	Get the php version of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getPhpVersionLite() {   

			$php_version = 'N/A';

			if( function_exists('phpversion') ) {
				$php_version = phpversion();
			}

			if( defined('PHP_VERSION') ) {
				$php_version = PHP_VERSION;
			}

			return $php_version;
		}

		/*
		 *******************
		 * CURL VERSION
		 *******************
		 *
		 *	Get the curl version.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getcURLVersion() {

			$curl_version = 'N/A';

			if( function_exists( 'curl_version' ) ) {
				$curl_version = curl_version();
				$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
			}

			return $curl_version; 

		}

		/*
		 *******************
		 * MYSQL VERSION
		 *******************
		 *
		 *	Get the currently installed mysql version with warning.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getMySQLVersion() {

			if( function_exists( 'mysql_get_server_info' ) ) {

				global $wpdb;

				$ver = mysql_get_server_info();
				if( $wpdb->use_mysqli ) {
					$ver = mysqli_get_server_info( $wpdb->dbh );
				}

				if( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) {
					$get_version = $wpdb->db_version();
					$mysql_version = $get_version;
					if( version_compare( $get_version, '5.6', '<') ) {
						$mysql_version = '<span class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf(__('%s - We recommend a minimum MySQL version of 5.6. See: %s', 'wphave-admin'), $get_version, '<a href="https://wordpress.org/about/requirements/" target="_blank" rel="noopener">' . __('WordPress Requirements', 'wphave-admin') . '</a>') . '</span>';
					}
				}

				return $mysql_version;

			}

			return 'N/A';

		}

		/*
		 *******************
		 * MYSQL VERSION LITE
		 *******************
		 *
		 *	Get the currently installed mysql version.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getMySQLVersionLite() {

			if( function_exists( 'mysql_get_server_info' ) ) {

				global $wpdb;

				$ver = mysql_get_server_info();
				if( $wpdb->use_mysqli ) {
					$ver = mysqli_get_server_info( $wpdb->dbh );
				}

				if( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) {
					$get_version = $wpdb->db_version();
					$mysql_version = $get_version;
				}

				return $mysql_version;

			}

			return 'N/A';

		}

		 /*
		 *******************
		 * MYSQL TABEL PREFIX
		 *******************
		 *
		 *	Get the currently used mysql table prefix.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function get_table_prefix() {

			global $wpdb;    

			$prefix = array(
				'tablePrefix' => $wpdb->prefix,
				'tableBasePrefix' => $wpdb->base_prefix,
			);

			return $prefix;    

		}

		/*
		 *******************
		 * SHELL ENABLED
		 *******************
		 *
		 *	Check if shell_exec() is enabled on this server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function isShellEnabled() {

			if( function_exists('shell_exec') && ! in_array( 'shell_exec', array_map( 'trim', explode( ', ', ini_get( 'disable_functions' ) ) ) ) ) {
				// If enabled, check if shell_exec() actually have execution power
				$returnVal = shell_exec( 'cat /proc/cpuinfo' );
				if( ! empty( $returnVal ) ) {
					return true;
				}
			}

			return false;

		}

		/*
		 *******************
		 * SERVER UPTIME
		 *******************
		 *
		 *	Get the uptime of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerUptime() {       

			$file = '/proc/uptime';

			if( file_exists( $file ) ) {
				$str = file_get_contents( $file );

				$num = floatval($str);
				$secs = fmod($num, 60); 
				$num = (int)($num / 60);
				$mins = $num % 60;      
				$num = (int)($num / 60);
				$hours = $num % 24;      
				$num = (int)($num / 24);
				$days = $num;

				$uptime = $days . ' ' . esc_html__( 'Days', 'wphave-admin' ) . ' ' . $hours . ' ' . esc_html__( 'Hours', 'wphave-admin' ) . ' ' . $mins . ' ' . esc_html__( 'Minutes', 'wphave-admin' );

				return $uptime;
			}

			return 'N/A';

		}

		/*
		 *******************
		 * WP TIMEZONE
		 *******************
		 *
		 *	Get the currently used timezone of WordPress.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function wp_timezone() {

			$timezone = get_option('timezone_string'); // Direct value

			// Create a UTC+- zone if no timezone string exists
			if( empty( $timezone ) ) {
				// Current offset
				$current_offset = get_option('gmt_offset');

				// Plus offset
				$timezone = 'UTC+' . $current_offset;

				// No offset
				if( 0 == $current_offset ) {
					$timezone = 'UTC+0';
				// Negative offset
				} elseif( $current_offset < 0 ) {
					$timezone = 'UTC' . $current_offset;
				}

				// Normalize
				$timezone = str_replace( array('.25','.5','.75'), array(':15',':30',':45'), $timezone );
			}

			return $timezone;

		}

		/*
		 *******************
		 * TOTAL SERVER RAM
		 *******************
		 *
		 *	Get the total available RAM of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerRamTotal() {

			$os = '';
			if( defined('PHP_OS') ) {
				$os = PHP_OS;
			}

			$result = 0;

			// Linux server
			if( $os == 'Linux' ) {

				$fh = fopen( '/proc/meminfo', 'r' );
				while( $line = fgets( $fh ) ) {
					$pieces = array();
					if( preg_match( '/^MemTotal:\s+(\d+)\skB$/', $line, $pieces ) ) {
						$result = $pieces[1];
						// KB to Bytes
						$result = round( $result / 1024 / 1024, 2 );
						break;
					}
				}
				fclose( $fh );

				return $result;

			}

			return 'N/A';
		}

		/*
		 *******************
		 * FREE SERVER RAM
		 *******************
		 *
		 *	Get the free available RAM of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerRamFree() {

			$os = '';
			if( defined('PHP_OS') ) {
				$os = PHP_OS;
			}

			$result = 0;

			// Linux server
			if( $os == 'Linux' ) {

				$fh = fopen( '/proc/meminfo', 'r' );
				while( $line = fgets( $fh ) ) {
					$pieces = array();
					if( preg_match( '/^MemFree:\s+(\d+)\skB$/', $line, $pieces ) ) {
						// KB to Bytes
						$result = round($pieces[1] / 1024 / 1024,2);
						break;
					}
				}
				fclose( $fh );

				return $result;

			}

			return 'N/A';
		}

		/*
		 *******************
		 * SERVER RAM INFO
		 *******************
		 *
		 *	Get a detailed information about the RAM of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerRamDetail() {

			$os = '';
			if( defined('PHP_OS') ) {
				$os = PHP_OS;
			}

			$ram_data = '';
			if( $os == 'Linux' ) {

				foreach( file( '/proc/meminfo' ) as $ri ) {
					$m[strtok( $ri, ':') ] = strtok('');
				}

				$ram_total = round( (int)$m['MemTotal'] / 1024 / 1024, 2 );
				$ram_available = round( (int)$m['MemAvailable'] / 1024 / 1024, 2 );
				$ram_free = round( (int)$m['MemFree'] / 1024 / 1024, 2 );
				$ram_buffers = round( (int)$m['Buffers'] / 1024 / 1024, 2 );
				$ram_cached = round( (int)$m['Cached'] / 1024 / 1024, 2 );

				$mem_kernel_app = round( ( 100 - ( $ram_buffers + $ram_cached + $ram_free ) / $ram_total * 100 ), 2 );
				$mem_cached = round( $ram_cached / $ram_total * 100, 2 );
				$mem_buffers = round( $ram_buffers / $ram_total * 100, 2 );

				$ram_data = array(
					'MemTotal' => $ram_total,
					'MemAvailable' => $ram_available,
					'MemFree' => $ram_free,
					'Buffers' => $ram_buffers,
					'Cached' => $ram_cached,
					'MemUsagePercentage' => round( $mem_kernel_app + $mem_buffers + $mem_cached, 2 ), // Physical Memory
				);

				return $ram_data;
			}

			return 'N/A';
		}

		/*
		 *******************
		 * MEMORY USAGE
		 *******************
		 *
		 *	Get the memory usage of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getRealMemoryUsage() {

			$real_memory_usage = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage(true) ) : 0;
			return $real_memory_usage;

		}

		/*
		 *******************
		 * WP MEMORY USAGE
		 *******************
		 *
		 *	Get the memory usage of WordPress (relative to the defined WP_MEMORY_LIMIT).
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function wp_memory_usage() {

			// Get WP Memory Limit
			$get_memory_limit = WP_MEMORY_LIMIT;
			if( (int)WP_MEMORY_LIMIT > (int)@ini_get( 'memory_limit' ) ) {   
				// WP Limit can't be greater than Server Limiit
				$get_memory_limit = @ini_get( 'memory_limit' );
			}

			$memory_limit_convert = wphave_admin_server::memory_size_convert( $get_memory_limit );
			$memory_limit_format = size_format( $memory_limit_convert );
			$memory_limit = $memory_limit_convert;

			// Get Real Memory Usage
			$get_memory_usage = wphave_admin_server::getRealMemoryUsage();
			$memory_usage_convert = round( $get_memory_usage / 1024 / 1024 );
			$memory_usage_format = $memory_usage_convert . ' MB';
			$memory_usage = $get_memory_usage;

			if( $get_memory_usage != false && $get_memory_limit != false ) {

				// check memory limit is a numeric value
				if( ! is_numeric( $memory_limit ) ) $memory_limit = 999;

				$wp_mem_data = array(
					'MemLimit' => $memory_limit,
					'MemLimitGet' => $get_memory_limit,
					'MemLimitConvert' => $memory_limit_convert,
					'MemLimitFormat' => $memory_limit_format,
					'MemUsage' => $memory_usage,
					'MemUsageGet' => $get_memory_usage,
					'MemUsageConvert' => $memory_usage_convert,
					'MemUsageFormat' => $memory_usage_format,
					'MemUsageCalc' => round( $memory_usage / $memory_limit * 100, 0 ),
				);

				return $wp_mem_data;

			}

			return 'N/A';
		}

		/*
		 *******************
		 * SERVER MEMORY USAGE
		 *******************
		 *
		 *	Get the memory usage of the server.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function server_memory_usage() {

			// Get Server Memory Limit
			$get_memory_limit = @ini_get( 'memory_limit' );
			$memory_limit_convert = wphave_admin_server::memory_size_convert( $get_memory_limit );
			$memory_limit_format = size_format( $memory_limit_convert );
			$memory_limit = $memory_limit_convert;

			// Get Real Memory Usage
			$get_memory_usage = wphave_admin_server::getRealMemoryUsage();
			$memory_usage_convert = round( $get_memory_usage / 1024 / 1024 );
			$memory_usage_format = $memory_usage_convert . ' MB';
			$memory_usage = $get_memory_usage;        

			if( $get_memory_usage != false && $get_memory_limit != false ) {

				// check memory limit is a numeric value
				if( ! is_numeric( $memory_limit ) ) $memory_limit = 999;

				$php_mem_data = array(
					'MemLimit' => $memory_limit,
					'MemLimitGet' => $get_memory_limit,
					'MemLimitConvert' => $memory_limit_convert,
					'MemLimitFormat' => $memory_limit_format,
					'MemUsage' => $memory_usage,
					'MemUsageGet' => $get_memory_usage,
					'MemUsageConvert' => $memory_usage_convert,
					'MemUsageFormat' => $memory_usage_format,
					'MemUsageCalc' => round( $memory_usage / $memory_limit * 100, 0 ),
				);

				return $php_mem_data;

			}

			return 'N/A';
		}

		/*
		 *******************
		 * HARDDISK
		 *******************
		 *
		 *	Get information about the server harddisk.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerDiskSize( $path = '/' ) {

			$os = '';
			if( defined('PHP_OS') ) {
				$os = PHP_OS;
			}

			// Linux server
			if( $os == 'Linux' ) {

				$result = array();
				$result['size'] = 0;
				$result['free'] = 0;
				$result['used'] = 0;

				$lines = null;
				exec( sprintf( 'df /P %s', $path ), $lines );

				foreach( $lines as $index => $line ) {
					if( $index != 1 ) {
						continue;
					}
					$values = preg_split( '/\s{1,}/', $line );
					$result['size'] = round( $values[1] / 1024 / 1024, 2 );
					$result['free'] = round( $values[3] / 1024 / 1024, 2 );
					$result['used'] = round( $values[2] / 1024 / 1024, 2 );
					$result['usage'] = round( $result['used'] / $result['size'] * 100, 2 );
					break;
				}

				return $result;

			}

			return 'N/A';
		}

		/*
		 *******************
		 * SERVER CPU COUNT
		 *******************
		 *
		 *	Get the count of available server CPUs.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function check_cpu_count() {

			$cpu_count = get_transient( 'wpss_cpu_count' );

			if( $cpu_count === FALSE ) {
				if( $this->isShellEnabled() ) {
					$cpu_count = shell_exec('cat /proc/cpuinfo |grep "physical id" | sort | uniq | wc -l');
					set_transient( 'wphave_admin_cpu_count', $cpu_count, WEEK_IN_SECONDS );
				} else {
					$cpu_count = 'N/A';
				}
			}

			return $cpu_count;
		}

		/*
		 *******************
		 * SERVER CPU LOAD AVERAGE
		 *******************
		 *
		 *	Get the load average of the server CPU.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function cpu_load_average() {	

			$load = 'N/A';

			// Check via PHP function
			$avg = function_exists('sys_getloadavg') ? sys_getloadavg() : false;
			if( ! empty( $avg ) && is_array( $avg ) && 3 == count( $avg ) ) {
				$load = implode(', ', $avg);
			}			

			return $load;
		}

		/*
		 *******************
		 * SERVER CPU CORE COUNT
		 *******************
		 *
		 *	Get the count of available server CPU cores.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function check_core_count() {

			$cmd = "uname";

			$OS = strtolower( trim( shell_exec( $cmd ) ) );

			switch( $OS ) {
			   case('linux'):
				  $cmd = "cat /proc/cpuinfo | grep processor | wc -l";
				  break;
			   case('freebsd'):
				  $cmd = "sysctl -a | grep 'hw.ncpu' | cut -d ':' -f2";
				  break;
			   default:
				  unset( $cmd );
			}

			if( isset( $cmd ) && $cmd != '' ) {
			   $cpuCoreNo = intval( trim( shell_exec( $cmd ) ) );
			}

			return empty( $cpuCoreNo ) ? 1 : $cpuCoreNo;        
		}

		/*
		 *******************
		 * SERVER CPU LOAD
		 *******************
		 *
		 *	Get the server CPU load in percentage.
		 *
		 *  @type	function
		 *  @date	06/18/19
		 *  @since	3.0
		 *
		 *  @param	N/A
		 *  @return	N/A
		 *
		*/

		function getServerCpuLoadPercentage() {

			$result = -1;
			$lines = null;

			$os = '';
			if( defined('PHP_OS') ) {
				$os = PHP_OS;
			}

			// Linux server
			if( $os == 'Linux' ) {

				$checks = array();
				foreach( array( 0, 1 ) as $i ) {
					$cmd = '/proc/stat';
					$lines = array();
					$fh = fopen( $cmd, 'r' );
					while( $line = fgets( $fh ) ) {
						$lines[] = $line;
					}
					fclose( $fh );
					foreach( $lines as $line ) {
						$ma = array();
						if( ! preg_match( '/^cpu  (\d+) (\d+) (\d+) (\d+) (\d+) (\d+) (\d+) (\d+) (\d+) (\d+)$/', $line, $ma ) ) {
							continue;
						}
						$total = $ma[1] + $ma[2] + $ma[3] + $ma[4] + $ma[5] + $ma[6] + $ma[7] + $ma[8] + $ma[9];
						//$totalCpu = $ma[1] + $ma[2] + $ma[3];
						//$result = (100 / $total) * $totalCpu;
						$ma['total'] = $total;
						$checks[] = $ma;
						break;
					}
					if( $i == 0 ) {
						// Wait before checking again.
						sleep(1);
					}
				}
				// Idle - prev idle
				$diffIdle = $checks[1][4] - $checks[0][4];
				// Total - prev total
				$diffTotal = $checks[1]['total'] - $checks[0]['total'];
				// Usage in %
				$diffUsage = round( ( 1000 * ( $diffTotal - $diffIdle ) / $diffTotal + 5 ) / 10, 2 );
				$result = $diffUsage;

				return (float) $result;

			}

			return 'N/A';
		}

	} // end class

	/*
	*  wphave admin server
	*
	*  The main function responsible for returning the one true wphave Instance to functions everywhere.
	*  Use this function like you would a global variable, except without needing to declare the global.
	*
	*  Example: <?php wphave_admin_server = wphave_admin_server(); ?>
	*
	*  @type	function
	*  @date	06/18/19
	*  @since	2.0
	*
	*  @param	N/A
	*  @return	N/A
	*/

	if ( ! function_exists( 'wphave_admin_server' ) ) :

		function wphave_admin_server() {

			// Globals
			global $wphave_admin_server;

			// Initialize
			if( ! isset( $wphave_admin_server ) ) {
				$wphave_admin_server = new wphave_admin_server();
				$wphave_admin_server->initialize();
			}

			// Return
			return $wphave_admin_server;

		}

	endif;

	// Initialize
	wphave_admin_server();

endif; // END of class_exists check


/*
 *******************
 * SERVER INFO SUBPAGE
 *******************
 *
 *	Add a subpage for server info without parent item.
 *
 *  @type	action
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_server_admin_menu' ) ) :

	function wphave_admin_server_admin_menu() {
			
		add_submenu_page(
			NULL,
			esc_html__( 'Server', 'wphave-admin' ),
			esc_html__( 'Server', 'wphave-admin' ),
			'manage_options',
			'wphave-admin-server-info',
			'wphave_admin_server_page'
		);
		
	}

endif;

add_action( 'admin_menu', 'wphave_admin_server_admin_menu' );


/*
 *******************
 * OUTPUT SERVER INFO PAGE CONTENT
 *******************
 *
 *	This function outputs the content for the server info subpage.
 *
 *  @type	function
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if ( ! function_exists( 'wphave_admin_server_page' ) ) :

	function wphave_admin_server_page() {
		
		// Deny page access for sub sites	
		if( ! wphave_admin_deny_access() ) {
			return wphave_admin_no_access();
		}
		
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
				<?php echo wphave_admin_title( esc_html__( 'System Info', 'wphave-admin' ) ); ?>
			</h1> 
				
			<?php if( wphave_admin_activation_status() ) {
			
				$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'options'; 
				echo wphave_admin_tab_menu( $active_tab ); 
			
				// Output the subpage menu
				echo wphave_admin_subpage_menu(); ?>

				<h2><?php echo esc_html__( 'Overview', 'wphave-admin' ); ?></h2>

				<p><?php echo __( 'Interesting information about your web server. You can also use <a href="http://linfo.sourceforge.net/" target="_blank" rel="noopener">linfo</a> or <a href="https://phpsysinfo.github.io/phpsysinfo/" target="_blank" rel="noopener">phpsysinfo</a> to get more information about the web server', 'wphave-admin' ); ?>.</p>

				<p><?php echo __( 'In the most cases you can modify some server settings like "PHP Memory Limit" or "PHP Post Max Size" by upload and modify a <code>php.ini</code> file in the WordPress <code>/wp-admin/</code> folder. Learn more about <a href="https://www.wpbeginner.com/wp-tutorials/how-to-increase-the-maximum-file-upload-size-in-wordpress/" target="_blank" rel="noopener">here</a>', 'wphave-admin' ); ?>.</p>

				<table class="wp-list-table widefat fixed striped posts">
					<thead>
						<tr>
							<th width="25%" class="manage-column"><?php echo esc_html__( 'Info', 'wphave-admin' ); ?></th>
							<th class="manage-column"><?php echo esc_html__( 'Result', 'wphave-admin' ); ?></th>
						</tr>
					</thead>  
					<tbody>
						<tr>
							<td><?php esc_html_e( 'OS', 'wphave-admin' ); ?>:</td>
							<td><?php echo PHP_OS; ?> / <?php echo ( PHP_INT_SIZE * 8 ) . __('Bit OS', 'wphave-admin') . ' (' . php_uname() . ')'; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Software', 'wphave-admin' ); ?>:</td>
							<td><?php echo esc_html($_SERVER['SERVER_SOFTWARE']); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'IP Address', 'wphave-admin' ); ?>:</td>
							<td><?php echo esc_html($_SERVER['SERVER_ADDR']); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Web Port', 'wphave-admin' ); ?>:</td>
							<td><?php echo $_SERVER['SERVER_PORT']; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Date / Time (WP)', 'wphave-admin' ); ?>:</td>
							<td><?php echo date( 'Y-m-d H:i:s', current_time( 'timestamp', 1 ) ) . ' (' . current_time( 'mysql' ) . ')'; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Timezone (WP)', 'wphave-admin' ); ?>:</td>
							<td><?php echo date_default_timezone_get() . ' (' . $common->wp_timezone() . ')'; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Default Timezone is UTC', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $default_timezone = date_default_timezone_get();
								if( 'UTC' !== $default_timezone ) {
									echo $no . sprintf( __( 'Default timezone is %s - it should be UTC', 'wphave-admin' ), $default_timezone ) . '</span>';
								} else {
									echo $yes;
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Protocol', 'wphave-admin' ); ?>:</td>
							<td><?php echo php_uname('n'); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Administrator', 'wphave-admin' ); ?>:</td>
							<td><?php echo $_SERVER['SERVER_ADMIN']; ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'CGI Version', 'wphave-admin' ); ?>:</td>
							<td><?php echo $_SERVER['GATEWAY_INTERFACE']; ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'CPU Total', 'wphave-admin' ); ?>:</td>
							<td><?php echo $common->check_cpu_count() . ' / ' . $common->check_core_count() . ' ' . esc_html__( 'Cores', 'wphave-admin' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'CPU Usage', 'wphave-admin' ); ?>:</td>
							<td><div class="status-progressbar"><span><?php echo $common->getServerCpuLoadPercentage() . '% '; ?></span><div style="width: <?php echo $common->getServerCpuLoadPercentage(); ?>%"></div></div></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'CPU Load Average', 'wphave-admin' ); ?>:</td>
							<td><?php echo $common->cpu_load_average(); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Disk Space', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $disk_space = $common->getServerDiskSize();
								if( $disk_space != 'N/A' ) {
									echo esc_html__( 'Total', 'wphave-admin' ) . ': ' . esc_html( $disk_space['size'] ) . ' GB / ' . esc_html__( 'Free', 'wphave-admin' ) . ': ' . esc_html( $disk_space['free'] ) . ' GB / ' . esc_html__( 'Used', 'wphave-admin' ) . ': ' . esc_html( $disk_space['used'] ) . ' GB';
								} else {
									echo esc_html( $disk_space );
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Disk Space Usage', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $disk_space = $common->getServerDiskSize();
								if( $disk_space != 'N/A' ) { ?>
									<div class="status-progressbar">
										<span><?php echo esc_html( $disk_space['usage'] . '% ' ); ?></span>
										<div style="width: <?php echo $disk_space['usage']; ?>%"></div>
									</div>
									<?php echo esc_html( ' ' . $disk_space['used'] . ' GB of ' . $disk_space['size'] . ' GB' );
								} else {
									echo esc_html( $disk_space );
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Memory (RAM) Total', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $server_ram = $common->getServerRamDetail();
								if( $server_ram != 'N/A' ) {
									echo esc_html( $server_ram['MemTotal'] ) . ' GB';
								} else {
									echo esc_html( $server_ram );
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Memory (RAM) Free', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $server_ram = $common->getServerRamDetail();
								if( $server_ram != 'N/A' ) {
									echo esc_html( $server_ram['MemFree'] ) . ' GB';
								} else {
									echo esc_html( $server_ram );
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Memory (RAM) Usage', 'wphave-admin' ); ?>:</td>
							<td>
								<?php $server_ram = $common->getServerRamDetail();
								if( $server_ram != 'N/A' ) { ?>
									<div class="status-progressbar">
										<span>
											<?php echo esc_html( $server_ram['MemUsagePercentage'] . '% ' ); ?>
										</span>
										<div style="width: <?php echo $server_ram['MemUsagePercentage']; ?>%"></div>
									</div>
									<?php echo esc_html( ' ' . ( $server_ram['MemTotal'] - $server_ram['MemFree'] ) . ' GB of ' . $server_ram['MemTotal'] . ' GB' );
								} else {
									echo esc_html( $server_ram );
								} ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Memcached', 'wphave-admin' ); ?>:</td>
							<td>
								<?php if( extension_loaded( 'memcache' ) ) : 
									echo $yes;
								else :
									echo $no;
								endif; ?>
							</td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Uptime', 'wphave-admin' ); ?>:</td>
							<td><?php echo $common->getServerUptime(); ?></td>
						</tr>
						<tr class="table-border-top">
							<td><?php esc_html_e( 'PHP Version', 'wphave-admin' ); ?>:</td>
							<td><?php echo $common->getPhpVersion(); ?></td>
						</tr>
						<?php if( function_exists('ini_get') ) : ?>
							<tr>
								<td><?php esc_html_e( 'PHP Memory Limit (WP)', 'wphave-admin' ); ?>:</td>
								<td><?php echo $common->getServerWPMemoryLimit(); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Memory Usage', 'wphave-admin' ); ?>:</td>
								<td>
									<?php if( $common->server_memory_usage()['MemLimitGet'] == '-1' ) { ?>
										<?php echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . esc_html__( 'Unlimited', 'wphave-admin' ) . ' (-1)'; ?>
									<?php } else { ?>
										<div class="status-progressbar"><span><?php echo $common->server_memory_usage()['MemUsageCalc'] . '% '; ?></span><div style="width: <?php echo $common->server_memory_usage()['MemUsageCalc']; ?>%"></div></div>
										<?php echo $common->server_memory_usage()['MemUsageFormat'] . ' ' . esc_html__( 'of', 'wphave-admin' ) . ' ' . $common->server_memory_usage()['MemLimitFormat']; ?>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Max Upload Size (WP)', 'wphave-admin' ); ?>:</td>
								<td><?php echo (int)ini_get('upload_max_filesize') . ' MB (' . size_format( wp_max_upload_size() ) . ')'; ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Post Max Size', 'wphave-admin' ); ?>:</td>
								<td><?php echo size_format( $common->memory_size_convert( ini_get( 'post_max_size' ) ) ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Max Input Vars', 'wphave-admin' ); ?>:</td>
								<td><?php echo ini_get('max_input_vars'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Max Execution Time', 'wphave-admin' ); ?>:</td>
								<td><?php echo ini_get('max_execution_time') . ' ' . esc_html__( 'Seconds', 'wphave-admin' ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'PHP Extensions', 'wphave-admin' ); ?>:</td>
								<td><?php echo esc_html( implode(', ', get_loaded_extensions() ) ); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'GD Library', 'wphave-admin' ); ?>:</td>
								<td>
									<?php $gdl = gd_info(); 
									if( $gdl ) {
										echo $yes . ' / ' . esc_html__( 'Version', 'wphave-admin' ) . ': ' . $gdl['GD Version'];
									} else { 
										echo $no; 
									} ?>
								</td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'cURL Version', 'wphave-admin' ); ?>:</td>
								<td><?php echo $common->getcURLVersion(); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e( 'SUHOSIN Installed', 'wphave-admin' ); ?>:</td>
								<td><?php echo extension_loaded('suhosin') ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
							</tr>
						<?php endif; ?>
						<?php if( function_exists('ini_get') ) : ?>
							<tr>
								<td><?php esc_html_e('PHP Error Log File Location', 'wphave-admin'); ?>:</td>
								<td><?php echo ini_get('error_log'); ?></td>
							</tr>
						<?php endif; ?>

						<?php $fields = array();

						// fsockopen/cURL.
						$fields['fsockopen_curl']['name'] = 'fsockopen/cURL';

						if( function_exists('fsockopen') || function_exists('curl_init') ) {
							$fields['fsockopen_curl']['success'] = true;
						} else {
							$fields['fsockopen_curl']['success'] = false;
						}

						// SOAP.
						$fields['soap_client']['name'] = 'SoapClient';

						if( class_exists('SoapClient') ) {
							$fields['soap_client']['success'] = true;
						} else {
							$fields['soap_client']['success'] = false;
							$fields['soap_client']['note'] = sprintf(__('Your server does not have the %s class enabled - some gateway plugins which use SOAP may not work as expected.', 'bsi'), '<a href="https://php.net/manual/en/class.soapclient.php">SoapClient</a>');
						}

						// DOMDocument.
						$fields['dom_document']['name'] = 'DOMDocument';

						if( class_exists('DOMDocument') ) {
							$fields['dom_document']['success'] = true;
						} else {
							$fields['dom_document']['success'] = false;
							$fields['dom_document']['note'] = sprintf(__('Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'bsi'), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>');
						}

						// GZIP.
						$fields['gzip']['name'] = 'GZip';

						if( is_callable('gzopen') ) {
							$fields['gzip']['success'] = true;
						} else {
							$fields['gzip']['success'] = false;
							$fields['gzip']['note'] = sprintf(__('Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'bsi'), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>');
						}

						// Multibyte String.
						$fields['mbstring']['name'] = 'Multibyte String';

						if( extension_loaded('mbstring') ) {
							$fields['mbstring']['success'] = true;
						} else {
							$fields['mbstring']['success'] = false;
							$fields['mbstring']['note'] = sprintf(__('Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'bsi'), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>');
						}

						// Remote Get.
						$fields['remote_get']['name'] = 'Remote Get Status';

						$response = wp_remote_get('https://www.paypal.com/cgi-bin/webscr', array(
							'timeout' => 60,
							'user-agent' => 'BSI/' . 1.0,
							'httpversion' => '1.1',
							'body' => array(
								'cmd' => '_notify-validate'
							)
						));
						$response_code = wp_remote_retrieve_response_code($response);
						if( $response_code == 200 ) {
							$fields['remote_get']['success'] = true;
						} else {
							$fields['remote_get']['success'] = false;
						}

						foreach( $fields as $field ) {
							$mark = ! empty( $field['success'] ) ? 'yes' : 'error'; ?>
							<tr>
								<td data-export-label="<?php echo esc_html( $field['name'] ); ?>"><?php echo esc_html( $field['name'] ); ?>:</td>
								<td>
									<span class="<?php echo $mark; ?>">
										<?php echo ! empty( $field['success'] ) ? $yes : $no; ?> <?php echo ! empty( $field['note'] ) ? wp_kses_data( $field['note'] ) : ''; ?>
									</span>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
            
			<?php } else {
				echo wphave_admin_plugin_activation_message();
			} ?>
			
        </div>

    <?php }

endif;