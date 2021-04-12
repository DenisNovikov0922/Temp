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
		return [ 'missio-elements' ];
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
					'boxed'      	=> esc_html__( 'Boxed', 'tr-framework' ),
					'coloured'      => esc_html__( 'Coloured', 'tr-framework' )
				],
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => __( 'Background Colour', 'tr-framework' ),
				'type' => Controls_Manager::COLOR,
				'default'     => ''
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content', [
				'label' => esc_html__( 'Pricing Table Titles', 'tr-framework' ),
			]
		);

		$icons = array_combine(tommusrhodus_get_icons(), tommusrhodus_get_icons());
		$none = array('none' => 'none');
		$icons = $none + $icons;
		
		$this->add_control(
			'icon', [
				'label'   => __( 'Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $icons,
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
				'default'     => 'btn',
				'label_block' => true,
				'options'     => [
					'btn'         	=> esc_html__( 'Regular Button', 'tr-framework' ),
					'btn-white' 	=> esc_html__( 'White', 'tr-framework' )
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
		} else {
			$trigger_modal = false;
		}
		
		
		if( 'standard' == $settings['layout'] ){
			
			echo '
				<div class="pricing panel">
					<div class="panel-heading">';

						if( 'none' !== $settings['icon'] ) {

							echo '
							<div class="icon icon-color color-default fs-48">
								<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i>
							</div>
							';

						}

						echo '						
						<h4 class="panel-title">'. $settings['description_title'] .'</h4>
						<div class="price color-dark"> 
							<span class="price-currency">'. $settings['currency'] .'</span><span class="price-value">'. $settings['price'] .'</span> 
							<span class="price-duration">'. $settings['small_text'] .'</span>
						</div>
					</div>

					<div class="panel-body">
						<table class="table">
					';
				
					foreach( $settings['list'] as $item ){

						echo '
							<tr>
                    			<td>'. $item['item_title'] .' </td>
                  			</tr>';

					}
		        
					echo '
						</table>
						<div class="panel-footer">
							<a '. $link .' class="btn shadow '. $settings['button_class'] .'" role="button">'. $settings['button_text'] .'</a>
						</div>
					</div>
            	</div>
			';
		
		} elseif( 'boxed' == $settings['layout'] ){
			
			echo '
				<div class="pricing panel box bg-white shadow">
					<div class="panel-heading">';

						if( 'none' !== $settings['icon'] ) {

							echo '
							<div class="icon icon-color color-default fs-48">
								<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i>
							</div>
							';

						}

						echo '						
						<h4 class="panel-title">'. $settings['description_title'] .'</h4>
						<div class="price color-dark"> 
							<span class="price-currency">'. $settings['currency'] .'</span><span class="price-value">'. $settings['price'] .'</span> 
							<span class="price-duration">'. $settings['small_text'] .'</span>
						</div>
					</div>

					<div class="panel-body">
						<table class="table">
					';
				
					foreach( $settings['list'] as $item ){

						echo '
							<tr>
                    			<td>'. $item['item_title'] .' </td>
                  			</tr>';

					}
		        
					echo '
						</table>
						<div class="panel-footer">
							<a '. $link .' class="btn shadow '. $settings['button_class'] .'" role="button">'. $settings['button_text'] .'</a>
						</div>
					</div>
            	</div>
			';
		
		} elseif( 'coloured' == $settings['layout'] ){
			
			echo '
				<div class="pricing panel box" style="background-color: ' . $settings['bg_color'] . '">
					<div class="panel-heading">';

						if( 'none' !== $settings['icon'] ) {

							echo '
							<div class="icon icon-color color-default fs-48">
								<i class="'. substr($settings['icon'], 0, 2) .' '. $settings['icon'] .'"></i>
							</div>
							';

						}

						echo '						
						<h4 class="panel-title">'. $settings['description_title'] .'</h4>
						<div class="price color-dark"> 
							<span class="price-currency">'. $settings['currency'] .'</span><span class="price-value">'. $settings['price'] .'</span> 
							<span class="price-duration">'. $settings['small_text'] .'</span>
						</div>
					</div>

					<div class="panel-body">
						<table class="table">
					';
				
					foreach( $settings['list'] as $item ){

						echo '
							<tr>
                    			<td>'. $item['item_title'] .' </td>
                  			</tr>';

					}
		        
					echo '
						</table>
						<div class="panel-footer">
							<a '. $link .' class="btn shadow '. $settings['button_class'] .'" role="button">'. $settings['button_text'] .'</a>
						</div>
					</div>
            	</div>
			';
		
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Pricing_Table_Block() );