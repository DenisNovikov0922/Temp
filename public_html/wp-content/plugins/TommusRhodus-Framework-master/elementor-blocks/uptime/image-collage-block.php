<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Collage_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-collage-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Collage', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-photo-library';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
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
				'label'      => __( 'Image 2', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'image3', [
				'label'      => __( 'Image 3', 'tr-framework' ),
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
		$url3     =  wp_get_attachment_image_src( $settings['image3']['id'], 'full' );
		
		echo '
			<div class="image-collage">
			
				<a href="'. esc_url( $url[0] ) .'" data-fancybox="Collage Gallery" class="d-none d-lg-block">
					<div data-jarallax-element="0 12">
						'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded', 'data-aos' => 'fade-right' ) ) .'
					</div>
				</a>
				
				<a href="'. esc_url( $url2[0] ) .'" data-fancybox="Collage Gallery">
					<div>
						'. wp_get_attachment_image( $settings['image2']['id'], 'large', 0, array( 'class' => 'rounded', 'data-aos' => 'fade-up' ) ) .'
					</div>
				</a>
				
				<a href="'. esc_url( $url3[0] ) .'" data-fancybox="Collage Gallery" class="d-none d-lg-block">
					<div data-jarallax-element="0 -12">
						'. wp_get_attachment_image( $settings['image3']['id'], 'large', 0, array( 'class' => 'rounded', 'data-aos' => 'fade-left' ) ) .'
					</div>
				</a>
				
			</div>
			
			<div class="decoration bottom left scale-2">
				<svg class="bg-primary-2" width="43" height="122" viewBox="0 0 43 122" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M27.7833 0.279065C31.5153 -0.395935 34.1643 0.647081 35.8243 3.52708C37.3013 3.75308 38.6543 3.75507 39.8533 4.18507C42.1543 5.01007 42.9763 6.54608 42.4253 8.85508C41.9003 11.0481 41.2363 13.2141 40.5173 15.3541C39.3333 18.8831 38.0523 22.3801 36.8223 25.8941C36.5623 26.6401 36.3473 27.4031 36.1253 28.1161C37.0183 29.7191 37.8983 31.1721 38.6433 32.6931C38.9753 33.3751 39.2763 34.2291 39.1643 34.9391C38.7993 37.2861 38.4123 39.6531 37.7263 41.9191C34.3003 53.2441 30.6563 64.5041 27.3223 75.8571C24.0643 86.9611 21.6093 98.2541 20.5933 109.813C20.3373 112.721 20.3023 115.648 20.1133 118.563C20.0643 119.327 19.8203 120.077 19.6813 120.759C15.5443 121.634 15.1673 121.063 12.5563 118.159C10.5703 115.952 10.0543 113.611 9.89033 110.991C9.78533 109.283 9.15631 107.965 7.99531 106.752C5.98531 104.652 4.13033 102.404 2.07933 100.35C1.36233 99.6311 0.903309 98.9591 0.886309 97.9751C0.820309 94.3911 0.722321 90.8071 0.698321 87.2231C0.682321 84.7301 1.61831 82.2831 0.753314 79.7161C0.466314 78.8631 1.1893 77.7051 1.3173 76.6701C1.5593 74.6961 1.55332 72.6821 1.92232 70.7361C2.96332 65.2691 4.04931 59.8211 5.40431 54.4081C7.37531 46.5491 9.58832 38.7701 11.8923 31.0121C12.5353 28.8511 13.2773 26.7081 14.1303 24.6221C16.2923 19.3441 18.5343 14.0991 20.7713 8.85109C21.0273 8.24809 21.3243 7.49607 21.8303 7.19307C24.5723 5.55607 25.8093 2.64406 27.7833 0.279065Z" fill="black" />
				</svg>
			</div>
		';
		
	}

	protected function _content_template() {
		?>
		
		<div class="image-collage">
		
			<a href="{{ settings.image.url }}" data-fancybox="Collage Gallery" class="d-none d-lg-block">
				<div data-jarallax-element="0 12">
					<img src="{{{ settings.image.url }}}" alt="Image" class="rounded" data-aos="fade-right">
				</div>
			</a>
			
			<a href="{{ settings.image2.url }}" data-fancybox="Collage Gallery">
				<div>
					<img src="{{{ settings.image2.url }}}" alt="Image" class="rounded" data-aos="fade-up">
				</div>
			</a>
			
			<a href="{{ settings.image3.url }}" data-fancybox="Collage Gallery" class="d-none d-lg-block">
				<div data-jarallax-element="0 -12">
					<img src="{{{ settings.image3.url }}}" alt="Image" class="rounded" data-aos="fade-left">
				</div>
			</a>
			
		</div>
		
		<div class="decoration bottom left scale-2">
			<svg class="bg-primary-2" width="43" height="122" viewBox="0 0 43 122" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M27.7833 0.279065C31.5153 -0.395935 34.1643 0.647081 35.8243 3.52708C37.3013 3.75308 38.6543 3.75507 39.8533 4.18507C42.1543 5.01007 42.9763 6.54608 42.4253 8.85508C41.9003 11.0481 41.2363 13.2141 40.5173 15.3541C39.3333 18.8831 38.0523 22.3801 36.8223 25.8941C36.5623 26.6401 36.3473 27.4031 36.1253 28.1161C37.0183 29.7191 37.8983 31.1721 38.6433 32.6931C38.9753 33.3751 39.2763 34.2291 39.1643 34.9391C38.7993 37.2861 38.4123 39.6531 37.7263 41.9191C34.3003 53.2441 30.6563 64.5041 27.3223 75.8571C24.0643 86.9611 21.6093 98.2541 20.5933 109.813C20.3373 112.721 20.3023 115.648 20.1133 118.563C20.0643 119.327 19.8203 120.077 19.6813 120.759C15.5443 121.634 15.1673 121.063 12.5563 118.159C10.5703 115.952 10.0543 113.611 9.89033 110.991C9.78533 109.283 9.15631 107.965 7.99531 106.752C5.98531 104.652 4.13033 102.404 2.07933 100.35C1.36233 99.6311 0.903309 98.9591 0.886309 97.9751C0.820309 94.3911 0.722321 90.8071 0.698321 87.2231C0.682321 84.7301 1.61831 82.2831 0.753314 79.7161C0.466314 78.8631 1.1893 77.7051 1.3173 76.6701C1.5593 74.6961 1.55332 72.6821 1.92232 70.7361C2.96332 65.2691 4.04931 59.8211 5.40431 54.4081C7.37531 46.5491 9.58832 38.7701 11.8923 31.0121C12.5353 28.8511 13.2773 26.7081 14.1303 24.6221C16.2923 19.3441 18.5343 14.0991 20.7713 8.85109C21.0273 8.24809 21.3243 7.49607 21.8303 7.19307C24.5723 5.55607 25.8093 2.64406 27.7833 0.279065Z" fill="black" />
			</svg>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Collage_Block() );