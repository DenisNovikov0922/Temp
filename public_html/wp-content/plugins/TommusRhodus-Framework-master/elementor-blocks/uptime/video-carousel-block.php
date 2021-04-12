<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Video_Carousel_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-video-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Video Carousel', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-carousel';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Carousel Layout', 'tr-framework' ),
			]
		);

		$this->add_control(
			'items_layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'label_block' => true,
				'options' => [
					'left'          	=> esc_html__( 'Left Align', 'tr-framework' ),
					'center'          	=> esc_html__( 'Center Align', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Carousel Content', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'layout', [
				'label'   => __( 'Video Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline-local',
				'label_block' => true,
				'options' => [
					'inline-local'          		=> esc_html__( 'Inline Local Video', 'tr-framework' ),
					'inline-embed-vimeo'         	=> esc_html__( 'Inline Embedded Vimeo Video', 'tr-framework' ),
					'inline-embed-youtube'         	=> esc_html__( 'Inline Embedded Youtube Video', 'tr-framework' ),
					'lightbox'         				=> esc_html__( 'Lightbox Video', 'tr-framework' ),
				],
			]
		);

		$repeater->add_control(
			'image', [
				'label'      => __( 'Lightbox Poster Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'video_url', [
				'label'       => __( 'Youtube/Vimeo Video URL - If using "inline-embed" layout, enter video ID instead', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'mp4_url', [
				'label'       => __( 'Local Video .mp4 URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'webm_url', [
				'label'       => __( 'Local Video .webm URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'ogv_url', [
				'label'       => __( 'Local Video .ogv URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
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
		
		echo '
			<div class="arrows-inside mb-6" data-flickity=\'{ "autoPlay": true, "imagesLoaded": true, "wrapAround": true, "prevNextButtons": false, "cellAlign": "'. $settings['items_layout'] .'" }\'>';

			foreach( $settings['list'] as $item ) {

				$image_url = wp_get_attachment_url( $item['image']['id'] );

				if( 'inline-local' == $item['layout'] ) {

					echo '
						<div class="carousel-cell col-lg-6 col-md-6 col-9 px-2 py-3">
							<div class="rounded o-hidden">
								<video class="plyr" poster="'. esc_url( $image_url ) .'" playsinline controls>
									<source src="'. esc_url( $item['mp4_url'] ) .'" type="video/mp4">
									<source src="'. esc_url( $item['webm_url'] ) .'" type="video/webm">
									<source src="'. esc_url( $item['ogv_url'] ) .'" type="video/ogg">
								</video>
							</div>
						</div>
					';	

				} elseif( 'inline-embed-vimeo' == $item['layout'] ) {

					echo '
						<div class="carousel-cell col-lg-6 col-md-6 col-9 px-2 py-3">
							<div class="rounded o-hidden">
			              		<div class="plyr" data-plyr-provider="vimeo" data-plyr-embed-id="'. $item['video_url'] .'"></div>
			            	</div>
		            	</div>
					';	

				} elseif( 'inline-embed-youtube' == $item['layout'] ) {

					echo '
						<div class="carousel-cell col-lg-6 col-md-6 col-9 px-2 py-3">
							<div class="rounded o-hidden">
			              		<div class="plyr" data-plyr-provider="youtube" data-plyr-embed-id="'. $item['video_url'] .'"></div>
			            	</div>	
		            	</div>
					';	

				} else {

					echo '
						<div class="carousel-cell col-lg-6 col-md-6 col-9 px-2 py-3">
							<div class="video-poster rounded">
								<a data-fancybox href="'. $item['video_url'] .'" class="btn btn-lg btn-primary btn-round">
									'. tommusrhodus_svg_icons_pluck( 'Play', 'icon' ) .'
								</a>
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
							</div>
						</div>	
					';	

				}	

			}

			echo '
			</div>		
		';

		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>

 	 		<script>
				jQuery(document).ready(function(){
					const players = Plyr.setup('.plyr');

					var nodeList = document.querySelectorAll('[data-flickity]');

					for (var i = 0, t = nodeList.length; i < t; i++) {
					    var flkty = Flickity.data(nodeList[i]);
					    if (!flkty) {
					        // Check if element had flickity options specified in data attribute.
					        var flktyData = nodeList[i].getAttribute('data-flickity');
					        if (flktyData) {
					            var flktyOptions = JSON.parse(flktyData);
					            new Flickity(nodeList[i], flktyOptions);
					        } else {
					            new Flickity(nodeList[i]);
					        }
					    }
					}
				});
 	 		</script>

		<?php 
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Video_Carousel_Block() );