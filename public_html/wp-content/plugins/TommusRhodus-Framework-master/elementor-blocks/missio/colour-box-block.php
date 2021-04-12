<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Color_Box_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-color-box-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Color Box', 'tr-framework' );
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
			'content',
			[
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __( 'Background Colour', 'tr-framework' ),
				'type' => Controls_Manager::COLOR,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		
		echo '
			<div class="box '. $settings['text_layout'] .'" style="background-color: ' . $settings['bg_color'] . '">
	            <div class="icon fs-50 color-dark mb-20"><i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i></div>
	            '. $settings['content'] .'
	      	</div>
		';
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Color_Box_Block() );