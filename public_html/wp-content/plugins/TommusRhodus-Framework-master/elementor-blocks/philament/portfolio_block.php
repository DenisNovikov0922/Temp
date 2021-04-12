<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Portfolio_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-portfolio-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Portfolio Posts', 'philament' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-post';
	}
	
	public function get_categories() {
		return [ 'philament-elements' ];
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
			'section_my_custom', [
				'label' => esc_html__( 'Portfolio Posts', 'philament' ),
			]
		);

		$this->add_control(
			'posts_per_page', [
				'label'   => esc_html__( 'Number of Posts', 'philament' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6'
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => esc_html__( 'Portfolio Layout', 'philament' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'brave',
				'options' => tommusrhodus_get_portfolio_layouts(),
			]
		);

		// Category Selector
		if( taxonomy_exists('portfolio_category') ){
		
			$portfolio_args = array(
				'orderby'      => 'name',
				'hide_empty'   => 0,
				'hierarchical' => 1,
				'taxonomy'     => 'portfolio_category'
			);
			
			$portfolio_cats       = get_categories( $portfolio_args );
			$final_portfolio_cats = array( 'all' => 'Show all categories' );
		
			if( is_array( $portfolio_cats ) ){
				foreach( $portfolio_cats as $cat ){
					$final_portfolio_cats[$cat->slug] = $cat->name;
				}
			}
		
			$this->add_control(
				'filter', [
					'label'   => esc_html__( 'Portfolio Category', 'tr-framework' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'all',
					'options' => $final_portfolio_cats,
				]
			);
		
		}	
	
		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query, $post, $paged;

		extract( 
			shortcode_atts( 
				array(
					'posts_per_page' => '6',
					'layout'         => 'brave',
					'filter'		 => 'all'
				), $this->get_settings()
			) 
		);
		
		if( is_front_page() ) { 
			$paged = ( get_query_var( 'page' ) )  ? get_query_var( 'page' )  : 1; 
		} else { 
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
		}

		/**
		 * Setup post query
		 */
		$query_args = array(
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged
		);

		if(!( $filter == 'all' )) {
			
			// Check for WPML
			if( has_filter('wpml_object_id') ){
			
				global $sitepress;
				
				// WPML recommended, remove filter, then add back after
				remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
				$filterClass    = get_term_by( 'slug', $filter, 'portfolio_category' );
				$ID             = (int) apply_filters( 'wpml_object_id', (int) $filterClass->term_id, 'portfolio_category', true );
				$translatedSlug = get_term_by( 'id', $ID, 'portfolio_category' );
				$filter         = $translatedSlug->slug;
				
				// Adding filter back
				add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
			}
				
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_category',
					'field'    => 'slug',
					'terms'    => $filter
				)
			);	
			
		}
	
		$old_query = $wp_query;
		$old_post  = $post;
		$wp_query  = new \WP_Query( $query_args );

		get_template_part( 'loop/loop-portfolio', $layout );

		wp_reset_postdata();
		$wp_query = $old_query;
		$post     = $old_post;

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Portfolio_Block() );