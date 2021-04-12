<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Portfolio_Details_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-portfolio-details-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Portfolio Details', 'tr-framework' );
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
				'label' => esc_html__( 'Portfolio Details', 'tr-framework' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'detail_title', [
				'label' => __( 'Title', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Detail Title' , 'tr-framework' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'detail_content', [
				'label' => __( 'Content', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => __( 'Detail Content' , 'tr-framework' ),
				'show_label' => false,
			]
		);

		$this->add_control(
			'details',
			[
				'label' => __( 'Detail Item', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'detail_title' => __( 'Detail Title', 'tr-framework' ),
						'detail_content' => __( 'Detail Content', 'tr-framework' ),
					],
				],
				'title_field' => '{{{ detail_title }}}',
			]
		);


		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		if ( $settings['details'] ) {
			echo '<div class="project_info">';

			foreach ( $settings['details'] as $item ) {

				echo '
					<div class="project_info_item reveal_content">
						<div class="reveal">
							<span class="project_info_title">'. $item['detail_title'] .'</span>
							<span class="project_info_desc">'. $item['detail_content'] .'</span>
						</div>
					</div>
				';

			}

			echo '</div>';
		}
		
	}

	protected function _content_template() {
		?>
		<# if ( settings.details.length ) { #>
			<div class="project_info">
				<# _.each( settings.details, function( item ) { #>
				<div class="project_info_item reveal_content">
					<div class="reveal">
						<span class="project_info_title">{{{ item.detail_title }}}</span>
						<span class="project_info_desc">{{{ item.detail_content }}}</span>
					</div>
				</div>
				<# }); #>
			</div>
		<# } #>
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Portfolio_Details_Block() );