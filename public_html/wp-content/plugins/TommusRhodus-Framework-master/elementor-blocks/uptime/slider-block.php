<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Slider_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-slider-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Slider', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-slideshow';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Slider Layout', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'label_block' => true,
				'options' => [
					'inline'          		=> esc_html__( 'Inline Slider', 'tr-framework' ),
					'fullwidth'         	=> esc_html__( 'Fullwidth Slider', 'tr-framework' ),
					'fullwidth_text'		=> esc_html__( 'Fullwidth with Text', 'tr-framework' ),
					'phone'					=> esc_html__( 'Phone Slider', 'tr-framework' ),
				],
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

		$repeater->add_control(
			'content', [
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
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
		
		if( 'inline' == $settings['layout'] ) {

			echo '
				<div data-flickity=\'{ "imagesLoaded": true, "wrapAround": true }\' class="mb-5">';

					foreach( $settings['list'] as $item ){

						echo '
							<div class="carousel-cell mx-3">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0, array( 'class' => 'rounded' ) ) .'
							</div>
						';

					}

					echo '
				</div>
			';
		} elseif( 'fullwidth' == $settings['layout'] ) {

			echo '
			    <section class="controls-inside controls-light p-0" data-flickity=\'{ "imagesLoaded": true, "wrapAround": true }\'>';

			      	foreach( $settings['list'] as $item ){

						echo '
							<div class="carousel-cell">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0, array( 'class' => 'w-100' ) ) .'
							</div>
						';

					}

			      echo '
			    </section>
			';

		} elseif( 'fullwidth_text' == $settings['layout'] ) {

			echo '<section class="controls-inside controls-light p-0 bg-primary-3" data-flickity=\'{ "imagesLoaded": true, "wrapAround": true }\'>';

			      	foreach( $settings['list'] as $item ){

						echo '
							<div class="carousel-cell py-5">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0, array( 'class' => 'bg-image opacity-50' ) ) .'
								<div class="container">
									<div class="row justify-content-center min-vh-70 align-items-center">
										<div class="col-lg-10 col-xl-9">
											<div class="text-center text-light">
												'. do_shortcode( $item['content'] ) .'
											</div>
										</div>
									</div>
								</div>
							</div>
						';

					}

			 echo '</section>';

		} elseif( 'phone' == $settings['layout'] ) {

			echo '
				<div class="slider-phone">
					<img src="'. get_template_directory_uri() .'/style/img/iphone-xr.svg" alt="Phone" class="col-9 col-md-5 col-lg-4 col-xl-3">
						<div data-flickity=\'{ "imagesLoaded": true, "wrapAround": true }\'>';

						foreach( $settings['list'] as $item ){

							echo '
								<div class="carousel-cell col-9 col-md-5 col-lg-4 col-xl-3 mx-4">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</div>
							';

						}

						echo '
					</div>
				</div>
			';
			
		}

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
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Slider_Block() );