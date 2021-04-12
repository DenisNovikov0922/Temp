<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Tabbed_Schedule_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-tabbed-schedule-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Tabbed Schedule', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-type-tool';
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
			'schedule_item_section', [
				'label' => __( 'Schedule Item', 'tr-framework' )
			]
		);
		
		$this->add_control(
			'block_title', [
				'label'       => __( 'Block Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Schedule',
				'label_block' => true
			]
		);

		$this->add_control(
			'workshop_title', [
				'label'       => __( 'Workshop Column Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Workshop',
				'label_block' => true
			]
		);

		$this->add_control(
			'time_title', [
				'label'       => __( 'Time Column Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Time',
				'label_block' => true
			]
		);

		$this->add_control(
			'presenter_title', [
				'label'       => __( 'Presenter Column Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Presenter',
				'label_block' => true
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title', [
				'label'       => __( 'Day/Tab Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'title', [
				'label'       => __( 'Event Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'time', [
				'label'       => __( 'Event Time', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'author_name', [
				'label'       => __( 'Author Name', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'author_image', [
				'label'      => __( 'Author Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'url', [
				'label'         => esc_html__( 'Item URL', 'tr-framework' ),
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
			'list', [
				'label'   => __( 'Schedule Item Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( '', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$days                    = array_column( $settings['list'], 'tab_title' );
		$days_unique             = array_unique( $days );
		
		// If we don't have days, escape from this completely
		if( !is_array( $days ) ){
			return false;
		}
		
		echo '
			<div class="row aling-items-center mb-4">
			
				<div class="col">
					<h2 class="h1 mb-sm-0">'. $settings['block_title'] .'</h2>
				</div>
		';
		
		if( is_array( $days_unique ) ){
			
			$i = 0;
			
			echo '<div class="col-auto"><ul class="nav" role="tablist">';
			
			foreach( $days_unique as $day ){
				
				$attr     = sanitize_title( $day );
				$class    = ( 0 == $i ) ? 'active' : '';
				$selected = ( 0 == $i ) ? 'true' : 'false';
				
				echo '
					<li class="nav-item">
						<a class="nav-link btn '. $class .'" href="#tab-'. esc_attr( $attr ) .'" id="'. esc_attr( $attr ) .'-tab" data-toggle="tab" aria-controls="'. esc_attr( $attr ) .'" role="tab" aria-selected="'. $selected .'">
							'. $day .'
						</a>
					</li>
				';
				
				$i++;
				
			}
			
			echo '</ul></div>';
				
		}

		echo '	
			</div>
			
			<div class="row no-gutters d-none d-md-flex bg-white py-3">
				<div class="col-xl-7 col-md-6">
					<span class="h6 mb-0 text-muted">'. $settings['workshop_title'] .'</span>
				</div>
				<div class="col">
					<span class="h6 mb-0 text-muted">'. $settings['time_title'] .'</span>
				</div>
				<div class="col">
					<span class="h6 mb-0 text-muted">'. $settings['presenter_title'] .'</span>
				</div>
			</div>
			
			<div class="tab-content" data-aos="fade-up">
		';
		
		$i = 0;
		
		foreach( $days_unique as $day ){
			
			$attr     = sanitize_title( $day );
			$class    = ( 0 == $i ) ? 'show active' : '';
			
			echo '<div id="tab-'. esc_attr( $attr ) .'" class="list-group list-group-flush tab-pane fade '. $class .'" role="tabpanel" aria-labelledby="'. esc_attr( $attr ) .'-tab">';
			
				foreach( $settings['list'] as $item ){
					
					if(!( $item['tab_title'] == $day )){
						continue;
					}
					
					$target   = $item['url']['is_external'] ? ' target="_blank"' : '';
					$nofollow = $item['url']['nofollow']    ? ' rel="nofollow"'  : '';
					$link     = 'href="'. esc_url( $item['url']['url'] ) .'"' . $target . $nofollow;
					
					echo '
						<a '. $link .' class="list-group-item list-group-item-action row no-gutters align-items-center py-3">
						
							<div class="col-xl-7 col-md-6">
								<h5 class="mb-0">'. $item['title'] .'</h5>
							</div>
							
							<div class="col-md">
								<span>'. $item['time'] .'</span>
							</div>
					';
					
					if( $item['author_image']['id'] ){
						
						echo '
							<div class="col-md">
								<div class="d-flex align-items-center mt-2 mt-md-0">
									'. wp_get_attachment_image( $item['author_image']['id'], 'large', 0, array( 'class' => 'avatar avatar-sm mr-2' ) ) .'
									<span class="h6 mb-0">'. $item['author_name'] .'</span>
								</div>
							</div>
						';
						
					} else {
						
						echo '
							<div class="col-md">
							  <span>–</span>
							</div>
						';
						
					}
							
					echo '</a>';
					
				}
				
			echo '</div>';
			
			$i++;
				
		}
				
		echo '</div>';

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Tabbed_Schedule_Block() );