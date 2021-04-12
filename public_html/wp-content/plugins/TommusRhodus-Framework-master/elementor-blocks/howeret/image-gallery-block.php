<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Image_Gallery_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-gallery-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Gallery', 'tr-framework' );
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
				'label' => esc_html__( 'Gallery', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label'   => __( 'Number of Columns', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '5',
				'options' => [
					'5'   => esc_html__( '5 Columns', 'tr-framework' ),
					'4'   => esc_html__( '3 Columns', 'tr-framework' ),
					'3'   => esc_html__( '3 Columns', 'tr-framework' ),
				],
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

		echo '<div class="gallery" data-cols="'. $settings['columns'] .'" data-margin="0" data-ratio="1:1">';
		
		foreach ($settings['gallery'] as $image) {
			$image_url = wp_get_attachment_url( $image['id'] );
			echo '<div class="entry" data-bg="'. esc_url( $image_url ) .'"></div>';
		}

		echo '</div>';

	}
	
	protected function _content_template() {
		?>
		
		<div class="section page_title_section">
			<div class="container">
				<div class="page_title"><?php esc_html_e( 'Your images will appear here.', 'tr-framework' ); ?></div>
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Image_Gallery_Block() );