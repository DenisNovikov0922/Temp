<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Single_Testimonial_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-single-testimonial-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Single Testimonial', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-blockquote';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'layout_section', [
				'label' => __( 'Testimonial Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Testimonial Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => [
					'centered'  => esc_html__( 'Centered', 'tr-framework' ),
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Testimonial Content', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'image', [
				'label'      => __( 'Testimonial Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'testimonial', [
				'label'       => __( 'Testimonial Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'author', [
				'label'       => __( 'Author', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'role', [
				'label'       => __( 'Author Job Role', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}
		
		echo '
			<div class="text-center mb-4 mb-md-0">
				'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg mb-3 mb-md-4 round' ) ) .'
				<blockquote class="blockquote p-0 border-0 text-body pr-xl-4">'. $settings['testimonial'] .'</blockquote>
				<div class="d-flex flex-column align-items-center">
					<h6 class="mb-1">'. $settings['author'] .'</h6>
					<span>'. $settings['role'] .'</span>
				</div>
			</div>
		';
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Single_Testimonial_Block() );