<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Portfolio_Filters_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-portfolio-filters-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Portfolio Posts Filter', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	
	public function get_categories() {
		return [ 'melissa-elements' ];
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
				'label' => esc_html__( 'Portfolio Filters - NOTE, for use with the Hover, Milan & Berlin layouts only', 'tr-framework' ),
			]
		);
		
		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query, $post;

		extract( 
			shortcode_atts( 
				array(
					'posts_per_page' => '6',
					'layout'         => 'metro',
					'filter'       => 'all'
				), $this->get_settings()
			) 
		);
		
		if( is_front_page() ) { 
			$paged = ( get_query_var( 'page' ) )  ? get_query_var( 'page' )  : 1; 
		} else { 
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
		}

		get_template_part( 'inc/content-portfolio', 'filters' );

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Portfolio_Filters_Block() );