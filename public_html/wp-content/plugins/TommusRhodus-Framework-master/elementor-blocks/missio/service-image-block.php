<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Service_Image_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-service-image-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Service Image', 'tr-framework' );
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
			'caption_style', [
				'label'   => __( 'Caption Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'polaroid',
				'options' => [
					'polaroid'   		=> 'Polaroid',
					'list-view'   		=> 'List View',
					'grid-view'   		=> 'Grid View',
					'fs-grid-view'		=> 'Fullscreen Grid View',
					'caption'   		=> 'Caption',
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

		$this->add_control(
			'title', [
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => '',
				'label_block' => true
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
			'url', [
				'label'         => esc_html__( 'URL', 'tr-framework' ),
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

		if( 'polaroid' == $settings['caption_style'] ) {

			echo '
				<div class="boxed item">
					<div class="box bg-white shadow p-30">
						<figure class="main polaroid">
							'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'
						</figure>
						'. $settings['caption'] .'
					</div>
				</div>
			';

		} elseif( 'list-view' == $settings['caption_style'] ) {

			echo '
				<div class="boxed list-view">
					<div class="item grid-sizer">
						<div class="bg-white shadow rounded">
							<div class="image-block-wrapper">
								<div class="image-block col-lg-6">
									<div class="image-block-bg bg-image" data-image-src="'. esc_url( $url[0] ) .'"></div>
								</div>
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-6 offset-lg-6">
											<div class="box d-flex">
												<div class="align-self-center">
													'. $settings['caption'] .'
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';

		} elseif( 'grid-view' == $settings['caption_style'] ) {

			echo '
				<div class="boxed grid-vew">
					<div class="item">
						<div class="box bg-white shadow p-30">
							<figure class="main mb-30">
								'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'
							</figure>
							'. $settings['caption'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'fs-grid-view' == $settings['caption_style'] ) {

			echo '
				<div class="grid-vew">
					<div class="item grid-sizer mb-0">
						<figure class="overlay caption caption-overlay mb-0">
							<a '. $link .'> 
								<spanla la-arrow-right></span>
								'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'
							</a>
							<figcaption class="d-flex">
								<div class="align-self-center mx-auto">
									'. $settings['caption'] .'
								</div>
							</figcaption>
						</figure>
					</div>
				</div>
			';

		} elseif( 'caption' == $settings['caption_style'] ) {

			echo '
				<div class="item grid-sizer mb-0">
					<figure class="overlay caption caption-overlay mb-0">
						<a '. $link .'> 
							<spanla la-arrow-right></span>
							'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'
						</a>
						<figcaption class="d-flex">
							<div class="align-self-center mx-auto">
								'. $settings['caption'] .'
							</div>
						</figcaption>
					</figure>
				</div>
			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Service_Image_Block() );