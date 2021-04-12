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
		return [ 'uptime-elements' ];
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
					'slider_widget'			=> esc_html__( 'Slider Widget', 'tr-framework' ),
					'cards_1'				=> esc_html__( 'Cards 1', 'tr-framework' ),
					'cards_2'				=> esc_html__( 'Cards 2', 'tr-framework' ),
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
				<div data-twitter-fetcher data-username="'. $settings['twitter_username'] .'" data-twitter-flickity=\'{"wrapAround": true, "autoPlay":true}\' class="text-center">
					<div class="carousel-cell">
						<div class="user mb-3"></div>
						<span class="h3 tweet d-block"></span>
						<span class="text-small timePosted"></span>
					</div>
				</div>
	         ';

	     } elseif( 'slider_widget' == $settings['layout'] ) {

			echo '
				<div data-twitter-flickity=\'{"wrapAround": true, "autoPlay": false, "prevNextButtons": false, "adaptiveHeight": true}\' data-twitter-fetcher data-username="'. $settings['twitter_username'] .'">
					<div class="carousel-cell">
						<div class="tweet mb-2"></div>
						<div class="d-flex align-items-center">
							'. tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon icon-sm mr-2 opacity-30' ) .'
							<div class="timePosted text-small"></div>
						</div>
					</div>
				</div>
	         ';

	     } elseif( 'cards_1' == $settings['layout'] ) {

			echo '
				<div class="row" data-twitter-fetcher data-username="'. $settings['twitter_username'] .'" data-twitter-isotope data-sort-ascending="true">
					<div class="col-md-4" data-isotope-item>
						<div class="card card-body align-items-start">
							'. tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon mb-3 bg-primary' ) .'
							<p class="tweet w-100"></p>
							<span class="text-small timePosted"></span>
						</div>
					</div>
				</div>
	         ';

	     } elseif( 'cards_2' == $settings['layout'] ) {

			echo '
				<div class="row" data-twitter-fetcher data-username="'. $settings['twitter_username'] .'" data-twitter-isotope data-sort-ascending="true">
					<div class="col-md-6" data-isotope-item>
						<div class="card card-body flex-row">
							<div class="icon-round icon-round-sm bg-primary">
								'. tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon bg-primary' ) .'
							</div>
							<div class="ml-3">
								<p class="tweet"></p>
								<span class="text-small timePosted"></span>
							</div>
						</div>
					</div>
				</div>
	         ';

	     }
		
		if( !$user_selected_animation ){
			echo '</div>';
		}
		
	}

	protected function _content_template() {
		?>
		
		<# if ( 'slider' == settings.layout ) { #>

			<div data-twitter-fetcher data-username="{{ settings.twitter_username }}" data-twitter-flickity='{"wrapAround": true, "autoPlay":true}' class="col-lg-10 col-xl-9 text-center">
				<div class="carousel-cell">
					<div class="user mb-3"></div>
					<span class="h3 tweet d-block"></span>
					<span class="text-small timePosted"></span>
				</div>
			</div>

		<# } else if ( 'slider_widget' == settings.layout ) { #>

			<div data-twitter-flickity='{"wrapAround": true, "autoPlay": false, "prevNextButtons": false, "adaptiveHeight": true}' data-twitter-fetcher data-username="{{ settings.twitter_username }}">
				<div class="carousel-cell">
					<div class="tweet mb-2"></div>
					<div class="d-flex align-items-center">
						<?php echo tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon icon-sm mr-2 opacity-30' ); ?>
						<div class="timePosted text-small"></div>
					</div>
				</div>
			</div>

		<# } else if ( 'cards_1' == settings.layout ) { #>

			<div class="row" data-twitter-fetcher data-username="{{ settings.twitter_username }}" data-twitter-isotope data-sort-ascending="true">
				<div class="col-md-4" data-isotope-item>
					<div class="card card-body align-items-start">
						<?php echo tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon mb-3 bg-primary' ); ?>
						<p class="tweet w-100"></p>
						<span class="text-small timePosted"></span>
					</div>
				</div>
			</div>

		<# } else if ( 'cards_2' == settings.layout ) { #>

			<div class="row" data-twitter-fetcher data-username="{{ settings.twitter_username }}" data-twitter-isotope data-sort-ascending="true">
				<div class="col-md-6" data-isotope-item>
					<div class="card card-body flex-row">
						<div class="icon-round icon-round-sm bg-primary">
							<?php echo tommusrhodus_svg_icons_pluck( 'Twitter Icon', 'icon bg-primary' ); ?>
						</div>
						<div class="ml-3">
							<p class="tweet"></p>
							<span class="text-small timePosted"></span>
						</div>
					</div>
				</div>
			</div>

		<# } #>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Twitter_Slider_Block() );