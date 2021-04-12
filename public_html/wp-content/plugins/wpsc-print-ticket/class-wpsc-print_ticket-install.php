<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Print_Ticket_Install' ) ) :
  
    final class WPSC_Print_Ticket_Install {
      
      public function __construct() {
        $this->check_version();
      }
      
      /**
       * Check version of WPSC
       */
      public function check_version(){
        $installed_version = get_option( 'wpsc_pt_current_version', 0 );
        if( $installed_version != WPSC_PT_VERSION ){
            add_action( 'init', array($this,'upgrade'), 101 );
        }
      }
      
      // Upgrade
  		public function upgrade(){
        
        $installed_version = get_option( 'wpsc_pt_current_version', 0 );
				$installed_version = $installed_version ? $installed_version : 0;
      
        if ( $installed_version < '1.0.0' ) {
					global $logo_src_url;
					update_option('wpsc-print-ticket_logo','wp-content/plugins/wpsc-print-ticket/asset/images/logo.png');
					$logo_src = get_option('wpsc-print-ticket_logo');
					$logo_src_url .='<img style="width:100px;" src="'. site_url('/').$logo_src.'">';
					
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
					</table>');
				
				  $wpsc_print_ticket_header = $logo_src_url .$wpsc_print_ticket_header;
					
					$wpsc_print_ticket_body = __('
					<strong>Name : </strong>{customer_name}<br>
					<strong>Email : </strong>{customer_email}<br>
					<strong>Date : </strong>{date_created}<br><br>
					<strong>Subject : </strong>{ticket_subject}<br><br>
					<strong>Description : </strong><br>
					{ticket_description}');
					
					$wpsc_print_ticket_footer_html = __('<div>I am Footer</div>');
					
          update_option('wpsc_print_th_btn_setting','1');
          update_option('wpsc_print_btn_lbl',__('Print','wpsc-pt'));
					update_option('wpsc_print_page_header_height','100');
					update_option('wpsc_print_page_footer_height','50');
					update_option('wpsc_print_ticket_header',$wpsc_print_ticket_header);
					update_option('wpsc_print_ticket_footer',$wpsc_print_ticket_footer_html);
					update_option('wpsc_print_ticket_body',$wpsc_print_ticket_body);
					
					//  Print Ticket Button
					$wpsc_appearance_print_ticket = array (
						'wpsc_print_ticket_btn_bg_color'              => '#FF5733',
						'wpsc_print_ticket_btn_text_color'            => '#000000',
					);
				  update_option('wpsc_appearance_print_ticket',$wpsc_appearance_print_ticket);
					update_option('wpsc_print_cust_btn_setting',0);
        }
				
				update_option( 'wpsc_pt_current_version', WPSC_PT_VERSION );

      }
    }
    
endif; 

new WPSC_Print_Ticket_Install();