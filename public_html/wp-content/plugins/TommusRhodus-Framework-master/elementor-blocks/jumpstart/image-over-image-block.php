<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Over_Image_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-over-image-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Over Image', 'tr-framework' );
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
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'small-image-left',
				'label_block' => true,
				'options' => [
					'small-image-left'	=> esc_html__( 'Small Image Left, Main Image Right', 'tr-framework' ),
					'small-image-right'	=> esc_html__( 'Small Image Right, Main Image Left', 'tr-framework' ),
				],
			]
		);
		
		$this->add_control(
			'image', [
				'label'      => __( 'Image 1', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'image2', [
				'label'      => __( 'Image 2 (Appears Over Image 1)', 'tr-framework' ),
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
		$url2     =  wp_get_attachment_image_src( $settings['image2']['id'], 'full' );
		
		if( 'small-image-left' == $settings['layout'] ) {

			echo '
				<div data-aos="fade-in">
					'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded', 'data-aos' => 'img-fluid rounded shadow' ) ) .'
		            '. wp_get_attachment_image( $settings['image2']['id'], 'large', 0, array( 'class' => 'position-absolute p-0 col-4 col-xl-5 border border-white border-thick rounded-circle top left shadow-lg mt-5 ml-n5 ml-lg-n3 ml-xl-n5 d-none d-md-block', 'data-jarallax-element' => '-20 0' ) ) .'
	          	</div>

			';

		} elseif( 'small-image-right' == $settings['layout'] ) {

			echo '
				<div data-aos="fade-in">
					'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded', 'data-aos' => 'img-fluid rounded shadow' ) ) .'
		            '. wp_get_attachment_image( $settings['image2']['id'], 'large', 0, array( 'class' => 'position-absolute p-0 col-4 col-xl-5 border border-white border-thick rounded-circle top right shadow-lg mt-5 mr-n5 mr-lg-n3 mr-xl-n5 d-none d-md-block', 'data-jarallax-element' => '-20 0' ) ) .'
	          	</div>

			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Over_Image_Block() );