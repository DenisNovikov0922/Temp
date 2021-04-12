<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Accordion_Block extends Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-accordion-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Accordion', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-accordion';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Accordion Item', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Panel Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-pastel-default',
				'label_block' => true,
				'options' => [
					'bg-pastel-default'		=> esc_html__( 'Coloured', 'tr-framework' ),
					'bg-white'				=> esc_html__( 'White', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'item_title', [
				'label'       => __( 'Accordion Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'item_content', [
				'label'       => __( 'Accordion Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings   = $this->get_settings_for_display();
		$title      = $settings['item_title'];
		$attr_title = sanitize_title_with_dashes( $title );

		echo '<div id="'. sanitize_file_name( $attr_title ) .'" class="accordion-wrapper">';

		if( 'bg-pastel-default' == $settings['layout'] ) {
		
			echo '
				<div class="card bg-pastel-default">
					<div class="card-header">
						<h3><a data-toggle="collapse" data-parent="#'. sanitize_file_name( $attr_title ) .'" href="#'. sanitize_file_name( $attr_title ) .'-collapse">'. $title .'</a></h3>
					</div>
					<!-- /.card-header -->
					<div id="'. sanitize_file_name( $attr_title ) .'-collapse" class="collapse">
						<div class="card-block">
							'. $settings['item_content'] .'
						</div>
					</div>
				</div>
			';

		} elseif( 'bg-white' == $settings['layout'] ) {
		
			echo '
				<div class="card bg-white">
					<div class="card-header">
						<h3><a data-toggle="collapse" data-parent="#'. sanitize_file_name( $attr_title ) .'" href="#'. sanitize_file_name( $attr_title ) .'-collapse">'. $title .'</a></h3>
					</div>
					<!-- /.card-header -->
					<div id="'. sanitize_file_name( $attr_title ) .'-collapse" class="collapse">
						<div class="card-block">
							'. $settings['item_content'] .'
						</div>
					</div>
				</div>
			';

		} 

		echo '</div>';

	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Accordion_Block() );