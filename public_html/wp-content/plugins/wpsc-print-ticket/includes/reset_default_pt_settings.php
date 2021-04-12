<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $current_user;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}

update_option('wpsc_print_th_btn_setting','1');

update_option('wpsc_print_btn_lbl','Print');

update_option('wpsc_print_page_header_height','100');

update_option('wpsc_print_page_footer_height','50');

update_option('wpsc-print-ticket_logo','wp-content/plugins/wpsc-print-ticket/asset/images/logo.png');

$wpsc_print_ticket_header = __('
  <table id="tbl_header_info">
    <tr>
      <td><strong>Ticket ID</strong></td>
       <td><strong>:</strong></td>
      <td>#{ticket_id}</td>
    </tr>
    <tr>
      <td><strong>Category</strong></td>
      <td><strong>:</strong></td>
      <td>{ticket_category}</td>
    </tr>
    <tr>
      <td><strong>Priority</strong></td>
      <td><strong>:</strong></td>
      <td>{ticket_priority}</td>
    </tr>  
  </table>'
);
update_option('wpsc_print_ticket_header',$wpsc_print_ticket_header);

$wpsc_print_ticket_body = __('
  <strong>Name : </strong>{customer_name}<br>
  <strong>Email : </strong>{customer_email}<br>
  <strong>Date : </strong>{date_created}<br><br>
  <strong>Subject : </strong>{ticket_subject}<br><br>
  <strong>Description : </strong><br>
  {ticket_description}');
  
update_option('wpsc_print_ticket_body',$wpsc_print_ticket_body);

$wpsc_print_ticket_footer_html = __('<div>I am Footer</div>');
update_option('wpsc_print_ticket_footer',$wpsc_print_ticket_footer_html);

do_action('wpsc_set_reset_default_settings');

echo '{ "sucess_status":"1","messege":"'.__('Settings Reset.','wpsc-pt').'" }';