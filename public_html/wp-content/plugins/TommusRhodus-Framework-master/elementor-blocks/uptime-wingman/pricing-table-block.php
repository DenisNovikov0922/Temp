<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Pricing_Table_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-pricing-table-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Pricing Table', 'tr-framework' );
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
			'layout_section', [
				'label' => __( 'Pricing Table Skin', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Skin', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-dark',
				'options' => [
					'bg-dark'  => esc_html__( 'Dark Background', 'tr-framework' ),
					'bg-white' => esc_html__( 'Light Background', 'tr-framework' )
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Pricing Table Titles', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'title', [
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Unlimited',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'currency', [
				'label'       => __( 'Currency', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '$',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'price', [
				'label'       => __( 'Price', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '9',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'small_text', [
				'label'       => __( 'Small Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'per user / month',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'description_title', [
				'label'       => __( 'Description Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Includes:',
				'label_block' => true
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'pricing_table_items_section', [
				'label' => __( 'Pricing Table Details', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Carousel Item Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Unlimited projects',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Pricing Table Details', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => __( 'Pricing Table Detail', 'tr-framework' )
					]
				],
				'title_field' => __( 'Pricing Table Detail', 'tr-framework' ),
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_button', [
				'label' => esc_html__( 'Pricing Table Button', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'button_text', [
				'label'       => __( 'Button Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Sign Up Now',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'button_class', [
				'label'       => __( 'Button Skin', 'tr-framework' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'btn-success',
				'label_block' => true,
				'options'     => [
					'btn-success'         => esc_html__( 'Primary Color', 'tr-framework' ),
					'btn-outline-primary' => esc_html__( 'Outline', 'tr-framework' )
				],
			]
		);
		
		$this->add_control(
			'url', [
				'label'         => esc_html__( 'Button URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
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
			<div class="card pricing card-lg '. $settings['layout'] .'">
			    <div class="card-body">
			        <h5>'. $settings['title'] .'</h5>
			        <span class="display-3">'. $settings['currency'] . $settings['price'] .'</span>
			        <span class="text-small">'. $settings['small_text'] .'</span>
			        <span class="h6">'. $settings['description_title'] .'</span>
			        <ul class="list-unstyled">
		';
		
		foreach( $settings['list'] as $item ){
			echo '<li>'. $item['item_title'] .'</li>';
		}
        
		echo '
					</ul>
			        <a '. $link .' class="btn btn-lg '. $settings['button_class'] .'">'. $settings['button_text'] .'</a>
			    </div>
			</div>
		';
		
	}

	protected function _content_template() {
		?>
		
			<div class="card pricing card-lg {{ settings.layout }}">
			    <div class="card-body">
			        <h5>{{{ settings.title }}}</h5>
			        <span class="display-3">{{{ settings.currency }}}{{{ settings.price }}}</span>
			        <span class="text-small">{{{ settings.small_text }}}</span>
			        <span class="h6">{{{ settings.description_title }}}</span>
			        <ul class="list-unstyled">
						
						<# _.each( settings.list, function( item ) { #>
							<li>{{{ item.item_title }}}</li>
						<# }); #>

					</ul>
			        <a href="{{ settings.url.url }}" class="btn btn-lg {{ settings.button_class }}">{{{ settings.button_text }}}</a>
			    </div>
			</div>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Pricing_Table_Block() );