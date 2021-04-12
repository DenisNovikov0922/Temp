<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Twitter_Slider_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-twiter-slider-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Twitter Feed', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-twitter-embed';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Feed Settings', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slider',
				'label_block' => true,
				'options' => [
					'slider'          		=> esc_html__( 'Slider', 'tr-framework' ),
					'cards'					=> esc_html__( 'Cards', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'twitter_username', [
				'label'       => __( 'Twitter Username', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'TommusRhodus',
				'label_block' => true
			]
		);

		$this->add_control(
			'max_tweets', [
				'label'       => __( 'Max Number of Tweets', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '4',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}

		if( 'slider' == $settings['layout'] ) {

			echo '
				<div data-twitter-fetcher data-max-tweets="'. $settings['max_tweets'] .'" data-username="'. $settings['twitter_username'] .'" data-twitter-flickity=\'{ "wrapAround": true, "autoPlay": true, "adaptiveHeight":true }\'>
					<div class="carousel-cell">
						<div class="row justify-content-center">
							<div class="col-lg-8">
								<p class="tweet"></p>
								<small class="timePosted"></small>
							</div>
						</div>
					</div>
				</div>
	         ';

	     } elseif( 'cards' == $settings['layout'] ) {

			echo '
				<div class="row" data-twitter-fetcher data-username="'. $settings['twitter_username'] .'" data-max-tweets="'. $settings['max_tweets'] .'">
					<div class="col-md-6 mb-3 mb-md-4">
						<div class="card card-body h-100 align-items-start shadow-sm">
							<div class="rounded-circle bg-primary mb-4">
								'. tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon icon-xs bg-white m-3 bg-primary' ) .'
							</div>
							<p class="tweet"></p>
							<small class="timePosted"></small>
						</div>
					</div>
				</div>
	         ';

	     }
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Twitter_Slider_Block() );