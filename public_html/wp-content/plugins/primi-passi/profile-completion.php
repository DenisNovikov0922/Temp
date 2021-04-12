<?php
/*
 * Plugin Name: Primi Passi (DSGN)
 * Description: ToDo List in Dashboard per primi passi Easy Delivery
 * Version: 1.0
 * Author: DSGN
 *
 * WC requires at least: 5.4.4
 * WC tested up to: 4.1
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include( plugin_dir_path(__FILE__) .'/woocommerce-functions-progress.php');
function load_progress_styles(){
	wp_enqueue_style( 'progress_css', plugins_url( "/css/style.css", __FILE__ ) );	
	wp_enqueue_style( 'progress_css');	
        
        wp_enqueue_script( 'progress_js', plugins_url( '/js/front-custom.js?v=1234', __FILE__ ), array() , null, true);		
	wp_enqueue_script( 'progress_js');
}
add_action('admin_enqueue_scripts', 'load_progress_styles');