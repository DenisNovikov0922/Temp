<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $wpscfunction;

$wpsc_print_btn_lbl = get_option('wpsc_print_btn_lbl');
$general_appearance = get_option('wpsc_appearance_general_settings');
$action_default_btn_css = 'background-color:'.$general_appearance['wpsc_default_btn_action_bar_bg_color'].' !important;color:'.$general_appearance['wpsc_default_btn_action_bar_text_color'].' !important;';

$ticket_auth_code = $wpscfunction->get_ticket_fields($ticket_id,'ticket_auth_code');

$print_url = site_url('/').'?wpsc_action=print_ticket&ticket_post='.$ticket_id.'&auth_code='.$ticket_auth_code;

?>
<a href="<?php echo $print_url?>" target="_blank" type="button" id="wpsc_print_btn" style="background-color:#fff;color:#000;<?php echo $action_default_btn_css ?>; text-decoration:none;" class="btn btn-sm wpsc_action_btn"><i class="fa fa-print"></i> <?php _e($wpsc_print_btn_lbl,'supportcandy')?></a>
