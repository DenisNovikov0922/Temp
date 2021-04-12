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
					'extra-tiny'      	=> esc_html__( 'Smallest Side Icon', 'tr-framework' ),
					'tiny'      		=> esc_html__( 'Tiny Side Icon', 'tr-framework' ),					
					'medium'      		=> esc_html__( 'Medium Side Icon', 'tr-framework' ),				
					'round'      		=> esc_html__( 'Round Side Icon', 'tr-framework' ),					
					'icon-top'      	=> esc_html__( 'Icon Top', 'tr-framework' ),					
					'icon-top-small'	=> esc_html__( 'Small Icon Top', 'tr-framework' )
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
			'image', [
				'label'      => __( 'Image (Will override icon and be shown full size, so be sure to supply a suitablely sized image)', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);
		

		$this->add_control(
			'icon_colour', [
				'label'   => __( 'Icon Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primary',
				'options' => [
					'bg-primary'      => esc_html__( 'Primary Colour', 'tr-framework' ),					
					'bg-white'        => esc_html__( 'White', 'tr-framework' )
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
		
		if( 'tiny' == $settings['layout'] ){
			
			echo '
				<div class="d-flex mb-3 mb-md-0">';

					if( $settings['image']['id'] ) {
						echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
					} else { 
						echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md '. $settings['icon_colour'] );
					}

					echo '
				  <div class="ml-3">'. $settings['content'] .'</div>
				</div>
			';
		
		} elseif( 'medium' == $settings['layout'] ){
			
			echo '
				<div class="d-flex align-items-center my-2">';

					if( $settings['image']['id'] ) {
						echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
					} else { 
						echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg '. $settings['icon_colour'] );
					}

					echo '
					<span class="h6 mb-0 ml-2">'. $settings['content'] .'</span>
				</div>
			';
		
		} elseif( 'icon-top' == $settings['layout'] ){
			
			echo '
				<div>
					<div class="icon-round '. $settings['icon_colour'] .' mx-auto mb-4">';

					if( $settings['image']['id'] ) {
						echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
					} else { 
						echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] );
					}

					echo '
					</div>
					'. $settings['content'] .'
				</div>
			';
		
		} elseif( 'icon-top-small' == $settings['layout'] ){
			
			echo '
				<div>
					';

					if( $settings['image']['id'] ) {
						echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
					} else { 
						echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md '. $settings['icon_colour'] );
					}

					echo '
					'. $settings['content'] .'
				</div>
			';
		
		} elseif( 'round' == $settings['layout'] ){
			
			echo '
				<div class="d-flex mb-4">
					<div class="icon-round bg-primary mr-3">';

						if( $settings['image']['id'] ) {
							echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
						} else { 
							echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary '. $settings['icon_colour'] );
						}

						echo '
					</div>
					<div>
						'. $settings['content'] .'
					</div>
				</div>
			';
		
		} elseif( 'extra-tiny' == $settings['layout'] ){
			
			echo '
				<div class="d-flex mb-3 mb-md-0">';

					if( $settings['image']['id'] ) {
						echo wp_get_attachment_image( $settings['image']['id'], 'full', 0, array( 'class' => 'img-fluid' ) );
					} else { 
						echo tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon '. $settings['icon_colour'] );
					}

					echo '
				  <div class="ml-3">'. $settings['content'] .'</div>
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