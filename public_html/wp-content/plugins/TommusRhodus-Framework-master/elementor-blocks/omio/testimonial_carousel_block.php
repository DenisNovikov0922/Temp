<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_Testimonial_Carousel_Block extends \Elementor\Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonial-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonial Carousel', 'omio' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-post';
	}
	
	public function get_categories() {
		return [ 'omio-elements' ];
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
			'content_section',
			[
				'label' => __( 'Content', 'omio' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_content', [
				'label' => __( 'Content', 'omio' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => '',
				'show_label' => false,
			]
		);

		$this->add_control(
			'list',
			[
				'label' => __( 'Testimonials', 'omio' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'omio' ),
					]
				],
				'title_field' => __( 'Testimonial Content', 'omio' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['list'] ) {
			echo '<div class="column col-6 offset-3 text-center"><div class="slider owl-carousel" data-dots="true" data-margin="50">';
			foreach (  $settings['list'] as $item ) {
				echo '<div class="item">' . $item['list_content'] . '</div>';
			}
			echo '</div></div>';
		}
	}

	protected function _content_template() {
		?>
		<# if ( settings.list.length ) { #>
		<div class="column col-6 offset-3 text-center">
			<div class="slider owl-carousel" data-dots="true" data-margin="50">
				<# _.each( settings.list, function( item ) { #>
					<div class="item">{{{ item.list_content }}}</div>
				<# }); #>
			</div>
		</div>
		<# } #>
		<?php
	}
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_Testimonial_Carousel_Block() );