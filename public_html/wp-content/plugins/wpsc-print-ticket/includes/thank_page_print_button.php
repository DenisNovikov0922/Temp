<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpscfunction;

$wpsc_print_btn_lbl = get_option('wpsc_print_btn_lbl');
$wpsc_print_th_btn_setting = get_option('wpsc_print_th_btn_setting');
$wpsc_appearance_print_ticket = get_option('wpsc_appearance_print_ticket');
$action_default_btn_css = 'background-color:'.$wpsc_appearance_print_ticket['wpsc_print_ticket_btn_bg_color'].' !important;color:'.$wpsc_appearance_print_ticket['wpsc_print_ticket_btn_text_color'].' !important;';

$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');

$print_url = site_url('/').'?wpsc_action=print_ticket&ticket_post='.$ticket_id.'&auth_code='.$ticket_auth_code;

if($wpsc_print_th_btn_setting) {
  $thankyou_html.= '<a href="'.$print_url.'" target="_blank" ><button class="btn btn-sm wpsc_action_btn" style="left:40%;position:relative;'.$action_default_btn_css.'; type="submit"><i class="fa fa-print"></i> '.__($wpsc_print_btn_lbl,'supportcandy').'</button></a>';  
}