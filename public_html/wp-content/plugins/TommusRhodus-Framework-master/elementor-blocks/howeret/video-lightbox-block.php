<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Video_Lightbox_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-video-lightbox-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Video Embed', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'howeret-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Video Embed', 'tr-framework' ),
			]
		);

		$this->add_control(
			'video_url', [
				'label' => esc_html__( 'Youtube/Vimeo Embed/MP4 URL', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'cover_image',
			[
				'label'       	=> __( 'Cover Image', 'tr-framework' ),
				'type' 			=> 	Controls_Manager::MEDIA,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$image_alt = get_post_meta( $settings['cover_image']['id'], '_wp_attachment_image_alt', true );
		
		echo '
			<figure class="reveal_content">
				<div class="reveal">
					<a href="'. esc_url($settings['video_url']) .'" class="popup_link video_link">
						<img src="'. $settings['cover_image']['url'] .'" alt="'. esc_attr( $image_alt ) .'" class="responsive">
						<div class="play"></div>
					</a>
				</div>
			</figure>
		';
		
	}

	protected function _content_template() {
		?>

		<figure class="reveal_content">
			<div class="reveal">
				<a href="{{{ settings.video_url }}}" class="popup_link video_link">
					<img src="{{{ settings.cover_image.url }}}" alt="" class="responsive">
					<div class="play"></div>
				</a>
			</div>
		</figure>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Video_Lightbox_Block() );