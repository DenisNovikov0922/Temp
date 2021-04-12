<?php
   
/**
 * Actions
 *
 *
 * This file contains the actions that are used through-out.
 *
 * @see /core/filters.php
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//add_action('rm_olp_activation','rm_olp_create_tables');
add_action( 'init','rm_olp_init',0);

/**
 * Init actions
 */
add_action('rm_olp_init','rm_olp_register_styles');
add_action('rm_olp_init','rm_olp_register_scripts');

//Extend tabs on front end (submissions page)
add_filter('rm_payment_procs_options_frontend','rm_olp_add_pay_proc_fe',10,2);
add_filter('rm_process_payment','rm_olp_process_payment',10,4);