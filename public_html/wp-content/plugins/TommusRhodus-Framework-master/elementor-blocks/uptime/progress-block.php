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
				'label' => esc_html__( 'Style', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Progress Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          	=> esc_html__( 'Basic', 'tr-framework' ),
					'labels'         	=> esc_html__( 'Labelled', 'tr-framework' ),
					'coloured'         	=> esc_html__( 'Coloured', 'tr-framework' ),
					'multiple'         	=> esc_html__( 'Multiple', 'tr-framework' )
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'progress_items_section', [
				'label' => __( 'Progress Bar', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'bar_percentage', [
				'label'       => __( 'Percentage', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '0',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'bar_colour', [
				'label'   => __( 'Bar Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'bg-primary'			=> esc_html__( 'Primary Background', 'tr-framework' ),
					'bg-primary-2'			=> esc_html__( 'Primary 2 Background', 'tr-framework' ),
					'bg-primary-3'			=> esc_html__( 'Primary 3 Background', 'tr-framework' ),
					'bg-success'			=> esc_html__( 'Success Background', 'tr-framework' ),
					'bg-info'				=> esc_html__( 'Info Background', 'tr-framework' ),
					'bg-warning'			=> esc_html__( 'Warning Background', 'tr-framework' ),
					'bg-danger'				=> esc_html__( 'Danger Background', 'tr-framework' ),
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

		if( 'basic' == $settings['layout'] ) {

			foreach( $settings['list'] as $item ) {

				echo '
					<div class="progress mb-3">
	              		<div class="progress-bar '. $item['bar_colour'] .'" style="width: '. $item['bar_percentage'] .'%" role="progressbar" aria-valuenow="'. $item['bar_percentage'] .'" aria-valuemin="0" aria-valuemax="100"></div>
		            </div>
	        	';

			}			

		} elseif( 'labels' == $settings['layout'] ) {

			foreach( $settings['list'] as $item ) {

				echo '
					<div class="progress mb-3">
		             	<div class="progress-bar '. $item['bar_colour'] .'" role="progressbar" style="width: '. $item['bar_percentage'] .'%" aria-valuenow="'. $item['bar_percentage'] .'" aria-valuemin="0" aria-valuemax="100">'. $item['bar_percentage'] .'%</div>
		            </div>
	        	';

			}			

		} elseif( 'coloured' == $settings['layout'] ) {

			foreach( $settings['list'] as $item ) {

				echo '
					<div class="progress mb-3">
		             	<div class="progress-bar '. $item['bar_colour'] .'" style="width: '. $item['bar_percentage'] .'%" role="progressbar" aria-valuenow="'. $item['bar_percentage'] .'" aria-valuemin="0" aria-valuemax="100"></div>
		            </div>
	        	';

			}			

		} elseif( 'multiple' == $settings['layout'] ) {

			echo '<div class="progress mb-3">';

			foreach( $settings['list'] as $item ) {

				echo '<div class="progress-bar p-1 '. $item['bar_colour'] .'" role="progressbar" style="width: '. $item['bar_percentage'] .'%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>';

			}	

			echo '</div>';
		

		}
	
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Progress_Block() );