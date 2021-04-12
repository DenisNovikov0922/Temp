<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Page_Heading_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-page-heading-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Page Heading', 'omio' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-post';
	}
	
	public function get_categories() {
		return [ 'omio-elements' ];
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
				'label' => esc_html__( 'Page Heading', 'omio' ),
			]
		);

		$this->add_control(
			'heading_text',
			[
				'label' => esc_html__( 'Heading Text', 'omio' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'rows' => 10,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query;
		global $post;

		extract( 
			shortcode_atts( 
				array(
					'heading_text' => '',
				), $this->get_settings()
			) 
		);

		echo '
			<div class="heading">
				<div class="container text-center">
					' . $heading_text . '
				</div>
			</div>';

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Page_Heading_Block() );