<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Alert_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-alert-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Alert', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-alert';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
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

	protected function _register_controls() {
		
		$this->start_controls_section(
			'layout_section', [
				'label' => __( 'Alert Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'dismiss', [
				'label'   => __( 'Show Dismiss Button?', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no'  => 'No',
					'yes' => 'Yes'
				],
			]
		);
		
		$this->add_control(
			'style', [
				'label'   => __( 'Colour Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'alert-primary',
				'options' => [
					'alert-primary'   => 'Primary',
					'alert-primary-2' => 'Primary 2',
					'alert-primary-3' => 'Primary 3',
					'alert-secondary' => 'Secondary',
					'alert-success'   => 'Success',
					'alert-danger'    => 'Danger',
					'alert-warning'   => 'Warning',
					'alert-info'      => 'Info',
					'alert-light'     => 'Light',
					'alert-dark'      => 'Dark',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Alert Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'icon', [
				'label'   => __( 'Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'None',
				'options' => array_keys( tommusrhodus_get_svg_icons() ),
			]
		);

		$this->add_control(
			'content',
			[
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		if( 'no' == $settings['dismiss'] ){
			
			if(!( 'None' == $settings['icon'] )){
			
				echo '
					<div class="alert '. $settings['style'] .'" role="alert">
						<div class="d-flex">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
							<div class="ml-2 flex-grow-1">'. $settings['content'] .'</div>
						</div>
					</div>
				';
				
			} else {
				
				echo '<div class="alert '. $settings['style'] .'" role="alert">'. $settings['content'] .'</div>';
				
			}
		
		} else {
			
			if(!( 'None' == $settings['icon'] )){

				echo '
					<div class="alert '. $settings['style'] .' alert-dismissible fade show" role="alert">
						<div class="d-flex">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
							<div class="ml-2 flex-grow-1">'. $settings['content'] .'</div>
						</div>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
				';
				
			} else {
				
				echo '
					<div class="alert '. $settings['style'] .' alert-dismissible fade show" role="alert">
						'. $settings['content'] .'
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
				';
				
			}
			
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Alert_Block() );