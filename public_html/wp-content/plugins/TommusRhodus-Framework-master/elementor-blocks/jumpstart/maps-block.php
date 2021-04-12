<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Maps_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-maps-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Map', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-google-maps';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Map Details', 'tr-framework' ),
			]
		);

		$this->add_control(
			'api_key', [
				'label'       => __( 'Google API Key', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Divider Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'simple',
				'label_block' => true,
				'options' => [
					'simple'          	=> esc_html__( 'No Divider', 'tr-framework' ),
					'divider_left'		=> esc_html__( 'Divider Left', 'tr-framework' ),
					'divider_right'		=> esc_html__( 'Divider Right', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'map_type', [
				'label'   => __( 'Card Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          	=> esc_html__( 'Basic', 'tr-framework' ),
					'basic_shadow'		=> esc_html__( 'Basic with Shadow', 'tr-framework' ),
					'styled'         	=> esc_html__( 'Styled Map', 'tr-framework' ),
					'styled_multi'		=> esc_html__( 'Styled Map + Multiple Markers', 'tr-framework' ),
				],
			]
		);


		$this->add_control(
			'divider_colour', [
				'label'   => __( 'Divider Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primarybg-primary',
				'label_block' => true,
				'options' => [
					'bg-primary'	=> esc_html__( 'Primary Background', 'tr-framework' ),
					'bg-dark'		=> esc_html__( 'Dark Background', 'tr-framework' ),					
					'bg-secondary'	=> esc_html__( 'Secondary Background', 'tr-framework' ),		
					'bg-white'		=> esc_html__( 'White Background', 'tr-framework' ),		
					'bg-light'		=> esc_html__( 'Light Background', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'address', [
				'label'       => __( 'Address - Use this if your using the "basic" map.', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'South Wharf, Melbourne Australia',
				'label_block' => true
			]
		);

		$this->add_control(
			'latlong', [
				'label'       => __( 'Latitude & Logitude (eg 40.713750,-74.007590) - Use this if your using "styled" map.', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '40.713750,-74.007590',
				'label_block' => true
			]
		);

		$this->add_control(
			'content', [
				'label'       => __( 'Marker Info', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'style_code', [
				'label'       => __( 'Map Colour Code - Available from <a href="https://snazzymaps.com/" target="_blank">here</a>', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'zoom', [
				'label' => __( 'Default Zoom Level', 'tr-framework' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 14,
				]
			]
		);

		$this->add_control(
			'show_zoom', [
				'label' 		=> __( 'Show Zoom Controls', 'tr-framework' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'tr-framework' ),
				'label_off' 	=> __( 'Hide', 'tr-framework' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'no',
			]
		);

		$this->add_control(
			'marker_image', [
				'label' => __( 'Choose Image', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => get_template_directory_uri() .'/style/img/map-marker-2.svg',
				],
			]
		);

		$this->add_control(
			'min_map_height', [
				'label'   => __( 'Minimum Map Height', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'min-vh-50',
				'label_block' => true,
				'options' => [					
					'min-vh-100'        => esc_html__( '100vh', 'tr-framework' ),
					'min-vh-50'         => esc_html__( '50vh', 'tr-framework' ),
					'min-vh-30'         => esc_html__( '30vh', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'markers_section', [
				'label' => __( 'Markers', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'marker_latlong', [
				'label'       => __( 'Latitude & Logitude (eg 40.713750,-74.007590)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '40.713750,-74.007590',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'marker_content', [
				'label'       => __( 'Marker Info', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Multiple Markers', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Marker Details', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];

		if( 'yes' == $settings['show_zoom'] ) {
			$zoom = 'data-decimal-places="2"';
		} else {
			$zoom = '';
		}			

		if( 'divider_left' == $settings['layout'] ) {
			echo '<div class="divider divider-side '. esc_attr( $settings['divider_colour'] ) .' d-none d-lg-block rotated-180"></div>';
		}
		
		if( 'basic' == $settings['map_type'] ) {

			echo '
				<div class="'. $settings['min_map_height'] .' w-100 rounded" data-marker-image="'. $settings['marker_image']['url'] .'" data-maps-api-key="'. $settings['api_key'] .'" data-address="'. $settings['address'] .'" data-map-zoom="'. $settings['zoom']['size'] .'" '. $zoom .'>
					<div class="map-marker" data-address="'. $settings['address'] .'">
						<div class="info-window" data-max-width="400">
							<div class="container">
								<div class="row">
									'. $settings['content'] .'
								</div>
							</div>
						</div>
					</div>
				</div>
	    	';
			
		} elseif( 'basic_shadow' == $settings['map_type'] ) {

			echo '
				<div class="'. $settings['min_map_height'] .' w-100 rounded shadow-3d" data-marker-image="'. $settings['marker_image']['url'] .'" data-maps-api-key="'. $settings['api_key'] .'" data-address="'. $settings['address'] .'" data-map-zoom="'. $settings['zoom']['size'] .'" '. $zoom .'>
					<div class="map-marker" data-address="'. $settings['address'] .'">
						<div class="info-window" data-max-width="400">
							<div class="container">
								<div class="row">
									'. $settings['content'] .'
								</div>
							</div>
						</div>
					</div>
				</div>
	    	';
			
		} elseif( 'styled' == $settings['map_type'] ) {

			echo '
				<div class="'. $settings['min_map_height'] .' w-100 rounded" data-marker-image="'. $settings['marker_image']['url'] .'" data-maps-api-key="'. $settings['api_key'] .'" data-latlong="'. $settings['latlong'] .'" data-map-zoom="'. $settings['zoom']['size'] .'" '. $zoom .'>
					<div class="map-marker" data-latlong="'. $settings['latlong'] .'">
						<div class="info-window" data-max-width="400">
							<div class="container">
								<div class="row">
									'. $settings['content'] .'
								</div>
							</div>
						</div>
					</div>

					<div class="map-style">
						'. $settings['style_code'] .'
					</div>
				</div>
	    	';
			
		} elseif( 'styled_multi' == $settings['map_type'] ) {

			echo '
				<div class="'. $settings['min_map_height'] .' w-100 rounded" data-marker-image="'. $settings['marker_image']['url'] .'" data-maps-api-key="'. $settings['api_key'] .'" data-latlong="'. $settings['latlong'] .'" data-map-zoom="'. $settings['zoom']['size'] .'" '. $zoom .'>';

					foreach( $settings['list'] as $item ){

						echo '
							<div class="map-marker" data-latlong="'. $item['marker_latlong'] .'">
								<div class="info-window" data-max-width="400">
									<div class="container">
										<div class="row">
											'. $item['marker_content'] .'
										</div>
									</div>
								</div>
							</div>
						';

					}

					echo '
	              	<div class="map-style">
						'. $settings['style_code'] .'
					</div>
	            </div>
	    	';
			
		}

		if( 'divider_right' == $settings['layout'] ) {
			echo '<div class="divider divider-side '. esc_attr( $settings['divider_colour'] ) .' d-none d-lg-block"></div>';
		}

	}

	protected function _content_template() {
		?>

			<# if ( 'basic' == settings.layout ) { #>

				<div class="min-vh-50 w-100 rounded" data-marker-image="{{ settings.marker_image.url }}" data-maps-api-key="{{ settings.api_key }}" data-address="{{ settings.address }}" data-map-zoom="{{ settings.zoom.size }}">
					<div class="map-marker" data-address="{{ settings.address }}">
						<div class="info-window" data-max-width="400">
							<div class="container">
								<div class="row">
									{{{ settings.content }}}
								</div>
							</div>
						</div>
					</div>
				</div>
					
			<# } else if ( 'styled' == settings.layout ) { #>

				<div class="min-vh-50 w-100 rounded" data-marker-image="{{ settings.marker_image.url }}" data-maps-api-key="{{ settings.api_key }}" data-address="{{ settings.latlong }}" data-map-zoom="{{ settings.zoom.size }}">
					<div class="map-marker" data-address="{{ settings.address }}">
						<div class="info-window" data-max-width="400">
							<div class="container">
								<div class="row">
									{{{ settings.content }}}
								</div>
							</div>
						</div>
					</div>
					<div class="map-style">
						{{{ settings.style_code }}}
					</div>
				</div>
					
			<# } else if ( 'styled_multi' == settings.layout ) { #>

				<div class="min-vh-50 w-100 rounded" data-marker-image="{{ settings.marker_image.url }}" data-maps-api-key="{{ settings.api_key }}" data-address="{{ settings.latlong }}" data-map-zoom="{{ settings.zoom.size }}">
					<# _.each( settings.list, function( item ) { #>
						<div class="map-marker" data-address="{{ item.marker_latlong }}">
							<div class="info-window" data-max-width="400">
								<div class="container">
									<div class="row">
										{{{ item.marker_content }}}
									</div>
								</div>
							</div>
						</div>
					<# }); #>
					<div class="map-style">
						{{{ settings.style_code }}}
					</div>
				</div>
					
			<# } #>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Maps_Block() );