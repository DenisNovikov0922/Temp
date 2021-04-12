<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Service_Icon_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-service-icon-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Service Icon', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-icon-box';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
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
				'label' => __( 'Layout & Content', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon-left',
				'options' => [
					'icon-left'      			=> esc_html__( 'Icon Left', 'tr-framework' ),
					'bg-icon-left'      		=> esc_html__( 'BG Icon Left', 'tr-framework' ),	
					'number-left'      			=> esc_html__( 'Number Left', 'tr-framework' ),	
					'number-left-no-box'		=> esc_html__( 'Number Left, No Box', 'tr-framework' ),	
					'icon-centered'      		=> esc_html__( 'Icon Centrred', 'tr-framework' ),
					'bg-icon-centered'      	=> esc_html__( 'BG Icon Centrred', 'tr-framework' ),	
					'number-centered'      		=> esc_html__( 'Number Centrred', 'tr-framework' ),	
				],
			]
		);
		
		$this->add_control(
			'text_layout', [
				'label'   => __( 'Text Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text-center',
				'options' => [
					'text-center'      	=> esc_html__( 'Text Center', 'tr-framework' ),
					'text-left'    		=> esc_html__( 'Text Left', 'tr-framework' ),		
				],
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
			'icon_bg_color', [
				'label' => __( 'Icon/Number Background Colour', 'tr-framework' ),
				'type' => Controls_Manager::COLOR,
				'default'     => ''
			]
		);

		$this->add_control(
			'icon_color', [
				'label' => __( 'Icon/Number Colour', 'tr-framework' ),
				'type' => Controls_Manager::COLOR,
				'default'     => ''
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

		$this->add_control(
			'number',
			[
				'label'       => __( 'Number', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();

		if( 'icon-left' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow">
					<div class="d-flex flex-row justify-content-center">
						<div class="icon fs-50 icon-color color-rose mr-25"> 
							<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"  style="color: '. $settings['icon_color'] .'"></i>
						</div>
						<div>
							 '. $settings['content'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'bg-icon-left' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow">
					<div class="d-flex flex-row justify-content-center">
						<div>
                  			<div class="icon fs-30 icon-bg mr-25" style="background-color: '. $settings['icon_bg_color'] .';"> 
                  				<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'" style="color: '. $settings['icon_color'] .';"></i> 
              				</div>
                		</div>
						<div>
							 '. $settings['content'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'number-left' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow">
					<div class="d-flex flex-row justify-content-center">
						<div>
                  			<div class="icon fs-30 icon-bg mr-25" style="background-color: '. $settings['icon_bg_color'] .';"> 
                  				<span class="number" style="color: '. $settings['icon_color'] .';">'. $settings['number'] .'</span> 
              				</div>
                		</div>
						<div>
							 '. $settings['content'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'number-left-no-box' == $settings['layout'] ){
			
			echo '
				<div>
					<div class="d-flex flex-row justify-content-center">
						<div>
                  			<div class="icon fs-30 icon-bg mr-25" style="background-color: '. $settings['icon_bg_color'] .';"> 
                  				<span class="number" style="color: '. $settings['icon_color'] .';">'. $settings['number'] .'</span> 
              				</div>
                		</div>
						<div>
							 '. $settings['content'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'icon-centered' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow text-center">
					<div class="icon fs-50 icon-color mb-20"> 
						<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'" style="color: '. $settings['icon_color'] .';"></i> 
					</div>
					'. $settings['content'] .'
				</div>
			';

		} elseif( 'bg-icon-centered' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow text-center">
					<div class="icon fs-30 icon-bg mb-20" style="background-color: '. $settings['icon_bg_color'] .';"> 
						<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'" style="color: '. $settings['icon_color'] .';"></i>
					</div>
					'. $settings['content'] .'
				</div>
			';

		} elseif( 'number-centered' == $settings['layout'] ){
			
			echo '
				<div class="box bg-white shadow text-center">
					<div class="icon fs-30 icon-bg icon-bg-s mb-20" style="background-color: '. $settings['icon_bg_color'] .';">
						<span class="number" style="color: '. $settings['icon_color'] .';">'. $settings['number'] .'</span>
					</div>
					'. $settings['content'] .'
				</div>
			';

		} 
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Service_Icon_Block() );