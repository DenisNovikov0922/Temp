<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Speaker_List_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-speaker-list-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Speaker List', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-gallery-grid';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'speaker_items_section', [
				'label' => __( 'Speaker Item', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image', [
				'label'      => __( 'Speaker Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'speaker_name', [
				'label'       => __( 'Speaker Name', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ''
			]
		);

		$repeater->add_control(
			'speaker_link_text', [
				'label'       => __( 'Speaker Social Link Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ''
			]
		);

		$repeater->add_control(
			'speaker_link_url', [
				'label'         => esc_html__( 'Speaker Social Link URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Speaker Item Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( '', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                	= $this->get_settings_for_display();		
		$user_selected_animation 	= (bool) $settings['_animation'];
		
		echo '
			 <div class="row">';

			 	$i = 1;
				foreach( $settings['list'] as $item ){

					$target   					= $item['speaker_link_url']['is_external'] ? ' target="_blank"' : '';
					$nofollow 					= $item['speaker_link_url']['nofollow']    ? ' rel="nofollow"'  : '';
					$link     					= 'href="'. esc_url( $item['speaker_link_url']['url'] ) .'"' . $target . $nofollow;

					echo '
						<div class="col-sm-6 col-lg-4 d-flex align-items-center mb-5" data-aos="fade-up" data-aos-delay="'. esc_attr( $i ) .'00">
							'. wp_get_attachment_image( $item['image']['id'], 'thumbnail', 0, array( 'class' => 'avatar avatar-xlg mr-3' ) ) .'
							<div>
								<h5 class="mb-0">'. $item['speaker_name'] .'</h5>';

								if( !empty( $item['speaker_link_url']['url'] ) ) { 
									echo '<a '. $link .'>'. $item['speaker_link_text'] .'</a>';
								} else {
									echo '<span>'. $item['speaker_link_text'] .'</span>';
								}

								echo '
							</div>
						</div>
					';

					$i++;

				}

				echo '
			</div>
		';

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Speaker_List_Block() );