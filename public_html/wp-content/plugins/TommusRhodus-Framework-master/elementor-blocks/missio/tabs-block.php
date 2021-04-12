<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Tabs_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-tabs-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Tab', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-tabs';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'tab_items_section', [
				'label' => __( 'Tab', 'tr-framework' )
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'coloured',
				'label_block' => true,
				'options' => [
					'coloured'          	=> esc_html__( 'Coloured Tabs', 'tr-framework' ),
					'regular'         		=> esc_html__( 'Regular Tabs', 'tr-framework' ),
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

		$repeater = new Repeater();

		$repeater->add_control(
			'title', [
				'label'       => __( 'Tab Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ''
			]
		);

		$repeater->add_control(
			'content', [
				'label'       => __( 'Tab Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);		

		$this->add_control(
			'list', [
				'label'   => __( 'Tab Details', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( '', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                	= $this->get_settings_for_display();		
		$user_selected_animation 	= (bool) $settings['_animation'];
		
		if( 'coloured' == $settings['layout'] ) {

			echo '
				<div class="tabs-wrapper" style="background-color: ' . $settings['bg_color'] . '">
          			<ul class="nav nav-tabs">';

			    		$i = 0;

			    		foreach( $settings['list'] as $item ){

			    			echo '
			    				<li class="nav-item"> 
		                			<a class="nav-link '; if( 0 == $i ) { echo 'active'; } echo '" data-toggle="tab" href="#'. sanitize_title( $item['title'] ) .'">'. $item['title'] .'</a>
		            			</li>';

					     	$i++;
			     		}

			     		echo '
			    	</ul>
			  		<div class="tab-content">';

			    		$i = 0;

			    		foreach( $settings['list'] as $item ){

			    			echo '
					      		<div class="tab-pane fade show '; if( 0 == $i ) { echo 'active'; } echo '" id="'. sanitize_title( $item['title'] ) .'"">
					      			'. $item['content'] .'
					      		</div>';

					      	$i++;
			      		}

			      		echo '
			    	</div>
				</div>
			';

		} elseif( 'regular' == $settings['layout'] ) {

			echo '
				<div class="tabs-wrapper bg-white shadow">
          			<ul class="nav nav-tabs">';

			    		$i = 0;

			    		foreach( $settings['list'] as $item ){

			    			echo '
			    				<li class="nav-item"> 
		                			<a class="nav-link '; if( 0 == $i ) { echo 'active'; } echo '" data-toggle="tab" href="#'. sanitize_title( $item['title'] ) .'">'. $item['title'] .'</a>
		            			</li>';

					     	$i++;
			     		}

			     		echo '
			    	</ul>
			  		<div class="tab-content">';

			    		$i = 0;

			    		foreach( $settings['list'] as $item ){

			    			echo '
					      		<div class="tab-pane fade show '; if( 0 == $i ) { echo 'active'; } echo '" id="'. sanitize_title( $item['title'] ) .'"">
					      			'. $item['content'] .'
					      		</div>';

					      	$i++;
			      		}

			      		echo '
			    	</div>
				</div>
			';

		}


	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Tabs_Block() );