<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Conditional_Aget_Assign_Install' ) ) :
  
  final class WPSC_Conditional_Aget_Assign_Install {
    
    public function __construct() {
      add_action('init', array($this, 'register_post_type') );
      $this->check_version();
    }
    
    // Register Post type and texonomies
    public function register_post_type() {
      
      // Register Conditional Agent Assign texonomy
			$args = array(
          'public'             => false,
          'rewrite'            => false
      );
      register_taxonomy( 'wpsc_caa', 'wpsc_ticket', $args );
    }
    
    /**
    *  Check Version of WPSC
    */
    public function check_version() {
      $installed_version = get_option('wpsc_caa_current_version', 0 );
      if($installed_version!= WPSC_CAA_VERSION){
        add_action('init', array($this, 'upgrade'), 101);
      }
    }
    
    // Upgrade
    public function upgrade() {
      
      $installed_version = get_option('wpsc_caa_current_version', 0 );
      $installed_version = $installed_version ? $installed_version : 0;
      
      if($installed_version < '1.0.0') {
        
        $term = wp_insert_term('conditional_agent_assign', 'wpsc_ticket_custom_fields');
        if( !is_wp_error($term) && isset($term['term_id'])) {
          add_term_meta ($term['term_id'], 'wpsc_tf_label', __('Conditional Agent Assign','wpsc-caa'));
          add_term_meta ($term['term_id'], 'agentonly', '2');
          add_term_meta ($term['term_id'], 'wpsc_tf_type', '0');
          add_term_meta ($term['term_id'], 'wpsc_allow_ticket_list', '0');
          add_term_meta ($term['term_id'], 'wpsc_customer_ticket_list_status', '0');
          add_term_meta ($term['term_id'], 'wpsc_agent_ticket_list_status', '0');
          add_term_meta ($term['term_id'], 'wpsc_allow_ticket_filter', '0');
          add_term_meta ($term['term_id'], 'wpsc_ticket_filter_type', 'string');
          add_term_meta ($term['term_id'], 'wpsc_allow_orderby', '1');
        }
        
      }
			
			if($installed_version < '1.0.2'){
				update_option('wpsc_assign_auto_responder', '0');
			}
			
			if($installed_version < '2.0.1'){
					$agent_role = get_terms([
						'taxonomy'   => 'wpsc_caa',
						'hide_empty' => false,
						'orderby'    => 'meta_value_num',
						'order'    	 => 'ASC',
						'meta_query' => array('order_clause' => array('key' => 'load_order')),
					]);
					foreach ( $agent_role as $agent_roles ) {
							
								$conditions     = get_term_meta( $agent_roles->term_id, 'conditions', true );
								$new_conditions = array();
								if($conditions){
									foreach ( $conditions as $key => $condition ) {
										
											foreach ($condition as $value) {
													
													$new_conditions[] = array(
															'field'    => $key,
															'compare'  => 'match',
															'cond_val' => $value,
													);
													
											}
										
									}
								}
								
								$new_conditions = $new_conditions ? json_encode($new_conditions) : '';
								update_term_meta( $agent_roles->term_id ,'conditions' , $new_conditions);
							
					}	
      }
      
      if($installed_version < '2.0.4'){
        $caa = get_term_by('slug','conditional_agent_assign','wpsc_ticket_custom_fields');
        update_term_meta ($caa->term_id, 'wpsc_allow_ticket_list', '0');
      }
			
			update_option( 'wpsc_caa_current_version', WPSC_CAA_VERSION );
    }
    
  }
  
endif;  

new WPSC_Conditional_Aget_Assign_Install();