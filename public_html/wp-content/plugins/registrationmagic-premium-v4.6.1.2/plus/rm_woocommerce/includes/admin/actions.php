<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


add_action( 'admin_menu','rm_wc_admin_menu');
add_action( 'admin_enqueue_scripts','rm_wc_admin_enqueue');
add_action( 'rm_wc_init','rm_wc_admin');
add_action('rm_filter_user_view_tab_titles', 'rmw_modify_rm_tabs');
add_action('rm_filter_user_view_tab_contents', 'rmw_modify_rm_contents',10, 2);
add_action('rm_filter_user_view_before_profile_fields', 'rmw_add_total_revenue_to_userview',10,2);
add_action( 'wp_ajax_rmwc_req', 'rmwc_handle_ajax_req' );
add_filter('rm_global_setting_manager', 'rm_wc_extended_global_setting');
add_action('rm_form_card_class', 'rm_wc_form_card_class',10, 2);

