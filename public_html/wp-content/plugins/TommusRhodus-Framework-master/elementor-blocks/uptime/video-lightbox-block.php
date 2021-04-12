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
		return esc_html__( 'Video', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-play';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Video Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-local',
				'label_block' => true,
				'options' => [
					'inline-local'          		=> esc_html__( 'Inline Local Video', 'tr-framework' ),
					'inline-embed-vimeo'         	=> esc_html__( 'Inline Embedded Vimeo Video', 'tr-framework' ),
					'inline-embed-youtube'         	=> esc_html__( 'Inline Embedded Youtube Video', 'tr-framework' ),
					'lightbox'         				=> esc_html__( 'Lightbox Video', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'image', [
				'label'      => __( 'Poster Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'video_url', [
				'label'       => __( 'Youtube/Vimeo Video URL - If using "inline-embed" layout, enter video ID instead', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'mp4_url', [
				'label'       => __( 'Local Video .mp4 URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'webm_url', [
				'label'       => __( 'Local Video .webm URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'ogv_url', [
				'label'       => __( 'Local Video .ogv URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$image_url 		 		 = wp_get_attachment_url( $settings['image']['id'] );

		if( 'inline-local' == $settings['layout'] ) {

			echo '
				<div class="rounded o-hidden">
					<video class="plyr" poster="'. esc_url( $image_url ) .'" playsinline controls>
						<source src="'. esc_url( $settings['mp4_url'] ) .'" type="video/mp4">
						<source src="'. esc_url( $settings['webm_url'] ) .'" type="video/webm">
						<source src="'. esc_url( $settings['ogv_url'] ) .'" type="video/ogg">
					</video>
				</div>
			';	

		} elseif( 'inline-embed-vimeo' == $settings['layout'] ) {

			echo '
				<div class="rounded o-hidden">
              		<div class="plyr" data-plyr-provider="vimeo" data-plyr-embed-id="'. $settings['video_url'] .'"></div>
            	</div>
			';	

		} elseif( 'inline-embed-youtube' == $settings['layout'] ) {

			echo '
				<div class="rounded o-hidden">
              		<div class="plyr" data-plyr-provider="youtube" data-plyr-embed-id="'. $settings['video_url'] .'"></div>
            	</div>	
			';	

		} else {

			echo '
				<div class="video-poster rounded">
					<a data-fancybox href="'. $settings['video_url'] .'" class="btn btn-lg btn-primary btn-round">
						'. tommusrhodus_svg_icons_pluck( 'Play', 'icon' ) .'
					</a>
					'. wp_get_attachment_image( $settings['image']['id'], 'large', 0 ) .'
				</div>	
			';	

		}	
			
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Video_Lightbox_Block() );