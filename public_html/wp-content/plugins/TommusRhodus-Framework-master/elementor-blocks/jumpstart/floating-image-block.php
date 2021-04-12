<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Floating_Image_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-floating-image-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Floating Image', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-photo-library';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'image', [
				'label'      => __( 'Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'image-offset', [
				'label'       => __( 'Parallax Offset (eg -60)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '-60',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		echo '
			<div data-jarallax-element="'. $settings['image-offset'] .'">
            	'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid rounded shadow' ) ) .'
            </div>
		';
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Floating_Image_Block() );