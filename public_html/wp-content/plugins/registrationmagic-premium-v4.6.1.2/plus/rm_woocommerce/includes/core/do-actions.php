<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function rm_wc_activation(){
    do_action('rm_wc_activation');
}

function rm_wc_deactivation(){
    do_action('rm_wc_deactivation');
}

/**
 * Load translations for current language
 *
 */
function rm_wc_load_textdomain() {
	do_action( 'rm_wc_load_textdomain' );
}

function rm_wc_init() {
    do_action( 'rm_wc_init' );

    //These hooks can only be applied on init hook, calling "is_user_logged_in" too early causes error.
    //Integration during checkout will work only if user is not logged in.
    if(!function_exists('is_user_logged_in'))
        require_once(ABSPATH . 'wp-includes/pluggable.php');
    if(!is_user_logged_in())
    {
        add_filter('woocommerce_checkout_fields', 'rm_wc_check_fac_during_checkout');
        add_action('woocommerce_checkout_after_customer_details', 'rm_wc_checkout_custom_fields', 10);
    }
}
