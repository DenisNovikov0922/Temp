<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Card_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-card-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Card', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-info-box';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout', [
				'label' => esc_html__( 'Layout', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Card Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          					=> esc_html__( 'Basic', 'tr-framework' ),
					'icon-1'         					=> esc_html__( 'Icon 1', 'tr-framework' ),
					'icon-1-dark'						=> esc_html__( 'Icon 1 Primary BG', 'tr-framework' ),
					'icon-1-primary-2'					=> esc_html__( 'Icon 1 Primary 2 BG', 'tr-framework' ),
					'icon-1-primary-3'					=> esc_html__( 'Icon 1 Primary 3 BG', 'tr-framework' ),
					'icon-2'         					=> esc_html__( 'Icon 2', 'tr-framework' ),
					'icon-2-tited-hover'				=> esc_html__( 'Icon 2 Tilted on Hover', 'tr-framework' ),
					'icon-2-tited-hover-primary'		=> esc_html__( 'Icon 2 Tilted on Hover Primary BG', 'tr-framework' ),
					'icon-2-tited-hover-primary-2'		=> esc_html__( 'Icon 2 Tilted on Hover Primary 2 BG', 'tr-framework' ),
					'icon-2-tited-hover-primary-3'		=> esc_html__( 'Icon 2 Tilted on Hover Primary 3 BG', 'tr-framework' ),
					'icon-3'         					=> esc_html__( 'Icon 3', 'tr-framework' ),
					'icon-3-tilted'						=> esc_html__( 'Icon 3 Tilted', 'tr-framework' ),
					'icon-3-tilted-alt'					=> esc_html__( 'Icon 3 Tilted Alternative', 'tr-framework' ),
					'icon-3-tilted-primary-2'			=> esc_html__( 'Icon 3 Tilted Primary 2', 'tr-framework' ),
					'icon-3-tilted-primary-3'			=> esc_html__( 'Icon 3 Tilted Primary 3', 'tr-framework' ),
					'icon-4'         					=> esc_html__( 'Icon 4', 'tr-framework' ),
					'icon-4-primary-2'         			=> esc_html__( 'Icon 4 Primary 2', 'tr-framework' ),
					'icon-4-hover'						=> esc_html__( 'Icon 4 + Hover Effect', 'tr-framework' ),
					'customer-1'						=> esc_html__( 'Customer 1', 'tr-framework' ),
					'customer-2'						=> esc_html__( 'Customer 2', 'tr-framework' ),
					'customer-3'						=> esc_html__( 'Customer 3', 'tr-framework' ),
					'customer-4'						=> esc_html__( 'Customer 4', 'tr-framework' ),
					'customer-5'						=> esc_html__( 'Customer 5', 'tr-framework' ),
					'customer-7'						=> esc_html__( 'Customer 7', 'tr-framework' ),
					'customer-8'						=> esc_html__( 'Customer 8', 'tr-framework' ),
					'large-image-and-text'				=> esc_html__( 'Large Image + Text', 'tr-framework' ),
					'large-image-and-text-overlay'		=> esc_html__( 'Large Image + Text Overlay', 'tr-framework' ),
					'event'								=> esc_html__( 'Event', 'tr-framework' ),
					'event-2'							=> esc_html__( 'Event 2', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Card Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'image', [
				'label'      => __( '<strong>Fields are used on a per-layout basis, if you find a URL or such us not effecting the card, choose another layout.</strong><br><br>Card Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'icon', [
				'label'   => __( 'Icon', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => array_keys( tommusrhodus_get_svg_icons() ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => __( 'Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'content', [
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'badge_label',
			[
				'label'       => __( 'Badge Label', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'url', [
				'label'         => esc_html__( 'Card URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);


		$this->add_control(
			'event_date_1',
			[
				'label'       => __( 'Event Date (Event Layout Only)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '24',
				'label_block' => true
			]
		);


		$this->add_control(
			'event_date_2',
			[
				'label'       => __( 'Event Month (Event Layout Only)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'JUN',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		$target   = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow']    ? ' rel="nofollow"'  : '';
		$link     = 'href="'. esc_url( $settings['url']['url'] ) .'"' . $target . $nofollow;
		
		if( 'basic' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-body">
						<div class="flex-grow-1">
							<div class="h3">'. $settings['title'] .'</div>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {
			
				echo '
					<div class="card card-body">
						<div class="flex-grow-1">
							<div class="h3">'. $settings['title'] .'</div>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-1' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-body">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'	
						</div>			
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</a>
				';

			} else {

				echo '
					<div class="card card-body">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'	
						</div>			
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</div>
				';

			}
			

		} elseif( 'icon-1-dark' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-body bg-primary text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</a>
				';

			} else {

				echo '
					<div class="card card-body bg-primary text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</div>
				';

			}			

		} elseif( 'icon-1-primary-2' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-body bg-primary-2 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</a>
				';

			} else {

				echo '
					<div class="card card-body bg-primary-2 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</div>
				';

			}			

		} elseif( 'icon-1-primary-3' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-body bg-primary-3 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</a>
				';

			} else {

				echo '
					<div class="card card-body bg-primary-3 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'	
						</div>				
						<h4>'. $settings['title'] .'</h4>	
						'. $settings['content'] .'
					</div>
				';

			}			

		} elseif( 'icon-2' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-icon-2 card-body justify-content-between shadow-3d hover-bg-primary-3">
						<div class="icon-round mb-3 mb-md-4 icon bg-primary-2">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</a>
				';

			} else {

				echo '
					<div class="card card-icon-2 card-body justify-content-between">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary-2' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</div>
				';

			}
			
		} elseif( 'icon-2-tited-hover' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-icon-2 card-body justify-content-between hover-shadow-3d">
						<div class="icon-round mb-3 mb-md-4 icon bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</a>
				';

			} else {

				echo '
					<div class="card card-icon-2 card-body justify-content-between hover-shadow-3d">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</div>
				';

			}
			
		} elseif( 'icon-2-tited-hover-primary' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</a>
				';

			} else {

				echo '
					<div class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</div>
				';

			}
			
		} elseif( 'icon-2-tited-hover-primary-2' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary-2 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</a>
				';

			} else {

				echo '
					<div class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary-2 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</div>
				';

			}
			
		} elseif( 'icon-2-tited-hover-primary-3' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary-3 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</a>
				';

			} else {

				echo '
					<div class="card card-icon-2 card-body justify-content-between hover-shadow-3d bg-primary-3 text-light">
						<div class="icon-round mb-3 mb-md-4 icon bg-white">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-white' ) .'
						</div>
						<h5 class="mb-0">'. $settings['title'] .'</h5>
					</div>
				';

			}
			
		} elseif( 'icon-3' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-icon-3 card-body justify-content-between">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {
			
				echo '
					<div class="card card-icon-3 card-body justify-content-between">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-3-tilted' == $settings['layout'] ) {
		
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {

				echo '
					<div class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-3-tilted-alt' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-icon-3 card-body justify-content-between hover-shadow-3d rotate-right">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {
			
				echo '
					<div class="card card-icon-3 card-body justify-content-between hover-shadow-3d rotate-right">
						<div class="icon-round mb-3 mb-md-4 bg-primary">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary' ) .'
					 	</div>
						<span class="badge badge-primary text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-3-tilted-primary-2' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary-2">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary-2' ) .'
					 	</div>
						<span class="badge bg-primary-2 text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {
			
				echo '
					<div class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary-2">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary-2' ) .'
					 	</div>
						<span class="badge bg-primary-2 text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-3-tilted-primary-3' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
				
				echo '
					<a '. $link .' class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary-3">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary-3' ) .'
					 	</div>
						<span class="badge bg-primary-3 text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</a>
				';
				
			} else {
			
				echo '
					<div class="card card-icon-3 card-body justify-content-between shadow-3d rotate-left">
						<div class="icon-round mb-3 mb-md-4 bg-primary-3">
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon bg-primary-3' ) .'
					 	</div>
						<span class="badge bg-primary-3 text-light">'. $settings['badge_label'] .'</span>
						<div>
							<h3>'. $settings['title'] .'</h3>
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'icon-4' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-sm card-body flex-row align-items-center hover-shadow-3d">					
						'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary', $settings['image']['url'] ) .'	             	
	                  	<div class="ml-3">
	                  		<h5 class="mb-0">'. $settings['title'] .'</h5>
		                    '. $settings['content'] .'
	                  	</div>
	                </a>
				';

			} else {

				echo '
					<div class="card card-sm card-body flex-row align-items-center">
						'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary', $settings['image']['url'] ) .'	             	
	                  	<div class="ml-3">
	                  		<h5 class="mb-0">'. $settings['title'] .'</h5>
		                    '. $settings['content'] .'
	                  	</div>
	                </div>
				';

			} 

		} elseif( 'icon-4-primary-2' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<a '. $link .' class="card card-sm card-body flex-row align-items-center hover-shadow-3d">					
						'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary-2', $settings['image']['url'] ) .'	             	
	                  	<div class="ml-3">
	                  		<h5 class="mb-0">'. $settings['title'] .'</h5>
		                    '. $settings['content'] .'
	                  	</div>
	                </a>
				';

			} else {

				echo '
					<div class="card card-sm card-body flex-row align-items-center">
						'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary-2', $settings['image']['url'] ) .'	             	
	                  	<div class="ml-3">
	                  		<h5 class="mb-0">'. $settings['title'] .'</h5>
		                    '. $settings['content'] .'
	                  	</div>
	                </div>
				';

			} 

		} elseif( 'icon-4-hover' == $settings['layout'] ) {

			echo '
				<a '. $link .' class="card card-sm card-body flex-row align-items-center hover-bg-primary-2 hover-shadow-3d">
					'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary-2', $settings['image']['url'] ) .'             	
                  	<div class="ml-3">
                  		<h5 class="mb-0">'. $settings['title'] .'</h5>
	                    '. $settings['content'] .'
                  	</div>
                </a>
			';

		} elseif( 'customer-1' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<div class="card card-customer-1 card-body align-items-start">
					  <div class="mb-4 mb-md-5">
					    '. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary-2' ) .'
					  </div>
					  <div class="mb-3 mb-md-4">
					    '. $settings['content'] .'
					  </div>
					  <a '. $link .'>'. $settings['badge_label'] .'</a>
					</div>
				';

			} else {

				echo '
					<div class="card card-customer-1 card-body align-items-start">
					  <div class="mb-4 mb-md-5">
					    '. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-lg bg-primary-2' ) .'
					  </div>
					  <div class="mb-3 mb-md-4">
					    '. $settings['content'] .'
					  </div>
					</div>
				';

			}

		} elseif( 'customer-2' == $settings['layout'] ) {

			if( !empty( $settings['url']['url'] ) ) {

				echo '
					<div class="card">
	                  	<a '. $link .'>
	                  		'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
	                  	</a>
	                  	<div class="card-body align-items-start">
	                    	<div class="mb-3">
	                    		'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md' ) .'
	                    	</div>
		                     '. $settings['content'] .'
	                  	</div>
	                </div>
				';

			} else {

				echo '
					<div class="card">
                    	'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
	                  	<div class="card-body align-items-start">
	                    	<div class="mb-3">
	                    		'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md' ) .'
	                    	</div>
		                     '. $settings['content'] .'
	                  	</div>
	                </div>
				';

			}
 
		} elseif( 'customer-3' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
			
				echo '
					<a '. $link .' class="card card-body">
					  	<div class="d-flex mb-3">
					    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
					    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
					    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
					    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
					    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
					  	</div>
					  	<div class="my-md-2 flex-grow-1">
					        <h4>'. $settings['title'] .'</h4>
					  	</div>
					  	<div class="avatar-author align-items-center">
					  		'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar' ) ) .'
					    	<div class="ml-2">
					          	'. $settings['content'] .'
					        </div>
					  	</div>
					</a>
				';

			} else {
						
				echo '
					<div class="card card-body">
	                  	<div class="d-flex mb-3">
	                    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
	                    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
	                    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
	                    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
	                    	'. tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' ) .'
	                  	</div>
	                  	<div class="my-md-2 flex-grow-1">
		                    <h4>'. $settings['title'] .'</h4>
	                  	</div>
	                  	<div class="avatar-author align-items-center">
	                  		'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar' ) ) .'
	                    	<div class="ml-2">
		                      	'. $settings['content'] .'
		                    </div>
	                  	</div>
	                </div>
				';
			
			}

		} elseif( 'customer-4' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
						
				echo '
					<a '. $link .' class="card card-body">
						<div class="mb-3 mb-md-4">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg' ) ) .'
						</div>
						<div class="flex-grow-1 pt-md-3">
							<h4>'. $settings['title'] .'</h4>
						</div>
						<div class="avatar-author d-block mb-2">
							'. $settings['content'] .'
						</div>
					</a>
				';

			} else {
						
				echo '
					<div class="card card-body">
						<div class="mb-3 mb-md-4">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg' ) ) .'
						</div>
						<div class="flex-grow-1 pt-md-3">
							<h4>'. $settings['title'] .'</h4>
						</div>
						<div class="avatar-author d-block mb-2">
							'. $settings['content'] .'
						</div>
					</div>
				';
			
			}

		} elseif( 'customer-5' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
									
				echo '
					<a '. $link .' class="card card-body flex-row py-4">
						'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg' ) ) .'
						<div class="ml-3">
							<h4>'. $settings['title'] .'</h4>
							<div class="avatar-author d-block">
								'. $settings['content'] .'
							</div>
						</div>
					</a>
				';

			} else {
						
				echo '
					<div class="card card-body flex-row py-4">
						'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg' ) ) .'
						<div class="ml-3">
							<h4>'. $settings['title'] .'</h4>
							<div class="avatar-author d-block">
								'. $settings['content'] .'
							</div>
						</div>
					</div>
				';
				
			}

		} elseif( 'customer-7' == $settings['layout'] ) {
			
			if( !empty( $settings['url']['url'] ) ) {
												
				echo '
					<a '. $link .' class="card card-body">
						<div class="flex-grow-1 mb-3">
							<p class="lead">'. $settings['title'] .'</p>
						</div>
						<div class="avatar-author align-items-center">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar' ) ) .'
							<div class="ml-2">
								'. $settings['content'] .'
							</div>
						</div>
					</a>
				';

			} else {
						
				echo '
					<div class="card card-body">
						<div class="flex-grow-1 mb-3">
							<p class="lead">'. $settings['title'] .'</p>
						</div>
						<div class="avatar-author align-items-center">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar' ) ) .'
							<div class="ml-2">
								'. $settings['content'] .'
							</div>
						</div>
					</div>
				';
			
			}

		} elseif( 'customer-8' == $settings['layout'] ) {

			echo '
				<a '. $link .' class="card card-article-wide flex-md-row no-gutters hover-shadow-3d">
						<div class="col-md-5 col-lg-6">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
						</div>
						<div class="card-body d-flex flex-column justify-content-between col-auto p-4 p-lg-5">
						<div>
							'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md mb-4' ) .'
							'. $settings['content'] .'
						</div>
						<p class="lead mb-0 text-primary font-weight-bold">'. $settings['badge_label'] .'</p>
					</div>
				</a>
			';

		} elseif( 'large-image-and-text' == $settings['layout'] ) {

			echo '
				<div>
					<a '. $link .'>
						'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded mb-3' ) ) .'
					</a>
					<a '. $link .'>
						'. $settings['content'] .'
					</a>
				</div>
			';

		} elseif( 'large-image-and-text-overlay' == $settings['layout'] ) {

			echo '
				<div>
					<a '. $link .' class="card position-relative text-light border-0 d-flex justify-content-end overlay">
						'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded width-100' ) ) .'
						<div class="position-absolute p-3 layer-2">
							'. $settings['content'] .'
						</div>
					</a>
				</div>
			';

		} elseif( 'event' == $settings['layout'] ) {

			echo '
				<a class="card hover-shadow-sm" '. $link .'>
					'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
					<div class="card-body d-flex flex-column">
						<div class="d-flex">
							<div class="text-right">
								<div class="h4 mb-0 text-danger">'. $settings['event_date_1'] .'</div>
								<div class="h4 mb-0 text-uppercase">'. $settings['event_date_2'] .'</div>
							</div>
							<div class="ml-3">
								 <div class="badge badge-primary mb-2">'. $settings['badge_label'] .'</div>
								'. $settings['content'] .'
							</div>
						</div>
					</div>
				</a>
			';

		} elseif( 'event-2' == $settings['layout'] ) {

			echo '
				<div>
       				<a '. $link .'">
       				 	'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'rounded' ) ) .'
        			</a>
    				<div class="my-3">
          				<span class="badge badge-primary-2">'. $settings['badge_label'] .'</span>
        			</div>
        			<h3>'. $settings['title'] .'</h3>
        			'. $settings['content'] .'
      			</div>
			';

		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Card_Block() );