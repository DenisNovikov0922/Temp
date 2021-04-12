<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$debug_mode = get_option('wpsc_ep_debug_mode','0');

if($debug_mode){
	echo '==> Email piping checkpoint!<br>';
}

$last_check = get_option('wpsc_ep_last_check' ,'');
$exe_time = get_option('wpsc_ep_cron_execution_time');
if(!$exe_time) $exe_time = 1;
$check_flag = false;

if($last_check){
	$now = time();
	$ago = strtotime($last_check);
	$diff = $now - $ago;
	$diff_minutes = round( $diff / 60 );
	if($diff_minutes >= $exe_time){
		$check_flag = true;
	}
}

if(!(!$last_check || $check_flag)){
	if($debug_mode){
		echo '==> Cron execution time not exceeded. Aborting email piping!<br>';
	}
	return;
}

$piping_type = get_option('wpsc_ep_piping_type');

if( $piping_type == 'gmail' ){
	include_once( WPSC_EP_ABSPATH . 'includes/import_gmail.php' );
} else {
	include_once( WPSC_EP_ABSPATH . 'includes/import_imap.php' );
}
