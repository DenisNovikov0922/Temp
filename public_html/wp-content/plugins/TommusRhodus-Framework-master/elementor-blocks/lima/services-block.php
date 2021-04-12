<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Services_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-services-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Services', 'tr-framework' );
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
				'label' => esc_html__( 'Services', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'   => esc_html__( 'Image Left / Content Right', 'tr-framework' ),
					'right'  => esc_html__( 'Image Right / Content Left', 'tr-framework' )
				],
			]
		);
		
		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'tr-framework' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
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
		
		extract( 
			shortcode_atts( 
				array(
					'layout'   => 'left',
					'content'  => '',
					'image'    => ''
				), $this->get_settings_for_display()
			) 
		);
		
		echo '
			<div class="services">
				<div class="imageblock '. $layout .'">
					
					<div class="imageblock_holder background-image" style="background-image: url(\''. $image['url'] .'\');"></div>
					
					<div class="imageblock_content">
						<div class="imageblock_content_inner">'. $content .'</div>
					</div>
					
				</div>
			</div>
		';
		
	}

	protected function _content_template() {
		?>
		
		<div class="services">
			<div class="imageblock {{ settings.layout }}">
				
				<div class="imageblock_holder background-image" style="background-image: url('{{ settings.image.url }}');"></div>
				
				<div class="imageblock_content">
					<div class="imageblock_content_inner">{{{ settings.content }}}</div>
				</div>
				
			</div>
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Services_Block() );