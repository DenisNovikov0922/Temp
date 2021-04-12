<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Testimonials_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonials-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonials', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-blockquote';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Testimonials Layout', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slider',
				'label_block' => true,
				'options' => [
					'slider'          		=> esc_html__( 'Slider', 'tr-framework' ),
					'carousel'          	=> esc_html__( 'Carousel', 'tr-framework' ),
					'coloured-carousel'		=> esc_html__( 'Coloured Carousel', 'tr-framework' ),
					'slider-alt'          	=> esc_html__( 'Alternative Slider', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_items_section', [
				'label' => __( 'Testimonial Items', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image', [
				'label'      => __( 'Image (for Alternative Slider)', 'tr-framework' ),
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

		$repeater->add_control(
			'bg_color', [
				'label'       => __( 'Background Colour (Coloured Carousel)', 'tr-framework' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'label_block' => true
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
		
		if( 'carousel' == $settings['layout'] ) {

			echo '
				<div class="cube-carousel cbp">';

					foreach( $settings['list'] as $item ){						

						echo '
							<div class="cbp-item">
								<div class="box bg-white shadow">
									<blockquote class="icon icon-left">
										'. do_shortcode( $item['content'] ) .'
										<footer class="blockquote-footer">'. do_shortcode( $item['author'] ) .'</footer>
									</blockquote>
								</div>
							</div>
						';

					}

					echo '
				</div>
			';
		
		} elseif( 'slider' == $settings['layout'] ) {

			echo '
				<div class="cube-slider cbp-slider-edge cbp">';

					foreach( $settings['list'] as $item ){						

						echo '
							<div class="cbp-item">
								<blockquote class="icon icon-top larger text-center pl-60 pr-60">
									'. do_shortcode( $item['content'] ) .'
									<footer class="blockquote-footer">'. do_shortcode( $item['author'] ) .'</footer>
								</blockquote>
							</div>
						';

					}

					echo '
				</div>
			';
		
		} elseif( 'coloured-carousel' == $settings['layout'] ) {

			echo '
				<div class="cube-carousel cbp">';

					foreach( $settings['list'] as $item ){						

						echo '
							<div class="cbp-item">
								<div class="box" style="background-color: '. $item['bg_color'] .';">
									<blockquote class="icon icon-left">
										'. do_shortcode( $item['content'] ) .'
										<footer class="blockquote-footer">'. do_shortcode( $item['author'] ) .'</footer>
									</blockquote>
								</div>
							</div>
						';

					}

					echo '
				</div>
			';
		
		} elseif( 'slider-alt' == $settings['layout'] ) {

			echo '
				<div class="cube-slider cbp-slider-edge cbp">';

					foreach( $settings['list'] as $item ){						

						echo '
							<div class="cbp-item pl-60 pr-60 pb-10">
								<div class="row d-flex">
									<div class="col-md-6 pr-35 pr-sm-15">
										<figure>'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'</figure>
									</div>
									<div class="col-md-6 align-self-center">
										<blockquote class="icon icon-left">
											'. do_shortcode( $item['content'] ) .'
											<footer class="blockquote-footer">'. do_shortcode( $item['author'] ) .'</footer>
										</blockquote>
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

				

				});
 	 		</script>

		<?php 
		}

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Testimonials_Block() );