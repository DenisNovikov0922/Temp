<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Testimonial_Carousel_Block extends Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonial-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-blockquote';
	}
	
	public function get_categories() {
		return [ 'melissa-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section', [
				'label' => __( 'Content', 'tr-framework' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'list_content', [
				'label'      => __( 'Content', 'tr-framework' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'list_name', [
				'label'      => __( 'Author', 'tr-framework' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Testimonials', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'tr-framework' ),
						'list_name'    => __( 'Author', 'tr-framework' ),
					]
				],
				'title_field' => __( 'Testimonial Content', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings = $this->get_settings_for_display();

		if ( $settings['list'] ) {
			echo '<div class="slider owl-carousel controls_outside testimonials" data-items="1" data-dots="true">';
			foreach (  $settings['list'] as $item ) {
				echo '
					<div class="item testimonial">
						'. wpautop( $item['list_content'] ) .'
						<span class="author">'. $item['list_name'] .'</span>
					</div>
				';
			}
			echo '</div>';
		}
		
	}

	protected function _content_template() {
		?>
		
		<# if ( settings.list.length ) { #>
		
			<div class="slider owl-carousel controls_outside testimonials" data-items="1" data-dots="true">
				<# _.each( settings.list, function( item ) { #>
					<div class="item testimonial">
						<p>{{{ item.list_content }}}</p>
						<span class="author">{{{ item.list_name }}}</span>
					</div>
				<# }); #>
			</div>
		
		<# } #>
		
		<?php
	}
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Testimonial_Carousel_Block() );