<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Page_Heading_Block extends Widget_Base {
	
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
		return [ 'howeret-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Page Heading', 'tr-framework' ),
			]
		);

		$this->add_control(
			'small_heading_text', [
				'label' => esc_html__( 'Small Heading Text', 'tr-framework' ),
				'type' => Controls_Manager::TEXT,
				'rows' => 10,
			]
		);
		
		$this->add_control(
			'content',
			[
				'label'       => __( 'Main Heading Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		echo '
			<div class="headline">
				<div class="container">
					<div class="headline_cat reveal_content">
						<span class="reveal">'. $settings['small_heading_text'] .'</span>
					</div>

					<div class="headline_content">
						<h1 class="headline_title">
							<div class="reveal_content">
								<div class="reveal">'. $settings['content'] .'</div>
							</div>
						</h1>
					</div>
				</div>
			</div>
		';
		
	}

	protected function _content_template() {
		?>

		<div class="headline">
			<div class="container">
				<div class="headline_cat reveal_content">
					<span class="reveal">{{{ settings.small_heading_text }}}</span>
				</div>

				<div class="headline_content">
					<h1 class="headline_title">
						<span class="reveal_content">
							<span class="reveal">{{{ settings.content }}}</span>
						</span>
					</h1>
				</div>
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Page_Heading_Block() );