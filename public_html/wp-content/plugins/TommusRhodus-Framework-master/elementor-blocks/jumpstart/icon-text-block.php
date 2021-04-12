<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Icon_Text_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-icon-text-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Icon & Text', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-icon-box';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
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
				'label' => __( 'Icon Styling', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Icon & Text Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tiny',
				'options' => [				
					'small-side'      					=> esc_html__( 'Small Side Icon Card', 'tr-framework' ),	
					'medium-side'      					=> esc_html__( 'Medium Side Icon Card', 'tr-framework' ),	
					'medium-side-simple'				=> esc_html__( 'Medium Side Icon', 'tr-framework' ),
					'medium-top-simple'					=> esc_html__( 'Medium Top Icon', 'tr-framework' ),	
					'medium-top-simple-card'			=> esc_html__( 'Medium Top Icon Card', 'tr-framework' ),
					'rounded-top-simple'				=> esc_html__( 'Round Top Icon Centered', 'tr-framework' ),	
					'rounded-top-simple-left-align'		=> esc_html__( 'Round Top Icon Card', 'tr-framework' ),	
				],
			]
		);
		
		$this->add_control(
			'icon', [
				'label'   => __( 'Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => array_keys( tommusrhodus_get_svg_icons() ),
			]
		);

		$this->add_control(
			'icon_colour', [
				'label'   => __( 'Icon Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primary',
				'options' => [
					'bg-primary'      	=> esc_html__( 'Primary Colour', 'tr-framework' ),
					'bg-primary-2'		=> esc_html__( 'Primary Colour 2', 'tr-framework' ),					
					'bg-white'        	=> esc_html__( 'White', 'tr-framework' ),					
					'bg-success'		=> esc_html__( 'Success', 'tr-framework' )
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Icon & Text Content', 'tr-framework' ),
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
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="100">';
		}
		
		if( 'small-side' == $settings['layout'] ){
			
			echo '
				<div class="mb-3 mr-4 ml-lg-0 mr-lg-4">
					<div class="d-flex align-items-center">
						<div class="rounded-circle '.  $settings['icon_colour'] .'-alt">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'm-2 icon icon-xs '. $settings['icon_colour'] ) .'
						</div>
						<h6 class="mb-0 ml-3">'. $settings['content'] .'</h6>
					</div>
				</div>
			';
		
		} elseif( 'medium-side' == $settings['layout'] ){
			
			echo '
				<div class="card card-body bg-white align-items-start flex-sm-row h-100">
					'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon mb-4 mb-sm-0 '. $settings['icon_colour'] ) .'
					<div class="ml-sm-3 ml-md-4">
						'. $settings['content'] .'						
					</div>
				</div>
			';
		
		} elseif( 'medium-side-simple' == $settings['layout'] ){
			
			echo '
				<div class="my-4">
					<div class="d-flex">
						<div class="mr-3 mr-md-4">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] ) .'
						</div>
						<div>
							'. $settings['content'] .'
						</div>
					</div>
                </div>
			';
		
		} elseif( 'medium-top-simple' == $settings['layout'] ){
			
			echo '
				<div class="mb-4 mb-md-0">
					<div>
						'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] ) .'
						'. $settings['content'] .'
					</div>
				</div>
			';
		
		} elseif( 'medium-top-simple-card' == $settings['layout'] ){
			
			echo '
				<div class="mb-3 mb-md-4">
					<div class="card card-body">
						<div>
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] ) .'
							'. $settings['content'] .'
						</div>
					</div>
				</div>
			';
		
		} elseif( 'rounded-top-simple' == $settings['layout'] ){
			
			echo '
				<div class="mb-3 mb-md-4 text-center">
					<div>
                		<div class="d-inline-block mb-4 p-3 p-md-4 rounded-circle '. $settings['icon_colour'] .'-alt">
                			'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] ) .'
                		</div>
						<div>
							'. $settings['content'] .'
						</div>
              		</div>
				</div>
			';
		
		} elseif( 'rounded-top-simple-left-align' == $settings['layout'] ){
			
			echo '
				<div class="mb-4">
					<div class="card card-body h-100 shadow-sm">
						<div>
							<div class="d-inline-block mb-4 p-3 p-md-4 rounded-circle '. $settings['icon_colour'] .'-alt">
								'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-sm '. $settings['icon_colour'] ) .'
							</div>
							'. $settings['content'] .'
						</div>
					</div>
				</div>
			';
		
		}
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Icon_Text_Block() );