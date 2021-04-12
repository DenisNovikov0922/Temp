<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Caption_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-caption-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image with Caption', 'tr-framework' );
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
				'default' => 'default',
				'options' => [
					'default'   		=> 'Default',
					'default-bottom'   	=> 'Default align Bottom',
					'dark-bg'   		=> 'Dark Background',
					'dark-bg-bottom'   	=> 'Dark Background align Bottom',
					'light'   			=> 'Light',
					'light-bottom'   	=> 'Light align Bottom',
					'coloured'   		=> 'Coloured',
					'coloured-bottom'	=> 'Coloured align Bottom',

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
			'caption', [
				'label'       => __( 'Caption', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'text_style', [
				'label'   => __( 'Text Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text-default',
				'options' => [
					'text-default'		=> 'Default',
					'text-uppercase'   	=> 'Uppercase',

				],
			]
		);

		$this->add_control(
			'text_size', [
				'label'   => __( 'Text Size', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'h3'			=> 'H3',
					'h4'			=> 'H4',
					'h5'   			=> 'H5',

				],
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

		if( 'default' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption caption-overlay mb-0">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#"><span></span>'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo '<span></span>'. wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-center mx-auto">
							<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'default-bottom' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption caption-overlay mb-0">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#"><span></span>'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo '<span></span>'. wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-end mx-auto">
							<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'dark-bg' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-center mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'dark-bg-bottom' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-end mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'light' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption light rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-center mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'light-bottom' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption light rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-end mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'coloured' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption color rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-center mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		} elseif( 'coloured-bottom' == $settings['caption_style'] ) {

			echo '
				<figure class="overlay caption color rounded">';

					if( !empty( $settings['url']['url'] ) ) {
						echo '<a href="#">'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
					} else {
						echo wp_get_attachment_image( $settings['image']['id'], 'full' );
					}
										
					echo '
					<figcaption class="d-flex">
						<div class="align-self-end mx-auto">
							<div class="caption-inner">
								<'. $settings['text_size'] .' class="'. $settings['text_style'] .' mb-0">'. $settings['caption'] .'</'. $settings['text_size'] .'>
							</div>
						</div>
					</figcaption>
				</figure>
			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Caption_Block() );