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
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Accordion Item', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
				'type'        => Controls_Manager::WYSIWYG,
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
		
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}

		echo '
			<div class="card mb-2 mb-md-3">
				<a href="#'. sanitize_file_name( $attr_title ) .'" data-toggle="collapse" role="button" aria-expanded="false" class="p-3 p-md-4"  aria-controls="'. sanitize_file_name( $attr_title ) .'">
					<div class="d-flex justify-content-between align-items-center">
						<h6 class="mb-0 mr-2">'. $title .'</h6>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="icon icon-sm" data-src="assets/img/icons/interface/icon-caret-right.svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<path d="M14.376 11.584C14.6728 11.7819 14.6728 12.2181 14.376 12.416L9.77735 15.4818C9.44507 15.7033 9 15.4651 9 15.0657L9 8.93426C9 8.53491 9.44507 8.29671 9.77735 8.51823L14.376 11.584Z" fill="#2C3038"></path>
						</svg>
					</div>
				</a>
				<div class="collapse" id="'. sanitize_file_name( $attr_title ) .'" data-parent="#'. sanitize_file_name( $attr_title ) .'">
					<div class="px-3 px-md-4 pb-3 pb-md-4">
						'. $settings['item_content'] .'
					</div>
				</div>
			</div>
		';
		
		if( !$user_selected_animation ){
			echo '</div>';
		}

	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Accordion_Block() );