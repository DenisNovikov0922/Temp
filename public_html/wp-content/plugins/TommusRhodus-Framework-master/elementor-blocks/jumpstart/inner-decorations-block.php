<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Inner_Decorations_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-inner-decorations-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Inner Decorations', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-slideshow';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'styling_section', [
				'label' => __( 'Decorations Styling', 'tr-framework' )
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'blob_background',
				'label' => __( 'Background', 'tr-framework' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}.decoration-block .blob',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'slider_items_section', [
				'label' => __( 'Decorations', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'layout', [
				'label'   => __( 'Choose a Decoration', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'blob_top_left',
				'label_block' => true,
				'options' => [
					'blob_bottom_right'				=> esc_html__( 'Blob, Positon Bottom Right', 'tr-framework' ),
					'blob_bottom_left'				=> esc_html__( 'Blob, Positon Bottom Left', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Decoration', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Decoration', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                 = $this->get_settings_for_display();
		$user_selected_animation  = (bool) $settings['_animation'];	
		$user_selected_background = (bool) $settings['blob_background_background'];

		foreach( $settings['list'] as $item ){

			if( 'blob_bottom_right' == $item['layout'] ) {
				
				$class = ( $user_selected_background ) ? '' : 'bg-success opacity-90';
				
				echo '
					<div class="elementor-element elementor-element-'. $this->get_id() .' decoration-block h-75 w-75 position-absolute bottom right d-none d-lg-block" data-jarallax-element="-50">
                  		<div class="blob blob-4 w-100 h-100 '. $class .'"></div>
               		</div>
				';
				
			}	

			if( 'blob_bottom_left' == $item['layout'] ) {
				
				$class = ( $user_selected_background ) ? '' : 'bg-primary-2 opacity-90';
				
				echo '
					<div class="elementor-element elementor-element-'. $this->get_id() .' decoration-block h-50 w-50 position-absolute bottom left d-none d-lg-block" data-jarallax-element="-50">
                  		<div class="blob blob-2 w-100 h-100 '. $class .' top right"></div>
                	</div>
				';
				
			}	

		}

		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>

 	 		<script>
				jQuery(document).ready(function(){

					jQuery('.decoration-block').each(function(){
						jQuery(this).closest('.elementor-element').siblings().addClass('layer-2');	
					});

				});
 	 		</script>

		<?php 
		}

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Inner_Decorations_Block() );