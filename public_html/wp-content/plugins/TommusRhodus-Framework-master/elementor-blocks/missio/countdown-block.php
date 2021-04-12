<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Countdown_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-countdown-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Countdown', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-countdown';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Countdown Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'label_block' => true,
				'options' => [
					'simple'          	=> esc_html__( 'Simple', 'tr-framework' ),
					'boxed'          	=> esc_html__( 'Boxed', 'tr-framework' ),
					'boxed-coloured'	=> esc_html__( 'Colour Boxes', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'date', [
				'label'       => __( 'Date - EG Jan 1 2021', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Jan 1 2021',
				'label_block' => true
			]
		);

		$this->add_control(
			'fallback_text', [
				'label'       => __( 'Fallback Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'This is the fallback for when the countdown is elapsed',
				'label_block' => true
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$now 					 = time();

		if ( strtotime( $settings['date'] ) > $now ) {

			if( 'simple' == $settings['layout'] ) {

				echo '
					<div class="countdown" data-date="'. $settings['date'] .'">
						<div class="row text-center">

							<div class="col-md-6 col-lg-3">
								<h3 data-days>0</h3>
								<p>days</p>
							</div>

							<div class="col-md-6 col-lg-3">
								<h3 data-hours>0</h3>
								<p>hours</p>
							</div>

							<div class="space30 d-none d-md-block d-lg-none"></div>

							<div class="col-md-6 col-lg-3">
								<h3 data-minutes>0</h3>
								<p>minutes</p>
							</div>

							<div class="col-md-6 col-lg-3">
								<h3 data-seconds>0</h3>
								<p>seconds</p>
							</div>

						</div>
					</div>
	            ';

			} elseif( 'boxed' == $settings['layout'] ) {

				echo '
					<div class="countdown" data-date="'. $settings['date'] .'">
						<div class="row text-center">
							<div class="col-md-6 col-lg-3">
								<div class="box bg-white shadow">
									<h3 data-days>0</h3>
									<p>days</p>
								</div>							
							</div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-white shadow">
									<h3 data-hours>0</h3>
									<p>hours</p>
								</div>
							</div>

							<div class="space30 d-none d-md-block d-lg-none"></div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-white shadow">
									<h3 data-minutes>0</h3>
									<p>minutes</p>
								</div>
							</div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-white shadow">
									<h3 data-seconds>0</h3>
									<p>seconds</p>
								</div>
							</div>
							
						</div>
					</div>
	            ';

			} elseif( 'boxed-coloured' == $settings['layout'] ) {

				echo '
					<div class="countdown" data-date="'. $settings['date'] .'">
						<div class="row text-center">
							<div class="col-md-6 col-lg-3">
								<div class="box bg-pastel-lavender">
									<h3 data-days>0</h3>
									<p>days</p>
								</div>
							</div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-pastel-rose">
									<h3 data-hours>0</h3>
									<p>hours</p>
								</div>
							</div>

							<div class="space30 d-none d-md-block d-lg-none"></div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-pastel-leaf">
									<h3 data-minutes>0</h3>
									<p>minutes</p>
								</div>
							</div>

							<div class="col-md-6 col-lg-3">
								<div class="box bg-pastel-yellow">
									<h3 data-seconds>0</h3>
									<p>seconds</p>
								</div>
							</div>

						</div>
					</div>
	            ';

			}	

		} else {

			echo '
				<div data-countdown-date="2018/12/12">
              		<div data-elapsed-state class="d-none alert alert-danger">'. $settings['fallback_text'] .'</div>
            	</div>
        	';

		}	
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Countdown_Block() );