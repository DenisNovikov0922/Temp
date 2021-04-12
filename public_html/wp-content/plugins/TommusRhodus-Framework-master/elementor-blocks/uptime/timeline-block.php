<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Timeline_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-timeline-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Timeline', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-time-line';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'timeline_content', [
				'label' => __( 'Content', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Timeline Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'October 2016',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_description', [
				'label'       => __( 'Timeline Description', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'Secured Series-A funding',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Timeline Items', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title'       => 'October 2016',
						'item_description' => 'Secured Series-A funding'
					]
				],
				'title_field' => __( 'Timeline Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$animation               = ( $user_selected_animation ) ? false : 'data-aos="fade-left"';
		
		
		echo '<div class="row o-hidden o-lg-visible"><div class="col d-flex flex-column align-items-center"><ol class="process-vertical">';
		
		foreach( $settings['list'] as $item ){
			echo '
				<li '. $animation .'>
					<div class="process-circle bg-primary"></div>
					<div>
						<span class="text-small text-muted">'. $item['item_title'] .'</span>
						<h5 class="mb-0">'. $item['item_description'] .'</h5>
					</div>
				</li>
			';
		}
		
		echo '</ol></div></div>';
		
	}

	protected function _content_template() {
		?>
			
			<div class="row o-hidden o-lg-visible"><div class="col d-flex flex-column align-items-center"><ol class="process-vertical">
			
			<# _.each( settings.list, function( item ) { #>
				<li data-aos="fade-left">
					<div class="process-circle bg-primary"></div>
					<div>
						<span class="text-small text-muted">{{{ item.item_title }}}</span>
						<h5 class="mb-0">{{{ item.item_description }}}</h5>
					</div>
				</li>
			<# }); #>
			
			</ol></div></div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Timeline_Block() );