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
		return [ 'jumpstart-elements' ];
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
				'default' => 'vertical',
				'label_block' => true,
				'options' => [
					'regular'         		=> esc_html__( 'Regular Tabs', 'tr-framework' ),
					'vertical'         		=> esc_html__( 'Vertical Tabs', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'intro_content', [
				'label'       => __( 'Intro Text (Vertical Only)', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);	

		$repeater = new Repeater();

		$repeater->add_control(
			'icon', [
				'label'   => __( 'Tab Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => array_keys( tommusrhodus_get_svg_icons() ),
			]
		);

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
		
		if( 'regular' == $settings['layout'] ) {

			echo '
				<div class="row">
          			<div class="col">
            			<ul class="nav nav-tabs lead mb-4 mb-md-5 justify-content-center" role="tablist">';

				    		$i = 0;

				    		foreach( $settings['list'] as $item ){
								
								$title = str_replace( '%', '', esc_attr( sanitize_title( $item['title'] ) ) );
								
				    			echo '
						      		<li class="nav-item">
						      			<a class="nav-link '; if( 0 == $i ) { echo 'active'; } echo '" href="#'. $title .'" data-toggle="tab" role="tab" aria-controls="'. $title .'" '; if( 0 == $i ) { echo 'aria-selected="true"'; } else { echo 'aria-selected="false"'; } echo '>						                  	
						                  	'. $item['title'] .'</a>
						                </a>
						     		 </li>';

						     	$i++;
				     		}

				     		echo '
				    	</ul>
				  		<div class="tab-content">';

				    		$i = 0;

				    		foreach( $settings['list'] as $item ){
								
								$title = str_replace( '%', '', esc_attr( sanitize_title( $item['title'] ) ) );
								
				    			echo '
						      		<div class="tab-pane fade show '; if( 0 == $i ) { echo 'active'; } echo '" id="'. $title .'" role="tabpanel" aria-labelledby="'. $title .'">
						      			'. $item['content'] .'
						      		</div>';

						      	$i++;
				      		}

				      		echo '

				    	</div>
				  	</div>
				</div>
			';

		} elseif( 'vertical' == $settings['layout'] ) {

			echo '
		    	<div class="row">
			      	<div class="col-xl-4 mb-5 mb-xl-0" data-aos="fade-right">
				        <div class="text-center text-xl-left mb-lg-5">
				          	'. $settings['intro_content'] .'
				        </div>
				        <ul class="nav nav-pills justify-content-center flex-xl-column pr-xl-5" role="tablist">';

				    		$i = 0;

				    		foreach( $settings['list'] as $item ){
								
								$title = str_replace( '%', '', esc_attr( sanitize_title( $item['title'] ) ) );
								
				    			echo '
						      		<li class="nav-item">
						      			<a class="btn btn-lg btn-primary w-100 '; if( 0 == $i ) { echo 'active'; } echo '" href="#'. $title .'" data-toggle="tab" role="tab" aria-controls="'. $title .'" '; if( 0 == $i ) { echo 'aria-selected="true"'; } else { echo 'aria-selected="false"'; } echo '>
							                <div class="d-flex align-items-center">
							                  	'. tommusrhodus_svg_icons_pluck( $item['icon'], 'icon bg-primary' ) .'
							                  	'. $item['title'] .'
							                </div>
						                </a>
					     		 	</li>';

						     	$i++;
				     		}

				     		echo '
				        </ul>
				    </div>
				    <div class="col" data-aos="fade-left" data-aos-delay="250">
				        <div class="tab-content">';

				    		$i = 0;

				    		foreach( $settings['list'] as $item ){
								
								$title = str_replace( '%', '', esc_attr( sanitize_title( $item['title'] ) ) );
								
				    			echo '
						      		<div class="tab-pane fade show '; if( 0 == $i ) { echo 'active'; } echo '" id="'. $title .'" role="tabpanel" aria-labelledby="'. $title .'">
						      			'. $item['content'] .'
						      		</div>';

						      	$i++;
				      		}

				      		echo '
				      	</div>
				    </div>
				</div>
			';

		}


	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Tabs_Block() );