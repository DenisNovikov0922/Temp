<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Image_Slider_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-slider-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Slider', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-gallery-masonry';
	}
	
	public function get_categories() {
		return [ 'howeret-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Slider', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'gallery',
			[
				'label' => __( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'show_label' => false,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		echo '<div class="slider owl-carousel" data-items="1" data-arrows="true" data-dots="false">';
		
		foreach ($settings['gallery'] as $image) {

			$image_url = wp_get_attachment_url( $image['id'] );
			$image_alt = get_post_meta( $image['id'], '_wp_attachment_image_alt', true );
			echo '<div class="item"><img src="'. esc_url( $image_url ) .'" alt="'. $image_alt .'"></div>';

		}

		echo '</div>';

	}
	
	protected function _content_template() {
		?>
		
		<div class="section page_title_section">
			<div class="container">
				<div class="page_title"><?php esc_html_e( 'Your slider will appear here.', 'tr-framework' ); ?></div>
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Image_Slider_Block() );