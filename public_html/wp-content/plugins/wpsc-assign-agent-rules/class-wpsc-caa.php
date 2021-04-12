<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Conditional_Agent_AssignBackend' ) ) :

   final class WPSC_Conditional_Agent_AssignBackend {

     // Add new menu WPSC settings.
     function conditional_agent_assign_setting_pill(){
       include WPSC_CAA_ABSPATH . 'includes/conditional_agent_assign_setting_pill.php';
     }

		 // Add Agnets.
     function get_caa_settings(){
	      include WPSC_CAA_ABSPATH . 'includes/get_caa_settings.php';
				die();
     }

		 // Add New Agent Roles
		 function wpsc_get_add_new_agent_rule() {
			 include WPSC_CAA_ABSPATH . 'includes/get_add_new_agent_rule.php';
			 die();
		 }

		 // Set Agent sett
     function wpsc_set_add_new_agent_rule(){
       include WPSC_CAA_ABSPATH . 'includes/set_add_new_agent_rule.php';
       die();
     }

		 // Get Edit Condition
		 function wpsc_get_edit_condition(){
			 include WPSC_CAA_ABSPATH . 'includes/get_edit_condition.php';
			 die();
		 }

		 // Set Edit Condition
		 function wpsc_set_edit_condition(){
			 include WPSC_CAA_ABSPATH . 'includes/set_edit_condition.php';
			 die();
		 }

		 // Delete Agent Condition
		 function wpsc_delete_agent_condition(){
			 include WPSC_CAA_ABSPATH . 'includes/delete_agent_condition.php';
			 die();
		 }

		 // After Create Ticket
		 function wpsc_after_create_ticket($ticket_id){
			 include WPSC_CAA_ABSPATH . 'includes/after_create_ticket.php';
		 }
		 
		 // Add-on installed or not for licensing
 		function is_add_on_installed($flag){
 			return true;
 		}
		
		// Print license functionlity for this add-on
		function addon_license_area(){
			include WPSC_CAA_ABSPATH . 'includes/addon_license_area.php';
		}
		
		// Activate Assign Agent license
		function license_activate(){
			include WPSC_CAA_ABSPATH . 'includes/license_activate.php';
      die();
		}
		
		// Deactivate Assign Agent license
		function license_deactivate(){
			include WPSC_CAA_ABSPATH . 'includes/license_deactivate.php';
      die();
		}
		
		function wpsc_set_assign_auto_responder(){
      include  WPSC_CAA_ABSPATH . 'includes/set_other_settings.php' ;
      die();
    }
		
		function wpsc_after_submit_reply($thread_id,$ticket_id){
			include  WPSC_CAA_ABSPATH . 'includes/assign_first_responder.php' ;
		}
	}
endif;

?>
