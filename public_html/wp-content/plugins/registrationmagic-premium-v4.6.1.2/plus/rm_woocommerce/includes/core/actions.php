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

$rmwc_opts = new RM_WC_Options;

add_action('rm_wc_activation','rm_wc_create_tables');
add_action( 'init','rm_wc_init',0);
add_action('woocommerce_register_form_start','rm_wc_check_fac');

add_action('woocommerce_account_dashboard', 'rm_wc_show_profile_fields');
/**
 * Init actions
 */

add_action( 'rm_wc_init', 'rm_wc_load_textdomain',0);
add_action( 'woocommerce_register_form', 'rm_wc_render_fields');
add_action( 'woocommerce_register_post', 'rm_wc_registration', 10, 3 );
add_action( 'woocommerce_created_customer','rm_wc_customer_created');
add_filter('woocommerce_registration_redirect', 'filter_woocommerce_login_redirect');
add_action('rm_wc_init','rm_wc_register_styles');
add_action('rm_wc_init','rm_wc_register_scripts');
add_action('rm_wc_notify_admin','rm_wc_notify_admin',10,4);

if($rmwc_opts->get_value_of('woo_enable_cart_in_fab') == 'yes'){
    add_filter('woocommerce_add_to_cart_fragments', 'rm_wc_extended_popup_button_menu_update');
    add_filter('rm_popup_button_menu','rm_wc_extended_popup_button_menu');
    add_filter('woocommerce_add_to_cart_fragments', 'rm_wc_extended_popup_button_content_update');
    add_filter('rm_popup_button_menu_content','rm_wc_extended_popup_button_content');
}
//Extend tabs on front end (submissions page)
add_filter('rm_after_front_tabtitle_listing','rm_wc_extended_fe_tabtitle',10,1);
add_filter('rm_after_front_tabcontent_listing','rm_wc_extended_fe_tabcontent',10,2);
add_action('rm_front_tabcontent','rm_wc_front_tabcontent',10,2);
add_action('rm_wc_submission_completed','rm_wc_submission_completed',10,3);
//add_action( 'woocommerce_checkout_update_user_meta','rm_wc_save_checkout_fields');
