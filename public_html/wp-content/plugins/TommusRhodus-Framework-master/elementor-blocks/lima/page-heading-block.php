<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Page_Heading_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-page-heading-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Page Heading', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'lima-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Page Heading', 'tr-framework' ),
			]
		);

		$this->add_control(
			'heading_text', [
				'label' => esc_html__( 'Heading Text', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'button_text', [
				'label' => esc_html__( 'Button Text', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'button_url', [
				'label' => esc_html__( 'Button URL', 'tr-framework' ),
				'type' => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);
		
		$this->add_control(
			'icon', [
				'label'   => __( 'Social Icons', 'tr-framework' ),
				'type'    => Controls_Manager::ICON,
				'default' => 'fa fa-rocket',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		extract( 
			shortcode_atts( 
				array(
					'icon'         => 'fa fa-rocket',
					'button_url'   => '',
					'button_text'  => '',
					'heading_text' => ''
				), $this->get_settings_for_display()
			) 
		);
		
		if( isset( $button_url['url'] ) && $button_url['url'] ){
			
			$target   = $button_url['is_external'] ? ' target="_blank"' : '';
			$nofollow = $button_url['nofollow']    ? ' rel="nofollow"'  : '';
			
			echo '
				<section class="section bg_dark">
					<div class="container">
						<div class="qta light_content">
						
							<div class="qta_70">
								<h3>'. $heading_text .'</h3>
							</div>
							
							<div class="qta_30 text_right">
								<a href="'. $button_url['url'] . '"' . $target . $nofollow . ' class="btn btn_light"> 
									<span>'. $button_text .'</span>
									<i class="'. $icon .'"></i>
								</a>
							</div>
							
						</div>
					</div>
				</section>
			';
			
		} else {
		
			echo '
				<section class="section page_title_section">
					<div class="container">
						<div class="page_title">'. $heading_text .'</div>
					</div>
				</section>
			';
		
		}
		
	}

	protected function _content_template() {
		?>
		
		<div class="section page_title_section">
			<div class="container">
				<div class="page_title">{{{ settings.heading_text }}}</div>
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Page_Heading_Block() );