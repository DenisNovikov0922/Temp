<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Divider_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-divider-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image with Divider', 'tr-framework' );
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
			'layout', [
				'label'   => __( 'Layout - dont forget to add the class "h-100" to the column which contains this element', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'small-image-left',
				'label_block' => true,
				'options' => [
					'divider-right'	=> esc_html__( 'Image with Divider Right', 'tr-framework' ),
					'divider-left'	=> esc_html__( 'Image with Divider Left', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'divider_colour', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primarybg-primary',
				'label_block' => true,
				'options' => [
					'bg-primary'	=> esc_html__( 'Primary Background', 'tr-framework' ),
					'bg-dark'		=> esc_html__( 'Dark Background', 'tr-framework' ),					
					'bg-secondary'	=> esc_html__( 'Secondary Background', 'tr-framework' ),		
					'bg-white'		=> esc_html__( 'White Background', 'tr-framework' ),
				],
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

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		$url      =  wp_get_attachment_image_src( $settings['image']['id'], 'full' );
		
		if( 'divider-right' == $settings['layout'] ) {

			echo '
				<div class="w-100 h-100">
					'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'w-100 h-100' ) ) .'
			        <div class="divider divider-side '. esc_attr( $settings['divider_colour'] ) .' d-none d-lg-block"></div>
     			</div>
			';

		} elseif( 'divider-left' == $settings['layout'] ) {

			echo '
				<div class="w-100 h-100">					
			        <div class="divider divider-side '. esc_attr( $settings['divider_colour'] ) .' d-none d-lg-block rotated-180"></div>
					'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'w-100 h-100' ) ) .'
     			</div>
			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Divider_Block() );