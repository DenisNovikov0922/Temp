<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Testimonial_Carousel_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonial-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-blockquote';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Testimonial Carousel Layout', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card-3',
				'label_block' => true,
				'options' => [
					'fullwidth'          	=> esc_html__( 'Fullwidth Carousel', 'tr-framework' ),
					'single-with-nav'		=> esc_html__( 'Single Card', 'tr-framework' ),
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
				'label'       => __( 'Testimonial Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$repeater->add_control(
			'author', [
				'label'       => __( 'Author/Role', 'tr-framework' ),
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
		
		if( 'fullwidth' == $settings['layout'] ) {

			echo '
				 <div class="highlight-selected" data-flickity=\'{ "imagesLoaded": true, "wrapAround":true, "pageDots":false, "adaptiveHeight":true, "autoPlay":3000 }\'>';

					foreach( $settings['list'] as $item ){

						echo '
							<div class="carousel-cell col-xl-7 col-md-8">
								<div class="row align-items-center justify-content-start justify-content-sm-center mx-3 mx-xl-4">
									<div class="col-sm-auto mb-4 mb-sm-0">
										'. wp_get_attachment_image( $item['image']['id'], 'Thumbnail', 0, array( 'class' => 'img-fluid avatar avatar-xl' ) ) .'
									</div>
									<div class="col pl-lg-4">
										'. do_shortcode( $item['content'] ) .'
									</div>
								</div>
							</div>
						';

					}

					echo '
				</div>
			';
		
		} elseif( 'single-with-nav' == $settings['layout'] ) {

			echo '
				 <div class="buttons-attached" data-flickity=\'{ "imagesLoaded": true, "wrapAround":true, "pageDots":false }\'>';

					foreach( $settings['list'] as $item ){

						echo '
							<div class="carousel-cell">
								<div class="d-flex flex-column flex-lg-row no-gutters border rounded bg-white o-hidden">
									<div class="position-relative bg-gradient col-lg-4">
										'. wp_get_attachment_image( $item['image']['id'], 'large', 0, array( 'class' => 'card-img-top h-100' ) ) .'
										<div class="divider divider-side d-none d-lg-block"></div>
									</div>
									<div class="p-4 p-md-5 col-lg">
										<div class="p-lg-4">
											'. do_shortcode( $item['content'] ) .'
											<div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
												'. do_shortcode( $item['author'] ) .'
											</div>
										</div>
									</div>
								</div>
							</div>
						';

					}

					echo '
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
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Testimonial_Carousel_Block() );