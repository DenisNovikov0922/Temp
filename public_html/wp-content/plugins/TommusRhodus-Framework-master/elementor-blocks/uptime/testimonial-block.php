<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Testimonial_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonial-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonial Posts', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-post';
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
			'section_my_custom', [
				'label' => esc_html__( 'Testimonial Posts', 'tr-framework' ),
			]
		);

		$this->add_control(
			'posts_per_page', [
				'label'   => esc_html__( 'Number of Posts', 'tr-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '3'
			]
		);

		$this->add_control(
			'layout', [
				'label'   => esc_html__( 'Posts Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => array(
					'row' => 'Row',
					'grid' => 'Grid'
				),
			]
		);

		$this->add_control(
			'posts_offset', [
				'label'   => esc_html__( 'Post Offset', 'tr-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0'
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query, $post;

		extract( 
			shortcode_atts( 
				array(
					'posts_per_page' 	=> '6',
					'posts_offset' 		=> '0',
					'filter'		 	    => 'all',
					'layout'		 	    => 'row',
					'show_pagination'	=> 'hide'
				), $this->get_settings()
			) 
		);

		/**
		 * Setup post query
		 */
		$query_args = array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page
		);
		
		if( $posts_offset ){
			$query_args[ 'offset' ] = $posts_offset;
		}
		
		$old_query = $wp_query;
		$old_post  = $post;
		$wp_query  = new \WP_Query( $query_args );

		get_template_part( 'loop/loop-testimonial', $layout );

		wp_reset_postdata();
		$wp_query = $old_query;
		$post     = $old_post;

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Testimonial_Block() );