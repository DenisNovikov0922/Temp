<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Card_link_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-card-link-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Link Card', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'wingman-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Link Card Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'image', [
				'label'      => __( 'Card Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'url', [
				'label'         => esc_html__( 'Card Image URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'tooltip',
			[
				'label'       => __( 'Tooltip Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		$target   = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow']    ? ' rel="nofollow"'  : '';
		$link     = 'href="'. esc_url( $settings['url']['url'] ) .'"' . $target . $nofollow;
		
		echo '
			<div class="card card-sm">
			
			    <a '. $link .'>
			    	'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
			    </a>
			    
			    <div class="card-footer d-flex justify-content-between">
			        <a '. $link .' class="h6 m-0">'. $settings['title'] .'</a>
			        <a '. $link .' data-toggle="tooltip" data-placement="top" title="'. $settings['tooltip'] .'"><i class="icon-popup"></i></a>
			    </div>
			    
			</div>
		';
		
	}

	protected function _content_template() {
		?>
		
		<div class="card card-sm">
			
		    <a href="{{ settings.url.url }}">
		    	<img src="{{ settings.image.url }}" alt="" class="card-img-top">
		    </a>
			
		    <div class="card-footer d-flex justify-content-between">
		        <a href="{{ settings.url.url }}" class="h6 m-0">{{{ settings.title }}}</a>
		        <a href="{{ settings.url.url }}" data-toggle="tooltip" data-placement="top" title="{{{ settings.tooltip }}}"><i class="icon-popup"></i></a>
		    </div>
			
		</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Card_link_Block() );