<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Hero_Slider_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-hero-slider-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Hero Half Slider', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-slideshow';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Slider Layout', 'tr-framework' ),
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

		$this->start_controls_section(
			'slider_items_section', [
				'label' => __( 'Slider Slides', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image', [
				'label'      => __( 'Slide Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Slide Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Slide Content', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		
		echo '
			<section class="p-0 border-top border-bottom row no-gutters">

				<div class="col-lg-7 col-xl-6">
					<div class="container min-vh-lg-80 d-flex align-items-center">
						<div class="row justify-content-center">
							<div class="col col-md-10 col-xl-9 text-center text-lg-left">
								<section>
									<div data-aos="fade-right">
										'. $settings['content'] .'
									</div>
									<div class="d-flex flex-column flex-sm-row mt-4 mt-md-5 justify-content-center justify-content-lg-start" data-aos="fade-right" data-aos-delay="300">
										'. $settings['lower_content'] .'
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-5 col-xl-6 d-lg-flex flex-lg-column">
					<div class="divider divider-side transform-flip-y bg-white d-none d-lg-block"></div>
					<div class="d-lg-flex flex-column flex-fill controls-hover" data-flickity=\'{ "imagesLoaded": true, "wrapAround":true, "pageDots":false, "autoPlay":true }\'>';

						foreach( $settings['list'] as $item ){

							echo '
								<div class="carousel-cell text-center">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0, array( 'class' => 'img-fluid' ) ) .'
								</div>';

						}

						echo '
					</div>
				</div>

			</section>
		';

		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>

 	 		<script>
				jQuery(document).ready(function(){

					jQuery( '[data-flickity]' ).each(function(){
						jQuery(this).flickity();
					});

				});
 	 		</script>

		<?php 
		}

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Hero_Slider_Block() );