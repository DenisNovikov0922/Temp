<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Lightbox_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-lightbox-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Lightbox', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-photo-library';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
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
			'caption', [
				'label'       => __( 'Caption', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'overlay_text', [
				'label'       => __( 'Overlay Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'View Larger',
				'label_block' => true
			]
		);

		$this->add_control(
			'url', [
				'label'         => esc_html__( 'Vimeo/YouTube Video URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		$url      =  wp_get_attachment_image_src( $settings['image']['id'], 'full' );
		$target   = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow']    ? ' rel="nofollow"'  : '';
		$link     = 'href="'. esc_url( $settings['url']['url'] ) .'"' . $target . $nofollow;

		if( !empty( $settings['caption'] ) ) {
			$caption = 'data-sub-html=".caption"';
		} else {
			$caption = false;
		}

		echo '
			<div class="light-gallery item">
				<figure class="overlay overlay1 rounded mb-10">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a '.$link.'>'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo '<a href="'. esc_url( $url[0] ) .'" '. $caption .'>'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					}
										
					echo '
					<figcaption>
						<h5 class="text-uppercase from-top mb-0">'. $settings['overlay_text'] .'</h5>
					</figcaption>
				</figure>
				<div class="caption hidden">
                  '. $settings['caption'] .'
                </div>					
			</div>
		';
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Lightbox_Block() );