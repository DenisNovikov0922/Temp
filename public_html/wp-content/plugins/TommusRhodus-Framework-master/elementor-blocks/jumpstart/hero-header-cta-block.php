<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Hero_Header_CTA_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-hero-header-cta-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Hero Header CTA', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'content', [
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'cta_content', [
				'label'       => __( 'CTA Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'blob_background',
				'label' => __( 'Blob Background Colour', 'tr-framework' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .blob',
			]
		);

		$this->end_controls_section();

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

	protected function render() {
		
		$settings                 = $this->get_settings_for_display();
		$user_selected_background = (bool) $settings['blob_background_background'];
		$class                    = ( $user_selected_background ) ? '' : 'bg-white opacity-10';
		
		echo '
			<div class="bg-gradient o-hidden position-relative" data-overlay>
					<section>
					<div class="container">
	  					<div class="row justify-content-around align-items-center">
	    					<div class="col-lg-6 col-xl-5 mb-4 mb-sm-5 mb-lg-0">
	    						'. $settings['content'] .'
							</div>
							<div class="col-lg-6 col-xl-5 col-md-9" data-aos="fade-left" data-aos-delay="250">
	      						<div class="card card-body shadow-lg">
	      							'. $settings['cta_content'] .'
	      						</div>
	      					</div>
	      				</div>
	      			</div>
	      		</section>
	      		<div class="position-absolute w-50 h-50 bottom right" data-jarallax-element="-50">
			        <div class="blob blob-3 w-100 h-100 top right '. $class .'"></div>
		      	</div>
	      	</div>
	     ';
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Hero_Header_CTA_Block() );