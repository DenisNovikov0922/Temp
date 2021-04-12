<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Typed_Text_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-typed-text-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Typed Text', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-type-tool';
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
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Countdown Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          		=> esc_html__( 'Basic', 'tr-framework' ),
					'highlight'         	=> esc_html__( 'Highlight', 'tr-framework' ),
					'highlight-large'		=> esc_html__( 'Highlight Large', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'prefix', [
				'label'       => __( 'Prefix Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Use Uptime To',
				'label_block' => true
			]
		);	

		$this->end_controls_section();

		$this->start_controls_section(
			'typed_text_items_section', [
				'label' => __( 'Typed Text Item', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text', [
				'label'       => __( 'Typed Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'launch your product.',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Typed Text Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'launch your product.', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$item_text               = '"'. rtrim( implode('","', array_column( $settings['list'], 'item_text' ) ), ',"' ) .'"';

		if( 'basic' == $settings['layout'] ) {

			echo "
				<span>". $settings['prefix'] ."</span>
        		<span data-typed-text data-loop='true' data-type-speed='45' data-strings='[". $item_text ."]'></span>
        	";

		} elseif( 'highlight' == $settings['layout'] ) {
			
			echo "
				<span class='h3'>". $settings['prefix'] ."</span>
	            <div class='highlight'>
	              	<span class='h3' data-typed-text data-loop='true' data-type-speed='45' data-strings='[". $item_text ."]'></span>
	            </div>
			";

		}

		elseif( 'highlight-large' == $settings['layout'] ) {
			
			echo "
				<div class='h1'>
				  	<span>". $settings['prefix'] ."</span>
				  	<div class='highlight'>
				  		<span class='h1' data-typed-text data-loop='true' data-type-speed='65' data-strings='[". $item_text ."]'></span>
				  	</div>
				</div>
			";

		}	
	
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Typed_Text_Block() );