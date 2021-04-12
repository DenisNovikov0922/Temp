<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Image_Link_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-link-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image & Link', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'lima-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Image Link', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'tr-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'heading', [
				'label' => esc_html__( 'Heading', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'link_text', [
				'label' => esc_html__( 'Link Text', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'button_url', [
				'label' => esc_html__( 'Block Link URL', 'tr-framework' ),
				'type' => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		extract( 
			shortcode_atts( 
				array(
					'heading'    => '',
					'image'      => '',
					'button_url' => '',
					'link_text'  => ''
				), $this->get_settings_for_display()
			) 
		);
		
		$target   = $button_url['is_external'] ? ' target="_blank"' : '';
		$nofollow = $button_url['nofollow']    ? ' rel="nofollow"'  : '';
		
		echo '
			<div class="service box_1 text_center">
			
				<div class="service_image">
					<a href="'. esc_url( $button_url['url'] ) .'">
						'. wp_get_attachment_image( $image['id'], 'large', 0, array( 'class' => 'responsive' ) ) .'
						<div class="overlay"></div>
					</a>
				</div>
				
				<div class="service_desc">
					<div class="service_title">'. $heading .'</div>
					<a href="'. esc_url( $button_url['url'] ) .'" class="btn btn_link">'. $link_text .' <i class="fa fa-arrow-right"></i></a>
				</div>
				
			</div>
		';
		
	}

	protected function _content_template() {
		?>
		
		<div class="service box_1 text_center">
		
			<div class="service_image">
				<a href="{{ settings.button_url.url }}">
					<img src="{{ settings.image.url }}" alt="" class="responsive">
					<div class="overlay"></div>
				</a>
			</div>
			
			<div class="service_desc">
				<div class="service_title">{{ settings.heading }}</div>
				<a href="{{ settings.button_url.url }}" class="btn btn_link">{{ settings.link_text }} <i class="fa fa-arrow-right"></i></a>
			</div>
			
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Image_Link_Block() );