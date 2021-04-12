<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Carousel_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Carousel', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-carousel';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Carousel Content', 'tr-framework' )
			]
		); 

		$this->add_control(
			'layout', [
				'label'   => __( 'Carousel Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fullwidth',
				'label_block' => true,
				'options' => [
					'fullwidth'										=> esc_html__( 'Fullwidth Carousel', 'tr-framework' ),
					'fullscreen'									=> esc_html__( 'Fullwidth Carousel with Fullscreen Link', 'tr-framework' ),
					'fullscreen-slider'								=> esc_html__( 'Fullscreen', 'tr-framework' ),
					'fullscreen-slider-fullscreen'					=> esc_html__( 'Fullscreen with Fullscreen Link', 'tr-framework' ),
					'fullscreen-slider-fullscreen-caption'			=> esc_html__( 'Fullscreen with Fullscreen Link & Caption (Uses img alt)', 'tr-framework' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image', [
				'label'      => __( 'Carousel Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'item_link', [
				'label' => __( 'Link Image to URL?', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( '#', 'tr-framework' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$repeater->add_control(
			'item_link_target', [
				'label'   => __( 'Link Behaviour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '_self',
				'label_block' => true,
				'options' => [
					'_self'			=> esc_html__( 'Open in Current Window', 'tr-framework' ),
					'_blank'		=> esc_html__( 'Open in New Window', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Carousel Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Carousel Content', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();

		if( 'fullwidth' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-carousel-container">
        			<div class="flickity flickity-carousel">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item">
								<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</a>
							</div>
						';

					} else {

						echo '
							<div class="item">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						';

					}

					

				}

				echo '
					</div>	
					<p class="flickity-status"></p>
				</div>		
			';

		} elseif( 'fullscreen' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-carousel-container fullscreen">
        			<div class="flickity flickity-carousel">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item">
								<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</a>
							</div>
						';

					} else {

						echo '
							<div class="item">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						';

					}

					

				}

				echo '
					</div>	
					<p class="flickity-status"></p>
				</div>		
			';

		} elseif( 'fullscreen-slider' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-carousel-container">
        			<div class="flickity flickity-carousel flickity-viewport-mode">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item mr-15">
								<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</a>
							</div>
						';

					} else {

						echo '
							<div class="item mr-15">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'fullscreen-slider-fullscreen' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-carousel-container fullscreen">
        			<div class="flickity flickity-carousel flickity-viewport-mode">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item mr-15">
								<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</a>
							</div>
						';

					} else {

						echo '
							<div class="item mr-15">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'fullscreen-slider-fullscreen-caption' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-carousel-container fullscreen">
        			<div class="flickity flickity-carousel flickity-viewport-mode">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item mr-15">
								<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
									'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								</a>
							</div>
						';

					} else {

						echo '
							<div class="item mr-15">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						';

					}

					

				}

				echo '
					</div>
					<p class="flickity-caption caption-bg bg-opacity-light color-dark">&nbsp;</p>	
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
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Carousel_Block() );