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
		return [ 'lima-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Gallery', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'gallery_style',
			[
				'label'   => __( 'Gallery Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hero-slider',
				'options' => [
					'hero-slider'   => esc_html__( 'Hero Slider', 'tr-framework' ),
					'hero-carousel' => esc_html__( 'Hero Carousel', 'tr-framework' ),
					'masonry'       => esc_html__( 'Masonry Gallery', 'tr-framework' )
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

		extract( 
			shortcode_atts( 
				array(
					'heading_text'  => '',
					'gallery_style' => '',
					'gallery'       => ''
				), $this->get_settings_for_display()
			) 
		);
		
		$ids = implode( ',', wp_list_pluck( $gallery, 'id' ) );
		
		echo do_shortcode( '[gallery ids="'. $ids .'" layout="'. $gallery_style .'"]' );

	}
	
	protected function _content_template() {
		?>
		
		<div class="section page_title_section">
			<div class="container">
				<div class="page_title"><?php esc_html_e( 'Your gallery will show here with style: ', 'tr-framework' ); ?>{{{ settings.gallery_style }}}</div>
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Image_Gallery_Block() );