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
		return 'eicon-price-table';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
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
				'label'   => __( 'Pricing Table Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'      => esc_html__( 'Standard', 'tr-framework' ),
					'compact'       => esc_html__( 'Compact', 'tr-framework' )
				],
			]
		);
		
		$this->add_control(
			'skin', [
				'label'   => __( 'Skin', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'  => esc_html__( 'Standard Background', 'tr-framework' ),
					'shadow-3d' => esc_html__( 'Shadow Background', 'tr-framework' )
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
				'default'     => '',
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
				'label'       => __( 'Detail Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Unlimited projects',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'detail_style', [
				'label'   => __( 'Strikethrough Detail?', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          				=> esc_html__( 'Basic Text', 'tr-framework' ),
					'text-strikethrough'			=> esc_html__( 'Strikethrough Text', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Pricing Table Details', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
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
				'default'     => 'btn-outline-primary',
				'label_block' => true,
				'options'     => [
					'btn-primary'         => esc_html__( 'Primary Color', 'tr-framework' ),
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

		$this->add_control(
			'launching_modal', [
				'label'   => __( 'Using Button to Trigger Modal? (eg modal-6848)', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'label_block' => true,
				'options' => [					
					'no'						=> esc_html__( 'No', 'tr-framework' ),
					'yes'          				=> esc_html__( 'Yes', 'tr-framework' ),
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

		if( 'yes' == $settings['launching_modal'] ) {
			$trigger_modal = 'data-toggle="modal" data-target="#'. $settings['url']['url'] .'"';
			$link = 'href="#"';
		} else {
			$trigger_modal = false;
		}
		
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}
		
		if( 'compact' == $settings['layout'] ){
			
			echo '			
				<div class="card card-body justify-content-center text-center '. $settings['skin'] .'">
				
					<div class="text-muted"><span>'. $settings['description_title'] .'</span></div>
				
					<div class="d-flex justify-content-center my-3">
						<span class="h3 pt-1 mr-1">'. $settings['currency'] .'</span>
						<span class="display-3">'. $settings['price'] .'</span>
					</div>
					
					<div class="text-small text-muted js-pricing-charge-description">'. $settings['small_text'] .'</div>
					
					<ul class="text-center list-unstyled my-2 my-md-4">
			';
			
			foreach( $settings['list'] as $item ){

				if( 'text-strikethrough' == $item['detail_style'] ) {
					echo '<li class="py-1"><span class="text-muted text-strikethrough">'. $item['item_title'] .'</span></li>';
				} else {
					echo '<li class="py-1">'. $item['item_title'] .'</li>';
				}
				
			}
			
			echo '</ul>
				
					<a '. $link .' class="btn btn-lg mt-3 '. $settings['button_class'] .'" '. $trigger_modal .'>'. $settings['button_text'] .'</a>
				
				</div>
			';
		
		} else {
		
			echo '
				<div class="card card-body align-items-center '. $settings['skin'] .'">
					
					<span class="badge badge-top badge-dark">'. $settings['description_title'] .'</span>
					
					<div class="pt-md-2">
						<h4>'. $settings['title'] .'</h4>
					</div>
					
					<div class="d-flex align-items-start pb-md-2">
						<span class="h3">'. $settings['currency'] .'</span>
						<span class="display-4">'. $settings['price'] .'</span>
					</div>
					
					<span class="text-small text-muted">'. $settings['small_text'] .'</span>

			        <ul class="text-center list-unstyled my-2 my-md-4">
			';
			
			foreach( $settings['list'] as $item ){

				if( 'text-strikethrough' == $item['detail_style'] ) {
					echo '<li class="py-1"><span class="text-muted text-strikethrough">'. $item['item_title'] .'</span></li>';
				} else {
					echo '<li class="py-1">'. $item['item_title'] .'</li>';
				}

			}
	        
			echo '
					</ul>
					
			        <a '. $link .' class="btn '. $settings['button_class'] .'" '. $trigger_modal .'>'. $settings['button_text'] .'</a>
			        
			    </div>
			';
		
		}
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

	protected function _content_template() {
		?>

			<# if ( 'compact' == settings.layout ) { #>
				
				<div class="card card-body justify-content-center text-center {{ settings.skin }}">
					
					<div class="text-muted"><span>{{{ settings.description_title }}}</span></div>
				
					<div class="d-flex justify-content-center my-3">
						<span class="h3 pt-1 mr-1">{{{ settings.currency }}}</span>
						<span class="display-3">{{{ settings.price }}}</span>
					</div>
					
					<div class="text-small text-muted js-pricing-charge-description">{{{ settings.small_text }}}</div>
					
					<ul class="text-center list-unstyled my-2 my-md-4">
						<# _.each( settings.list, function( item ) { #>
							<# if ( 'text-strikethrough' == item.detail_style ) { #>
								<li class="py-1"><li class="py-1"><span class="text-muted text-strikethrough">{{{ item.item_title }}}</li></span>
							<# } else { #>
								<li class="py-1">{{{ item.item_title }}}</li>
							<# } #>
						<# }); #>
						
					</ul>
				
					<a href="#" class="btn btn-lg mt-3 {{ settings.button_class }}">{{{ settings.button_text }}}</a>
				
				</div>
			
			<# } else { #>
				
				<div class="card card-body align-items-center {{ settings.skin }}">
					
					<span class="badge badge-top badge-dark">{{{ settings.description_title }}}</span>
					
					<div class="pt-md-2">
						<h4>{{{ settings.title }}}</h4>
					</div>
					
					<div class="d-flex align-items-start pb-md-2">
						<span class="h3">{{{ settings.currency }}}</span>
						<span class="display-4">{{{ settings.price }}}</span>
					</div>
					
					<span class="text-small text-muted">{{{ settings.small_text }}}</span>

			        <ul class="text-center list-unstyled my-2 my-md-4">
						<# _.each( settings.list, function( item ) { #>
							<# if ( 'text-strikethrough' == item.detail_style ) { #>
								<li class="py-1"><li class="py-1"><span class="text-muted text-strikethrough">{{{ item.item_title }}}</li></span>
							<# } else { #>
								<li class="py-1">{{{ item.item_title }}}</li>
							<# } #>
						<# }); #>						
					</ul>
					
			        <a href="#" class="btn {{ settings.button_class }}">{{{ settings.button_text }}}</a>
			        
			    </div>
					
			<# } #>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Pricing_Table_Block() );