<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Processes_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-processes-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Processes', 'tr-framework' );
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
				'label'   => __( 'Processes Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical-numbered',
				'label_block' => true,
				'options' => [
					'vertical-numbered'	=> esc_html__( 'Vertical Numbered', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'processes_content', [
				'label' => __( 'Content', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Process Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Create your account',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_description', [
				'label'       => __( 'Process Description', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'Voluptatem accusantium doloremque laudantium, totam rem aperiam.',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'icon_bg', [
				'label'   => __( 'Icon Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primary',
				'label_block' => true,
				'options' => [
					'bg-primary'          	=> esc_html__( 'Primary', 'tr-framework' ),
					'bg-primary-2'			=> esc_html__( 'Primary 2', 'tr-framework' ),
					'bg-primary-3'			=> esc_html__( 'Primary 3', 'tr-framework' ),
				],
			]
		);


		$this->add_control(
			'list', [
				'label'   => __( 'Processes Items', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title'       => 'Create your account',
						'item_description' => 'Voluptatem accusantium doloremque laudantium, totam rem aperiam.',
						'icon_bg' 		   => 'bg-primary'
					]
				],
				'title_field' => __( 'Processes Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$animation               = ( $user_selected_animation ) ? false : 'data-aos="fade-left"';
		$i 						 = 1;
		
		if( 'vertical-numbered' == $settings['layout'] ) {

			echo '<ol class="list-unstyled p-0">';
			
			foreach( $settings['list'] as $item ){
				echo '
					<li class="d-flex align-items-start my-4 my-md-5">
						<div class="rounded-circle p-3 p-sm-4 d-flex align-items-center justify-content-center bg-success">
							<div class="position-absolute text-white h5 mb-0">'. $i .'</div>
						</div>
						<div class="ml-3 ml-md-4">
							<h4>'. $item['item_title'] .'</h4>
							'. $item['item_description'] .'							
						</div>
					</li>
				';
				$i++;
			}
			
			echo '</ol>';
			
		} 
			
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Processes_Block() );