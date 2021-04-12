<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Counter_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-counter-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Counter', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-number-field';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'label_block' => true,
				'options' => [
					'simple'          		=> esc_html__( 'Simple', 'tr-framework' ),
					'boxed'          		=> esc_html__( 'Boxed', 'tr-framework' ),
					'boxed-coloured'   		=> esc_html__( 'Colour Boxes', 'tr-framework' ),
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
			'number', [
				'label'       => __( 'Number', 'tr-framework' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '73000',
				'label_block' => true
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

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Colour', 'tr-framework' ),
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

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();

		echo '<div class="counter">';

		if( 'simple' == $settings['layout'] ) {

			if( 'none' !== $settings['icon'] ) {

				echo '
				<div class="d-flex flex-row justify-content-center">
					<div class="icon fs-58 icon-color color-dark mr-25"><i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i></div>
					<div>
						<h3 class="value">'. $settings['number'] .'</h3>
						'. $settings['content'] .'
					</div>
				</div>
				';

			} else {

				echo '
				<div class="d-flex flex-row justify-content-center">
					<div>
						<h3 class="value">'. $settings['number'] .'</h3>
						'. $settings['content'] .'
					</div>
				</div>
				';

			}

		} elseif( 'boxed' == $settings['layout'] ) {

			if( 'none' !== $settings['icon'] ) {

				echo '
				<div class="box bg-white shadow">
					<div class="d-flex flex-row justify-content-center">
						<div class="icon fs-58 icon-color mr-25" style="color: '. $settings['icon_color'] .'"> <i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i> </div>
						<div>
							<h3 class="value">'. $settings['number'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				</div>
				';

			} else {

				echo '
				<div class="box bg-white shadow">
					<div class="d-flex flex-row justify-content-center">
						<div>
							<h3 class="value">'. $settings['number'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				</div>
				';

			} 

		} elseif( 'boxed-coloured' == $settings['layout'] ) {

			if( 'none' !== $settings['icon'] ) {

				echo '
				<div class="box" style="background-color: '. $settings['bg_color'] .'">
					<div class="d-flex flex-row justify-content-center">
						<div class="icon fs-58 icon-color color-dark mr-25"> <i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i> </div>
						<div>
							<h3 class="value">'. $settings['number'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				</div>
				';

			} else {

				echo '
				<div class="box" style="background-color: '. $settings['bg_color'] .'">
					<div class="d-flex flex-row justify-content-center">
						<div>
							<h3 class="value">'. $settings['number'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				</div>
				';

			}

		}

		echo '</div>';

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Counter_Block() );