<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Clients_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-clients-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Clients Posts', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-carousel';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}
	
	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'layout_section', [
				'label' => __( 'Clients Feed Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Clients Feed Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'small',
				'options' => array_flip( tommusrhodus_get_client_layouts() ),
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Clients Posts', 'tr-framework' ),
			]
		);

		$this->add_control(
			'posts_per_page', [
				'label'   => esc_html__( 'Number of Posts', 'tr-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4'
			]
		);
		
		// Category Selector
		if( taxonomy_exists( 'client_category' ) ){
		
			$client_args = array(
				'orderby'      => 'name',
				'hide_empty'   => 0,
				'hierarchical' => 1,
				'taxonomy'     => 'client_category'
			);
			
			$client_cats       = get_categories( $client_args );
			$final_client_cats = array( 'all' => 'Show all categories' );
		
			if( is_array( $client_cats ) ){
				foreach( $client_cats as $cat ){
					$final_client_cats[$cat->slug] = $cat->name;
				}
			}
		
			$this->add_control(
				'filter', [
					'label'   => esc_html__( 'Client Category', 'tr-framework' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'all',
					'options' => $final_client_cats,
				]
			);
		
		}
		
		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query, $post;

		$settings = $this->get_settings_for_display();
		$filter   = $settings['filter'];
		
		/**
		 * Setup post query
		 */
		$query_args = array(
			'post_type'      => 'client',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['posts_per_page']
		);
		
		if(!( $filter == 'all' )) {
			
			// Check for WPML
			if( has_filter('wpml_object_id') ){
			
				global $sitepress;
				
				// WPML recommended, remove filter, then add back after
				remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
				$filterClass    = get_term_by( 'slug', $filter, 'client_category' );
				$ID             = (int) apply_filters( 'wpml_object_id', (int) $filterClass->term_id, 'client_category', true );
				$translatedSlug = get_term_by( 'id', $ID, 'client_category' );
				$filter         = $translatedSlug->slug;
				
				// Adding filter back
				add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
			}
				
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'client_category',
					'field'    => 'slug',
					'terms'    => $filter
				)
			);	
			
		}
		
		$old_query = $wp_query;
		$old_post  = $post;
		$wp_query  = new \WP_Query( $query_args );

		get_template_part( 'loop/loop-client', $settings['layout'] );

		wp_reset_postdata();
		$wp_query = $old_query;
		$post     = $old_post;

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Clients_Block() );