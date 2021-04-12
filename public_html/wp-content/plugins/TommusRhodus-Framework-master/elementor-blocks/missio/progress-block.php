<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Progress_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-progress-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Progress', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-skill-bar';
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
			'progress_items_section', [
				'label' => __( 'Progress Bar', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label', [
				'label'       => __( 'Label', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'bar_percentage', [
				'label'       => __( 'Percentage', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '0',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'bg_colour', [
				'label'   => __( 'Bar Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'pastel-lavender',
				'label_block' => true,
				'options' => [
					'pastel-default'		=> esc_html__( 'Pastel Default', 'tr-framework' ),
					'pastel-lavender'		=> esc_html__( 'Pastel Lavender', 'tr-framework' ),
					'pastel-rose'			=> esc_html__( 'Pastel Rose', 'tr-framework' ),
					'pastel-leaf'			=> esc_html__( 'Pastel Leaf', 'tr-framework' ),
					'pastel-yellow'			=> esc_html__( 'Pastel Yellow', 'tr-framework' ),
					'lavender'				=> esc_html__( 'Lavender', 'tr-framework' ),
					'rose'					=> esc_html__( 'Rose', 'tr-framework' ),
					'leaf'					=> esc_html__( 'Leaf', 'tr-framework' ),
					'yellow'				=> esc_html__( 'Yellow', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Progress Bar Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Progress Bar Details', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];

		echo '<ul class="progress-list">';

			foreach( $settings['list'] as $item ) {

				echo '
					<li>
                		<p>'. $item['label'] .'</p>
	                	<div class="progressbar line '. $item['bg_colour'] .'" data-value="'. $item['bar_percentage'] .'"></div>
	              	</li>
	        	';

			}			

		echo '</ul>';
	
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Progress_Block() );