<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Hover_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-hover-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image with hover', 'tr-framework' );
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
					'light'   			=> 'Light',
					'color'   			=> 'Coloured',

				],
			]
		);

		$this->add_control(
			'overlay_style', [
				'label'   => __( 'Overlay Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'overlay1',
				'options' => [
					'overlay1'   		=> 'Overlay 1',
					'overlay2'   		=> 'Overlay 2',
					'overlay3'   		=> 'Overlay 3',
					'overlay4'   		=> 'Overlay 4',

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
			'title', [
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
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

		$icons = array_combine(tommusrhodus_get_icons(), tommusrhodus_get_icons());
		$none = array('none' => 'none');
		$icons = $none + $icons;

		$this->add_control(
			'icon', [
				'label'   => __( 'Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $icons,
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

		echo '<div class="tiles"><div class="items">';

			echo '
				<div class="item">
					<figure class="overlay '. $settings['overlay_style'] .' '. $settings['caption_style'] .' rounded">';

						if( !empty( $settings['url']['url'] ) ) {
							echo '<a href="#"><span></span>'. wp_get_attachment_image( $settings['image']['id'], 'full' ) .'</a>';
						} else {
							echo '<span></span>'.wp_get_attachment_image( $settings['image']['id'], 'full' );
						}

						if( 'none' !== $settings['icon'] ) {

							echo '
							<figcaption class="d-flex">
                      			<div class="align-self-center mx-auto"><i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i></div>
                    		</figcaption>
                   			 ';

						} elseif( 'overlay1' == $settings['overlay_style'] || 'overlay2' == $settings['overlay_style'] ) {

							echo '						
							<figcaption>
								<h5 class="text-uppercase from-top mb-0">'. $settings['title'] .'</h5>';

								if( !empty( $settings['caption'] ) ) {
									echo '<div class="from-bottom">'. $settings['caption'] .'</div>';
								}

							echo '
							</figcaption>';

						}

						echo '
					</figure>
				</div>
			';

		echo '</div></div>';
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Hover_Block() );