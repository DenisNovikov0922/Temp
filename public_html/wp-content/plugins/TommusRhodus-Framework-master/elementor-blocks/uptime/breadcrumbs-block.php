<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Breadcrumbs_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-breadcrumbs-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
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
			'section_my_custom', [
				'label' => esc_html__( 'Colour Options', 'tr-framework' ),
			]
		);

		$this->add_control(
			'text_colour', [
				'label'   => __( 'Select a Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text-dark',
				'options' => [
					'text-white' 			=> esc_html__( 'White Text', 'tr-framework' ),
					'text-dark'        		=> esc_html__( 'Dark Text', 'tr-framework' )
				],
			]
		);
		
		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		echo get_tommusrhodus_breadcrumbs( 'breadcrumb ' . $settings['text_colour'] );
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Breadcrumbs_Block() );