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
		return [ 'jumpstart-elements' ];
	}
	
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
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'regular',
				'label_block' => true,
				'options' => [
					'regular'          		=> esc_html__( 'Regular', 'tr-framework' ),
					'small'          		=> esc_html__( 'Small', 'tr-framework' ),
					'card'          		=> esc_html__( 'Card', 'tr-framework' ),
					'card-opaque'			=> esc_html__( 'Card Opaque (use on Dark Background)', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'title', [
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'start', [
				'label'       => __( 'Counter Start', 'tr-framework' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '4567',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'finish', [
				'label'       => __( 'Counter Finish', 'tr-framework' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '73000',
				'label_block' => true
			]
		);

		$this->add_control(
			'counter_suffix', [
				'label'       => __( 'Counter Suffix', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'colour', [
				'label'   => __( 'Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'label_block' => true,
				'options' => [
					'primary'          		=> esc_html__( 'Primary Colour', 'tr-framework' ),
					'primary-2'         	=> esc_html__( 'Primary Colour 2', 'tr-framework' ),
					'primary-3'         	=> esc_html__( 'Primary Colour 3', 'tr-framework' ),
					'dark'         			=> esc_html__( 'Dark', 'tr-framework' ),
					'custom'                => esc_html__( 'Custom Colour', 'tr-framework' )
				],
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Custom Title Color', 'tr-framework' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} [data-countup]' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'decimal', [
				'label' 		=> __( 'Contains Decimal? (eg 4.89)', 'tr-framework' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'tr-framework' ),
				'label_off' 	=> __( 'No', 'tr-framework' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'no',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];

		if( 'yes' == $settings['decimal'] ) {
			$decimal = 'data-decimal-places="2"';
		} else {
			$decimal = '';
		}	
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}

		if( 'regular' == $settings['layout'] ) {

			echo '<div class="h-100 border-top border-left border-thick border-white p-3 p-xl-4"><div class="p-3 p-md-5">';
				
			if ( Plugin::$instance->editor->is_edit_mode() ) {
			
				echo '<div class="display-4 text-'. $settings['colour'] .' mb-3"">'. $settings['finish'] . $settings['counter_suffix'] .'</div>';
				
			} else {
			
				echo '<div class="display-4 text-'. $settings['colour'] .' mb-3" data-countup data-start="'. $settings['start'] .'" data-end="'. $settings['finish'] .'" data-duration="3" data-grouping="true" data-suffix="'. $settings['counter_suffix'] .'" '. $decimal .'></div>';
				
			}
					
			echo '<div>'. $settings['title'] .'</div></div></div>';

		} elseif( 'small' == $settings['layout'] ) {

			echo '<div>';
				
			if ( Plugin::$instance->editor->is_edit_mode() ) {
			
				echo '<div class="h2 text-primary mb-2 text-'. $settings['colour'] .' mb-3"">'. $settings['finish'] . $settings['counter_suffix'] .'</div>';
				
			} else {
			
				echo '<div class="h2 text-primary mb-2 text-'. $settings['colour'] .' mb-3" data-countup data-start="'. $settings['start'] .'" data-end="'. $settings['finish'] .'" data-duration="3" data-grouping="true" data-suffix="'. $settings['counter_suffix'] .'" '. $decimal .'></div>';
				
			}
					
			echo '<h6 class="mb-0">'. $settings['title'] .'</h6></div>';

		} elseif( 'card' == $settings['layout'] ) {

			echo '
				<div class="mb-3 mb-md-0">
            		<div class="card card-body shadow-sm h-100">';

            			if ( Plugin::$instance->editor->is_edit_mode() ) {
			
							echo '<div class="display-4 text-'. $settings['colour'] .' mb-3"">'. $settings['finish'] . $settings['counter_suffix'] .'</div>';
							
						} else {
						
							echo '<div class="display-4 text-'. $settings['colour'] .' mb-3" data-countup data-start="'. $settings['start'] .'" data-end="'. $settings['finish'] .'" data-duration="3" data-grouping="true" data-suffix="'. $settings['counter_suffix'] .'" '. $decimal .'></div>';
							
						}
						
						echo '
         			 	<div>'. $settings['title'] .'</div>
         			 	
            		</div>
          		</div>';

		} elseif( 'card-opaque' == $settings['layout'] ) {

			echo '
				<div class="mb-3 mb-md-0">
            		<div class="card card-body bg-white h-100">';

            			if ( Plugin::$instance->editor->is_edit_mode() ) {
			
							echo '<div class="display-4 text-'. $settings['colour'] .' mb-3"">'. $settings['finish'] . $settings['counter_suffix'] .'</div>';
							
						} else {
						
							echo '<div class="display-4 text-'. $settings['colour'] .' mb-3" data-countup data-start="'. $settings['start'] .'" data-end="'. $settings['finish'] .'" data-duration="3" data-grouping="true" data-suffix="'. $settings['counter_suffix'] .'" '. $decimal .'></div>';
							
						}
						
						echo '
         			 	<div>'. $settings['title'] .'</div>
         			 	
            		</div>
          		</div>';

		}
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Counter_Block() );