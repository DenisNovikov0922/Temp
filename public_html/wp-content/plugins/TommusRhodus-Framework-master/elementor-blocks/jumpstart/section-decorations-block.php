<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Decorations_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-decorations-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Section Decorations', 'tr-framework' );
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
				'selector' => '{{WRAPPER}}.decoration-wrapper .blob',
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
				'default' => 'blob_bottom_left',
				'label_block' => true,
				'options' => [
					'gradient_blob_top_right'				=> esc_html__( 'Gradient Blob Top Right', 'tr-framework' ),
					'white_blob_bottom_right'				=> esc_html__( 'White Blob Bottom Right', 'tr-framework' ),
					'primary_blob_top_right'				    => esc_html__( 'Primary BG Blob Top Right', 'tr-framework' ),
					'big_gradient_blog_top_left' 			=> esc_html__( 'Big Gradient Blob Top Left', 'tr-framework' ),
					'big_gradient_blog_top_left_alt' 		=> esc_html__( 'Alternative Big Gradient Blob Top Left', 'tr-framework' ),	
					'big_gradient_blog_bottom_right' 		=> esc_html__( 'Big Gradient Blob Bottom Right', 'tr-framework' ),
					'white_blob_bottom_left' 				=> esc_html__( 'White Blob Bottom Left', 'tr-framework' ),
					'white_blob_top_right' 					=> esc_html__( 'White Blob Top Right', 'tr-framework' ),
					'big_white_blob_bottom_left' 			=> esc_html__( 'Big White Blob Bottom Left', 'tr-framework' ),
					'triple_blob' 							=> esc_html__( 'Triple Blob', 'tr-framework' ),
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
	
		echo '
			<div class="elementor-element elementor-element-'. $this->get_id() .' decoration-wrapper decoration-wrapper-block d-none d-sm-block">';

				foreach( $settings['list'] as $item ){

					if( 'gradient_blob_top_right' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-gradient opacity-50';
						
						echo '
							<div class="position-absolute w-50 h-100 top right" data-jarallax-element="100 48">
					        	<div class="blob blob-2 w-100 h-100 top right '. $class .'"></div>
					      	</div>
						';
						
					}	

					if( 'white_blob_bottom_right' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
						
						echo '
							<div class="position-absolute w-50 h-50 bottom right" data-jarallax-element="-50">
					        	<div class="blob blob-3 w-100 h-100 top right '. $class .'"></div>
					      	</div>
						';
						
					}	

					if( 'primary_blob_top_right' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-primary opacity-20';
						
						echo '
							<div class="position-absolute w-50 h-100 top right" data-jarallax-element="50">
					          	<div class="blob w-100 h-100 top left '. $class .'"></div>
					        </div>
						';
						
					}	

					if( 'big_gradient_blog_top_left' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-gradient';
						
						echo '
							<div class="position-absolute w-50 h-100 top left" data-jarallax-element="100 50">
          						<div class="blob blob-4 w-100 h-100 top left '. $class .'"></div>
        					</div>
						';
						
					}	

					if( 'big_gradient_blog_top_left_alt' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-gradient';
						
						echo '
							<div class="position-absolute w-100 h-100 top left" data-jarallax-element="100">
					        	<div class="blob blob-2 top left w-75 h-75 '. $class .'"></div>
					      	</div>
						';
						
					}

					if( 'big_gradient_blog_bottom_right' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-gradient';
						
						echo '
							<div class="position-absolute w-100 h-100 bottom right" data-jarallax-element="100">
					          	<div class="blob blob-4 top left w-100 h-100 '. $class .'"></div>
					        </div>
						';
						
					}	

					if( 'white_blob_top_right' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
						
						echo '
							<div class="blob blob-3 w-50 h-50 top right '. $class .'"></div>
						';
						
					}	

					if( 'white_blob_bottom_left' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
						
						echo '
							<div class="blob blob-4 w-25 h-25 bottom left '. $class .'"></div>
						';
						
					}	

					if( 'triple_blob' == $item['layout'] ) {
					
						$class = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
					
						echo '
							<div class="w-50 h-100 position-absolute top right" data-jarallax-element="100">
								<div class="blob blob-2 top right w-100 h-100 '. $class .'"></div>
								<div class="blob blob-2 top right w-75 h-75 '. $class .'"></div>
								<div class="blob blob-2 top right w-50 h-50 '. $class .'"></div>
							</div>
						';
						
					}	

					if( 'big_white_blob_bottom_left' == $item['layout'] ) {
						
						$class = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
						
						echo '
						 	<div class="h-75 w-50 position-absolute bottom left" data-jarallax-element="-60">
				        		<div class="blob bottom left w-100 h-100 '. $class .'"></div>
				     	 	</div>
						';
						
					}
				}

				echo '
			</div>
		';

		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>

 	 		<script>
				jQuery(document).ready(function(){

					jQuery('section.elementor-element .decoration-wrapper-block:first-of-type').each(function(){
						var currentSection = jQuery(this).closest('section.elementor-element');
						jQuery(this).closest('section.elementor-element').find('.elementor-element:not(.elementor-widget-tommusrhodus-decorations-block)').addClass('layer-2');
						jQuery(this).closest('section.elementor-element').addClass('o-hidden').append(this);
						jQuery(this).siblings('.cloned-decoration-wrapper').remove();
						jQuery(this).appendTo(jQuery(this).closest('section.elementor-element') ).addClass('cloned-decoration-wrapper');
						jQuery('.cloned-decoration-wrapper').replaceWith(this);
						jQuery(currentSection).find('.decoration-wrapper-block:not(.cloned-decoration-wrapper)').empty();

					});

				});
 	 		</script>

		<?php 
		}


	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Decorations_Block() );