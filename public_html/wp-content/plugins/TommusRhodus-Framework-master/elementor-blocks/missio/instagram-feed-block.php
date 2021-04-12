<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Instagram_Feed_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-instagram-feed-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Instagram Feed', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-number-field';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'label_block' => true,
				'options' => [
					'simple'          		=> esc_html__( 'Simple', 'tr-framework' ),
					'widget'          		=> esc_html__( 'Widget', 'tr-framework' ),
					'grid'   				=> esc_html__( 'Grid', 'tr-framework' ),
					'wedding'   			=> esc_html__( 'Wedding', 'tr-framework' ),
					'portrait'   			=> esc_html__( 'Portrait', 'tr-framework' ),
					'minimal'   			=> esc_html__( 'Minimal', 'tr-framework' ),
					'widget-wedding'		=> esc_html__( 'Widget Wedding', 'tr-framework' ),
					'wedding-2'   			=> esc_html__( 'Wedding 2', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'user_id', [
				'label'       => __( 'User ID', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'access_token', [
				'label'       => __( 'Access Token', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'number_of_items', [
				'label'       => __( 'Number of items', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '6',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();

		if( 'simple' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
         	 	<div id="instafeed" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
       		</div>
			';

		} elseif( 'widget' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
         	 	<div id="instafeed-widget" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
       		</div>
			';

		} elseif( 'grid' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed2" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		} elseif( 'wedding' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed-wedding" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		} elseif( 'portrait' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed-portrait" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		} elseif( 'minimal' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed-minimal" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		} elseif( 'widget-wedding' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed-widget-wedding" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		} elseif( 'widget-2' == $settings['layout'] ) {

			echo '
			<div class="tiles tiles-s">
              	<div id="instafeed-wedding-2" class="items row" data-instagram-user-id="'. $settings['user_id'] .'" data-instagram-access-token="'. $settings['access_token'] .'" data-instagram-number-items="'. $settings['number_of_items'] .'"></div>
            </div>
			';

		}

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Instagram_Feed_Block() );