<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Hero_Header_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-hero-header-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Hero Header', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_layout', [
				'label' => esc_html__( 'Layout', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Hero Header', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'label_block' => true,
				'options' => [
					'standard' 					=> esc_html__( 'Standard', 'tr-framework' ),
					'image-background' 			=> esc_html__( 'Standard, Image Background', 'tr-framework' ),
					'standard-image-right' 		=> esc_html__( 'Standard, Image Inline Right', 'tr-framework' ),					
					'alternative-image-right'	=> esc_html__( 'Alternative, Image Inline Right', 'tr-framework' ),
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'blob_background',
				'label' => __( 'Blob Background Colour', 'tr-framework' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .blob',
			]
		);
		
		$this->end_controls_section();
		
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
					'url' => '',
				],
			]
		);
		
		$this->add_control(
			'content', [
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'lower_content', [
				'label'       => __( 'Lower Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}
	
	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	protected function render() {
		
		$settings                 = $this->get_settings_for_display();
		$user_selected_background = (bool) $settings['blob_background_background'];
		
		if( 'standard' == $settings['layout'] ){
			
			$class = ( $user_selected_background ) ? '' : 'bg-gradient';
			
			echo '
				<div data-overlay class="o-hidden">
					<section>
					<div class="container">
						<div class="row justify-content-center text-center min-vh-40 d-flex flex-column align-items-center">
							<div class="col-md-10 col-lg-9 col-xl-8" data-aos="fade-up">
								'. $settings['content'] .'
								<div class="d-flex flex-column flex-sm-row mt-4 mt-md-5 justify-content-center" data-aos="fade-up" data-aos-delay="300">
									'. $settings['lower_content'] .'
								</div>
							</div>
						</div>
					</div>
						<div class="position-absolute bottom left w-50 h-50 d-none d-md-block" data-jarallax-element="-24 48">
							<div class="blob '. $class .' w-50 h-100 bottom left"></div>
						</div>
						<div class="position-absolute top right w-50 h-50 d-none d-md-block" data-jarallax-element="48">
							<div class="blob blob-2 '. $class .' w-50 h-50 top right"></div>
						</div>
					</section>
				</div>
			';
		
		} elseif( 'image-background' == $settings['layout'] ){
		
			echo '
				<div data-overlay class="bg-primary-3 jarallax text-white" data-jarallax data-speed="0.2">
					'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'jarallax-img opacity-30' ) ) .'
				  	<section>
					    <div class="container pb-5">
					      <div class="row justify-content-center text-center">
					        <div class="col-xl-8 col-lg-10 col-md-11">
						        '. $settings['content'] .'
					          	<div class="d-flex flex-column flex-sm-row justify-content-center mt-4 mt-md-5" data-aos="fade-up" data-aos-delay="300">
						           	'. $settings['lower_content'] .'
					          	</div>
					        </div>
					      </div>
					    </div>
				  	</section>
				</div>
			';
		
		} elseif( 'standard-image-right' == $settings['layout'] ){
			
			$class = ( $user_selected_background ) ? '' : 'bg-gradient';
			$class_2 = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
			
			echo '
				<section class="bg-primary-3 text-white o-hidden">
					<div class="container">
						<div class="row justify-content-between align-items-center">
							<div class="col-xl-5 col-lg-6 text-center text-lg-left mb-4 mb-md-5 mb-lg-0" data-aos="fade-right">
								'. $settings['content'] .'
							</div>
							<div class="col" data-aos="fade-left" data-aos-delay="250">
								'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid rounded shadow-lg border' ) ) .'
							</div>
						</div>
					</div>
					<div class="w-50 h-50 bottom right position-absolute" data-jarallax-element="50">
						<div class="blob blob-2 '. $class .' w-100 h-100"></div>
					</div>
					<div class="w-50 h-50 bottom right position-absolute" data-jarallax-element="75">
						<div class="blob blob-4 '. $class_2 .' w-100 h-100"></div>
					</div>
				</section>
			';

		} elseif( 'alternative-image-right' == $settings['layout'] ){
			
			$class = ( $user_selected_background ) ? '' : 'bg-warning opacity-90';
			
			echo '
				<section class="bg-light o-hidden pt-5">
					<div class="container">
						<div class="row align-items-center justify-content-between">
							<div class="col-lg-6 d-flex flex-column text-center text-lg-left mb-5 mb-lg-0" data-aos="fade-right">
								<div class="pr-xl-5">
									'. $settings['content'] .'
								</div>
							</div>
							<div class="col">
								<div class="row justify-content-center" data-jarallax-element="-50">
									<div class="col-10 col-sm-8 col-md-7 col-lg-9 col-xl-7" data-aos="zoom-in" data-aos-delay="250">
										'. wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) ) .'
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="w-50 h-50 bottom right position-absolute" data-aos="zoom-in" data-aos-delay="500">
						<div class="blob h-100 w-100 bottom right '. $class .'"></div>
					</div>
				</section>
			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Hero_Header_Block() );