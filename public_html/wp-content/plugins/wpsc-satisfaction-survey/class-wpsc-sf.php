<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_SF' ) ) :
  
  final class WPSC_SF {
    
		public function __construct() {
	    add_action( 'admin_enqueue_scripts', array( $this, 'loadScripts') );
	   }

	   public function loadScripts(){
	      wp_enqueue_script('jquery');
	      wp_enqueue_script('jquery-ui-core');
	      wp_enqueue_script('wpsc_sf_admin', WPSC_SF_PLUGIN_URL.'asset/js/admin.js?version='.WPSC_SF_VERSION, array('jquery'), null, true);
				wp_enqueue_style('wpsc-sf-css', WPSC_SF_PLUGIN_URL . 'asset/css/public.css?version='.WPSC_SF_VERSION );
	  } 
		
    // Add new menu WPSC settings.
    function sf_setting_pill(){
      include WPSC_SF_ABSPATH . 'includes/sf_setting_pill.php';
    }
    
    // Setting UI
    function get_sf_settings(){
      include WPSC_SF_ABSPATH . 'includes/get_sf_settings.php';
      die();
    }
		
		// Save Settings
		function save_settings(){
			include WPSC_SF_ABSPATH . 'includes/save_settings.php';
      die();
		}
    
    // Set Rating Order
    function set_rating_order(){
      include WPSC_SF_ABSPATH . 'includes/set_rating_order.php';
      die();
    }
    
    // Get add Rating
    function get_add_rating(){
      include WPSC_SF_ABSPATH . 'includes/get_add_rating.php';
      die();
    }
    
    // Set add Rating
    function set_add_rating(){
      include WPSC_SF_ABSPATH . 'includes/set_add_rating.php';
      die();
    }
    
    // Get edit Rating
    function get_edit_rating(){
      include WPSC_SF_ABSPATH . 'includes/get_edit_rating.php';
      die();
    }
    
    // Set edit Rating
    function set_edit_rating(){
      include WPSC_SF_ABSPATH . 'includes/set_edit_rating.php';
      die();
    }
    
    // Delete Rating
    function delete_rating(){
      include WPSC_SF_ABSPATH . 'includes/delete_rating.php';
      die();
    }
		
		// Shortcode
		function shortcode(){
			ob_start();
			include WPSC_SF_ABSPATH . 'includes/shortcode.php';
			return ob_get_clean();
		}
		
		// Replace macro
		function replace_macro( $str, $ticket_id ){
			include WPSC_SF_ABSPATH . 'includes/replace_macro.php';
			return $str;
		}
		
		// Set more feedback
		function set_more_feedback(){
			include WPSC_SF_ABSPATH . 'includes/set_more_feedback.php';
     		 die();
		}
		
		// Print feedback thread
		function print_feedback_thread( $thread_type, $thread ){
			include WPSC_SF_ABSPATH . 'includes/print_feedback_thread.php';
		}
		
		// Print rating widget
		function print_rating_widget( $ticket_id, $ticket_widget, $ticket_widgets ){
			global $current_user;
			
			$role_id                 = get_user_option('wpsc_agent_role');
			$wpsc_ticket_widget_type = get_term_meta( $ticket_widget->term_id, 'wpsc_ticket_widget_type', true);
			$wpsc_ticket_widget_role = get_term_meta( $ticket_widget->term_id, 'wpsc_ticket_widget_role', true);
			
			if( $ticket_widget->slug=="rating" ){
				
				if($wpsc_ticket_widget_type && (in_array($role_id,$wpsc_ticket_widget_role) || !$current_user->has_cap('wpsc_agent') && in_array('customer',$wpsc_ticket_widget_role))){
					include WPSC_SF_ABSPATH . 'includes/print_rating_widget.php';	
				}
			}
		}
		
		// Notification types
		function notification_types($notification_types){
			include WPSC_SF_ABSPATH . 'includes/notification_types.php';
			return $notification_types;
		}
		
		// Rating email notification
		function rating_notification($ticket_id){
			include WPSC_SF_ABSPATH . 'includes/rating_notification.php';
		}
		
		// Feedback email notification
		function feedback_notification($thread_id, $ticket_id){
			include WPSC_SF_ABSPATH . 'includes/feedback_notification.php';
		}
		
		// Print ticket list item
		function print_ticket_list_item($field){
			include WPSC_SF_ABSPATH . 'includes/print_ticket_list_item.php';
		}
		
		// Filter search
		function filter_search($arr,$field){
			include WPSC_SF_ABSPATH . 'includes/filter_search.php';
			return $arr;
		}
		
		// Filter autocomplete
		function filter_autocomplete($output,$term,$field_slug){
			include WPSC_SF_ABSPATH . 'includes/filter_autocomplete.php';
			return $output;
		}
		
		// Filter val label
		function filter_val_label($val,$field_slug){
			include WPSC_SF_ABSPATH . 'includes/filter_val_label.php';
			return $val;
		}
		
		// Cron
		function sf_cron(){
			include WPSC_SF_ABSPATH . 'includes/sf_cron.php';
		}
		
		// Check status
		function status_check($ticket_id,$status_id){
			include WPSC_SF_ABSPATH . 'includes/status_check.php';
		}
		
			// Add-on installed or not for licensing
		function is_add_on_installed($flag){
			return true;
		}
	  
	  // Print license functionlity for this add-on
	  function addon_license_area(){
	    include WPSC_SF_ABSPATH . 'includes/addon_license_area.php';
	  }
	  
	  // Activate Canned Reply license
	  function license_activate(){
	    include WPSC_SF_ABSPATH . 'includes/license_activate.php';
	    die();
	  }
	  
	  // Deactivate Canned Reply license
	  function license_deactivate(){
	    include WPSC_SF_ABSPATH . 'includes/license_deactivate.php';
	    die();
	  }
		
		//
		function wpsc_sf_rating_ticket_fields($export_colomn_value,$ticket_id,$value) {
			include WPSC_SF_ABSPATH . 'includes/wpsc_sf_rating_ticket_fields.php';
     		 return $export_colomn_value;
		}

		/**
		 * Add Meta Key
		 */
		function wpsc_get_all_meta_keys($meta_key){
			$meta_key[] = 'sf_rating';
			return $meta_key;
		}
		
		 function wpsc_after_individual_ticket($ticket_id){
			 include WPSC_SF_ABSPATH . 'includes/wpsc_after_individual_ticket.php';
		 }
		 /*
		 Get rating
		 */
		 function wpsc_sf_get_ratings(){
			 include WPSC_SF_ABSPATH . 'includes/wpsc_sf_get_ratings.php';
			 die();
		 }
		
		 function wpsc_add_sf_rating(){
			 include WPSC_SF_ABSPATH . 'includes/wpsc_add_sf_rating.php';
			 die();
		 }
	
		function wpsc_set_change_ticket_status($ticket_id, $status_id, $prev_status){
			include WPSC_SF_ABSPATH . 'includes/set_change_rating_ticket_status.php';
		}
	
		// add submenu in report add on
		function satisfaction_survey_graph(){
		?>
			<li id="wpsc_rp_rating_reports" role="presentation"><a href="javascript:get_ratings_report();"><?php _e('Rating','wpsc-sf');?></a></li>
			<?php
		}
			
		// display ratings report
		function get_ratings_report(){
			include WPSC_SF_ABSPATH . 'includes/tickets_ratings/get_ratings_report.php';
			die();
		}

		// ratings report by filter
		function sf_reports_bt_filter(){
			include WPSC_SF_ABSPATH . 'includes/tickets_ratings/sf_reports_bt_filter.php';
			die();
		}

		function sf_pie_chart(){
			include WPSC_SF_ABSPATH . 'includes/rating_pie_chart/sf_pie_chart.php';

		}
		
		function print_sf_checkbox(){
			$sf = get_term_by('slug','sf_rating','wpsc_ticket_custom_fields' );
			$pie_widgets     = get_option('wpsc_report_dash_widgets',array());
			$checked = in_array($sf->term_id,$pie_widgets)?'checked="checked"':'';	
			$label = get_term_meta($sf->term_id,'wpsc_tf_label',true);
			
			?>
			<div class="col-sm-4" style="margin-bottom:10px; display:flex;">
				<div style="width:25px;"><input type="checkbox" class="wpsc_reports_data" name="wpsc_report_dash_widgets[]" <?php echo $checked?> value="<?php echo $sf->term_id?>" /></div>
				<div style="padding-top:3px;"><?php echo $label?></div>
			</div>
			<?php
		}

		// email notification Settings

		function after_en_setting_pills(){?>
			<li id="wpsc_sf_ticket_notifications" role="presentation"><a href="javascript:wpsc_get_sf_email_notification_setting();"><?php _e('Feedback Notification ','wpsc-sf');?></a></li>
			<?php
		}
		
		function email_notification_settings(){
			include WPSC_SF_ABSPATH . 'includes/get_sf_email_notification.php';
      		die();
		}

		function set_sf_email_notification_settings(){
			include WPSC_SF_ABSPATH . 'includes/set_sf_email_notification.php';
			die();
		}

		function wpsc_admin_localize_script($data){
			$data['sf_rating']     = __('Rating','wpsc-sf');
			return $data;
		}

  }
  
endif;