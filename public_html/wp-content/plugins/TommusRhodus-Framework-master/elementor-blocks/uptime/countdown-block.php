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
		return [ 'uptime-elements' ];
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
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          	=> esc_html__( 'Basic', 'tr-framework' ),
					'labels'         	=> esc_html__( 'Labels', 'tr-framework' ),
					'labels_2'         	=> esc_html__( 'Labels 2', 'tr-framework' ),
					'basic_labelled'   	=> esc_html__( 'Basic + Labels', 'tr-framework' ),
					'basic_labelled_2'	=> esc_html__( 'Basic + Labels 2', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'date', [
				'label'       => __( 'Date - EG 2019/08/06', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '2019/08/06',
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

			if( 'basic' == $settings['layout'] ) {

				echo '<h1 data-countdown-date="'. $settings['date'] .'"></h1>';

			} elseif( 'labels' == $settings['layout'] ) {

				echo '
					<div class="col-12" data-countdown-date="'. $settings['date'] .'" data-detailed>
						<div data-active class="row text-center">
						  <div class="col">
						    <div class="card card-body shadow-3d">
						      <span class="h1 text-primary mb-2" data-weeks data-format="%w"></span>
						      <span class="h6 mb-0" data-weeks-label></span>
						    </div>
						  </div>

						  <div class="col">
						    <div class="card card-body shadow-3d">
						      <span class="h1 text-primary mb-2" data-days data-format="%d"></span>
						      <span class="h6 mb-0" data-days-label></span>
						    </div>
						  </div>

						  <div class="col">
						    <div class="card card-body shadow-3d">
						      <span class="h1 text-primary mb-2" data-hours></span>
						      <span class="h6 mb-0" data-hours-label></span>
						    </div>
						  </div>

						  <div class="col">
						    <div class="card card-body shadow-3d">
						      <span class="h1 text-primary mb-2" data-minutes></span>
						      <span class="h6 mb-0" data-minutes-label></span>
						    </div>
						  </div>

						  <div class="col">
						    <div class="card card-body shadow-3d">
						      <span class="h1 text-primary mb-2" data-seconds></span>
						      <span class="h6 mb-0" data-seconds-label></span>
						    </div>
						  </div>
						</div>
						<div data-elapsed style="display: none;">
						  <h1>'. $settings['fallback_text'] .'</h1>
						</div>
					</div>
				';

			} elseif( 'labels_2' == $settings['layout'] ) {

				echo '
					<div class="col-12" data-countdown-date="'. $settings['date'] .'" data-detailed>
						<div data-active class="row text-center">
							<div class="col-md">
								<div class="card card-body" data-aos="fade-up" data-aos-delay="100">
									<span class="h1 text-primary mb-2" data-weeks data-format="%w"></span>
									<span class="h6 mb-0 text-dark" data-weeks-label></span>
								</div>
							</div>

							<div class="col-md">
								<div class="card card-body" data-aos="fade-up" data-aos-delay="200">
									<span class="h1 text-primary mb-2" data-days data-format="%d"></span>
									<span class="h6 mb-0 text-dark" data-days-label></span>
								</div>
							</div>

							<div class="col-md">
								<div class="card card-body" data-aos="fade-up" data-aos-delay="300">
									<span class="h1 text-primary mb-2" data-hours></span>
									<span class="h6 mb-0 text-dark" data-hours-label></span>
								</div>
							</div>

							<div class="col-md">
								<div class="card card-body" data-aos="fade-up" data-aos-delay="400">
									<span class="h1 text-primary mb-2" data-minutes></span>
									<span class="h6 mb-0 text-dark" data-minutes-label></span>
								</div>
							</div>

							<div class="col-md">
								<div class="card card-body" data-aos="fade-up" data-aos-delay="500">
									<span class="h1 text-primary mb-2" data-seconds></span>
									<span class="h6 mb-0 text-dark" data-seconds-label></span>
								</div>
							</div>
						</div>
						<div data-elapsed style="display: none;">
							<h4>'. $settings['fallback_text'] .'</h4>
						</div>
					</div>
				';

			} elseif( 'basic_labelled' == $settings['layout'] ) {

				echo '
					<div class="col-12 mb-4" data-countdown-date="'. $settings['date'] .'" data-detailed>
						<div data-active class="row text-center">
							<div class="col">
								<span class="h1 text-primary mb-2" data-days data-format="%D">13</span>
								<span class="h6 mb-0" data-days-label></span>
							</div>

							<div class="col">
								<span class="h1 text-primary mb-2" data-hours>06</span>
								<span class="h6 mb-0" data-hours-label></span>
							</div>

							<div class="col">
								<span class="h1 text-primary mb-2" data-minutes>51</span>
								<span class="h6 mb-0" data-minutes-label></span>
							</div>

							<div class="col">
								<span class="h1 text-primary mb-2" data-seconds>20</span>
								<span class="h6 mb-0" data-seconds-label></span>
							</div>
						</div>
						<div data-elapsed style="display: none;">
						  	<h1>'. $settings['fallback_text'] .'</h1>
						</div>
					</div>
				';

			} elseif( 'basic_labelled_2' == $settings['layout'] ) {

				echo '
					<div class="col" data-detailed data-countdown-date="'. $settings['date'] .'">
						<div data-active class="row text-center">
							<div class="col">
								<div data-aos="fade-up" data-aos-delay="100">
									<div class="display-4 mb-2" data-weeks data-format="%w"></div>
									<span class="h6 mb-0" data-weeks-label></span>
								</div>
							</div>
							<div class="col">
								<div data-aos="fade-up" data-aos-delay="200">
									<div class="display-4 mb-2" data-days data-format="%d"></div>
									<span class="h6 mb-0" data-days-label></span>
								</div>
							</div>
							<div class="col">
								<div data-aos="fade-up" data-aos-delay="300">
									<div class="display-4 mb-2" data-hours></div>
									<span class="h6 mb-0" data-hours-label></span>
								</div>
							</div>
							<div class="col">
								<div data-aos="fade-up" data-aos-delay="400">
									<div class="display-4 mb-2" data-minutes></div>
									<span class="h6 mb-0" data-minutes-label></span>
								</div>
							</div>
							<div class="col">
								<div data-aos="fade-up" data-aos-delay="500">
									<div class="display-4 mb-2" data-seconds></div>
									<span class="h6 mb-0" data-seconds-label></span>
								</div>
							</div>
						</div>
						<div data-elapsed style="display: none;">
							<h4>'. $settings['fallback_text'] .'</h4>
						</div>
					</div>
				';

			}	

		} else {

			echo '<div data-countdown-date="2018/12/12" data-detailed>
              		<div data-elapsed-state class="d-none alert alert-danger">'. $settings['fallback_text'] .'</div>
            	</div>';

		}	
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Countdown_Block() );