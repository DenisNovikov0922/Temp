<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_SF_Install' ) ) :
  
  final class WPSC_SF_Install {
    
    public function __construct() {
      add_action( 'init', array($this,'register_post_type'), 100 );
      $this->check_version();
    }
    
    // Register post types and texonomies
    public function register_post_type(){
      
      // Register rating texonomy
			$args = array(
          'public'             => false,
          'rewrite'            => false
      );
      register_taxonomy( 'wpsc_sf_rating', 'wpsc_ticket', $args );
			
			// Register feedback email texonomy
			$args = array(
          'public'             => false,
          'rewrite'            => false
      );
      register_taxonomy( 'wpsc_sf_email', 'wpsc_ticket', $args );
      
    }
		
		/**
     * Check version of WPSC
     */
    public function check_version(){
      $installed_version = get_option( 'wpsc_sf_current_version', 0 );
			
			if( $installed_version != WPSC_SF_VERSION ){
          add_action( 'init', array($this,'upgrade'), 101 );
      }
		}
    
    // Upgrade
		public function upgrade(){
				
        $installed_version = get_option( 'wpsc_sf_current_version', 0 );
				$installed_version = $installed_version ? $installed_version : 0;
				
				if ( $installed_version < '1.0.0' ) {
          
					$term = wp_insert_term( 'sf_rating', 'wpsc_ticket_custom_fields' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'wpsc_tf_label', __('Rating','wpsc-sf'));
						add_term_meta ($term['term_id'], 'agentonly', '2');
						add_term_meta ($term['term_id'], 'wpsc_tf_type', '0');
						add_term_meta ($term['term_id'], 'wpsc_conditional', '1');
						add_term_meta ($term['term_id'], 'wpsc_allow_ticket_list', '1');
						add_term_meta ($term['term_id'], 'wpsc_customer_ticket_list_status', '0');
						add_term_meta ($term['term_id'], 'wpsc_agent_ticket_list_status', '0');
						add_term_meta ($term['term_id'], 'wpsc_allow_ticket_filter', '1');
						add_term_meta ($term['term_id'], 'wpsc_ticket_filter_type', 'number');
						add_term_meta ($term['term_id'], 'wpsc_customer_ticket_filter_status', '0');
						add_term_meta ($term['term_id'], 'wpsc_agent_ticket_filter_status', '1');
						add_term_meta ($term['term_id'], 'wpsc_tl_customer_load_order', '10');
						add_term_meta ($term['term_id'], 'wpsc_tl_agent_load_order', '10');
						add_term_meta ($term['term_id'], 'wpsc_filter_customer_load_order', '10');
						add_term_meta ($term['term_id'], 'wpsc_filter_agent_load_order', '10');
					}
          
          $term = wp_insert_term( __('Terrible','wpsc-sf'), 'wpsc_sf_rating' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'color', '#FF0000');
						add_term_meta ($term['term_id'], 'load_order', '1');
					}
          
          $term = wp_insert_term( __('Bad','wpsc-sf'), 'wpsc_sf_rating' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'color', '#E35213');
						add_term_meta ($term['term_id'], 'load_order', '2');
					}
          
          $term = wp_insert_term( __('Okey','wpsc-sf'), 'wpsc_sf_rating' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'color', '#969B3A');
						add_term_meta ($term['term_id'], 'load_order', '3');
					}
          
          $term = wp_insert_term( __('Good','wpsc-sf'), 'wpsc_sf_rating' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'color', '#54B42D');
						add_term_meta ($term['term_id'], 'load_order', '4');
					}
          
          $term = wp_insert_term( __('Excellent','wpsc-sf'), 'wpsc_sf_rating' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'color', '#448E26');
						add_term_meta ($term['term_id'], 'load_order', '5');
					}
					
					update_option('wpsc_sf_thankyou_text', __('Thank you for your valuable feedback!','wpsc-sf'));
					update_option('wpsc_sf_age', '3');
					update_option('wpsc_sf_age_unit', 'd');
					update_option('wpsc_sf_subject', 'Need feedback for ticket #{ticket_id}','wpsc-sf');
					update_option('wpsc_sf_email_body', '<p>Hello&nbsp;{customer_name},</p><p>We hope you are satisfied with support given for below ticket:</p><p>Ticket ID :&nbsp;#{ticket_id}<br />Ticket Subject :&nbsp;{ticket_subject}</p><p>Please take a moment to let us know your experience with our support agent. Click one of the link below and we will be immediately notified of your choice!</p><p>{satisfaction_survey_links}</p>');
          
        }
				
				if($installed_version < '1.0.6') {
					$term = wp_insert_term( 'satisfaction_survey_links', 'wpsc_ticket_custom_fields' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'wpsc_tf_label', __('Satisfaction Survey Links','wpsc-sf'));
					}
					$term = wp_insert_term( 'sf_last_feedback', 'wpsc_ticket_custom_fields' );
					if (!is_WP_error($term) && isset($term['term_id'])) {
						add_term_meta ($term['term_id'], 'wpsc_tf_label', __('Last Feedback','wpsc-sf'));
					}
				}
		if($installed_version < '2.0.4'){
			$sf_array = array();
			$dashboard_pie_chart = get_option('wpsc_report_dash_widgets',array());
			$sf = get_term_by('slug','sf_rating','wpsc_ticket_custom_fields' );
			$sf_array[] = $sf->term_id;
			$pie_charts = array_merge($sf_array, $dashboard_pie_chart);
			update_option('wpsc_report_dash_widgets',$pie_charts);
		}		
				
        update_option( 'wpsc_sf_current_version', WPSC_SF_VERSION );
        
    }
    
  }
  
endif;

new WPSC_SF_Install();