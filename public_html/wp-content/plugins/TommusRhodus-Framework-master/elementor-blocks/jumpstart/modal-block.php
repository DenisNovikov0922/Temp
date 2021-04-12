<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Modal_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-modal-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Modal', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'jumpstart-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Modal Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'modal_size', [
				'label'   => __( 'Modal Size', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'modal-basic',
				'label_block' => true,
				'options' => [
					'modal-basic'          	=> esc_html__( 'Regular', 'tr-framework' ),
					'modal-lg'         		=> esc_html__( 'Large', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'modal_position', [
				'label'   => __( 'Modal Position', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'modal-basic',
				'label_block' => true,
				'options' => [
					'modal-basic'          	=> esc_html__( 'Top', 'tr-framework' ),
					'modal-dialog-centered'	=> esc_html__( 'Centered', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'modal_bg_colour', [
				'label'   => __( 'Modal Background Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-basic',
				'label_block' => true,
				'options' => [
					'bg-basic'          	=> esc_html__( 'White Background', 'tr-framework' ),
					'bg-primary'          	=> esc_html__( 'Primary Background', 'tr-framework' ),
					'bg-primary-2'          => esc_html__( 'Primary 2 Background', 'tr-framework' ),
					'bg-primary-3'          => esc_html__( 'Primary 3 Background', 'tr-framework' ),
					'bg-dark'          		=> esc_html__( 'Dark Background', 'tr-framework' ),
					'bg-success'			=> esc_html__( 'Success Background', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'modal_content_colour', [
				'label'   => __( 'Modal Content Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text-basic',
				'label_block' => true,
				'options' => [
					'text-basic'          	=> esc_html__( 'Regular Text', 'tr-framework' ),
					'text-white'          	=> esc_html__( 'White Text', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'modal_bg_image', [
				'label'      => __( 'BG/Side Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'modal_style', [
				'label'   => __( 'Image Style', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'background-image',
				'label_block' => true,
				'options' => [					
					'background-image'          	=> esc_html__( 'Use Image as Background', 'tr-framework' ),
					'side-image'          			=> esc_html__( 'Show Image at side of modal', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'modal_content_padding', [
				'label'   => __( 'Modal Content Padding', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'm-xl-4 m-3',
				'label_block' => true,
				'options' => [					
					'm-xl-6 m-3'          	=> esc_html__( 'Large Padding', 'tr-framework' ),
					'm-xl-4 m-3'          	=> esc_html__( 'Medium Padding', 'tr-framework' ),
					'm-3'          			=> esc_html__( 'Regular Padding', 'tr-framework' ),
					'm-2'          			=> esc_html__( 'Small Padding', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'button_label', [
				'label'       => __( 'Button Label', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Launch Modal',
				'label_block' => true
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
			'content', [
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->add_control(
			'modal_id', [
				'label'       => __( 'Unique ID - for example, "sign-up-modal".', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'modal-'. rand(0, 10000),
				'label_block' => true
			]
		);

		$this->add_control(
			'show_button', [
				'label' 		=> __( 'Show Button (use this if you wish to launch the modal from a custom link)', 'tr-framework' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'tr-framework' ),
				'label_off' 	=> __( 'Hide', 'tr-framework' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);	

		$this->add_control(
			'show_close', [
				'label' 		=> __( 'Show Close Button', 'tr-framework' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'tr-framework' ),
				'label_off' 	=> __( 'Hide', 'tr-framework' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);	

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];

		if( 'yes' == $settings['show_button'] ) {
			echo '
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#'. $settings['modal_id'] .'">
					'. $settings['button_label'] .'
				</button>
			';
		}

		if( 'background-image' == $settings['modal_style'] ) {

			if( !empty( $settings['modal_bg_image']['id'] )) {
				$bg_image = wp_get_attachment_image( $settings['modal_bg_image']['id'], 'large', 0, array( 'class' => 'bg-image blend-mode-multiply rounded opacity-50' ) );
			} else {
				$bg_image = '';
			}

			echo '			
				<div class="modal fade" id="'. $settings['modal_id'] .'" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog '. $settings['modal_size'] .' '. $settings['modal_position'] .'" role="document">
						<div class="modal-content '. $settings['modal_bg_colour'] .' '. $settings['modal_content_colour'] .'">
							'. $bg_image .'';

							if( 'yes' == $settings['show_close'] ) { 

								echo '
								<div class="modal-header border-0 pb-0">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">';
											if( 'text-white' == $settings['modal_content_colour'] ) {
											echo tommusrhodus_svg_icons_pluck( 'Interface Close', 'icon icon-sm bg-white' );
											} else {
												echo tommusrhodus_svg_icons_pluck( 'Interface Close', 'icon icon-sm bg-dark' );
											}
										echo '
										</span>
									</button>
								</div>';
							}

							echo '
							<div class="w-100">';

								if( '0' !== $settings['icon'] && 'text-white' == $settings['modal_content_colour'] ) {
									echo '<div class="text-center pt-4"><div class="d-inline-block mb-4 p-3 p-md-4 rounded-circle bg-white">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md bg-white' ) .'</div></div>';
								} elseif( '0' !== $settings['icon'] ) {
									echo '<div class="text-center pt-4"><div class="d-inline-block mb-4 p-3 p-md-4 rounded-circle bg-primary-alt">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md bg-primary' ) .'</div></div>';
								}

								

								echo '
							</div>	
							<div class="modal-body">
								<div class="'. $settings['modal_content_padding'] .'">';								

								echo do_shortcode( $settings['content'] );

								echo '
								</div>
							</div>
						</div>
					</div>
				</div>
			';

		} elseif( 'side-image' == $settings['modal_style'] && !empty( $settings['modal_bg_image']['id'] ) ) {

			if( !empty( $settings['modal_bg_image']['id'] )) {
				$bg_image = wp_get_attachment_image( $settings['modal_bg_image']['id'], 'large', 0, array( 'class' => 'rounded-left img-fluid' ) );
			} else {
				$bg_image = '';
			}

			echo '			
				<div class="modal fade" id="'. $settings['modal_id'] .'" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog '. $settings['modal_size'] .' '. $settings['modal_position'] .'" role="document">
						<div class="modal-content row flex-row no-gutters border-0 '. $settings['modal_bg_colour'] .' '. $settings['modal_content_colour'] .'">
							<div class="col-lg-6">
								'. $bg_image .'
							</div>
							<div class="col-lg-6 d-flex flex-column">';

								if( 'yes' == $settings['show_close'] ) { 

									echo '
									<div class="modal-header text-center border-0 p-0">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">';
												if( 'text-white' == $settings['modal_content_colour'] ) {
												echo tommusrhodus_svg_icons_pluck( 'Interface Close', 'icon icon-sm bg-white' );
												} else {
													echo tommusrhodus_svg_icons_pluck( 'Interface Close', 'icon icon-sm bg-dark' );
												}
											echo '
											</span>
										</button>
									</div>';
								}

								echo '

								<div class="modal-body p-4 p-md-5 text-center flex-fill d-flex flex-column justify-content-center">						
									<div class="'. $settings['modal_content_padding'] .'">';

									if( '0' !== $settings['icon'] && 'text-white' == $settings['modal_content_colour'] ) {
										echo '<div class="icon-round icon-round-lg bg-white mx-auto mb-4">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md bg-white' ) .'</div>';
									} elseif( '0' !== $settings['icon'] ) {
										echo '<div class="icon-round icon-round-lg bg-primary mx-auto mb-4">'. tommusrhodus_svg_icons_pluck( $settings['icon'], 'icon icon-md bg-primary' ) .'</div>';
									}

									echo do_shortcode( $settings['content'] );

									echo '
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';

		}
		
	}

	protected function _content_template() {
		?>

			<# if ( 'yes' == settings.show_button ) { #>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{ settings.modal_id }}">
				{{{ settings.button_label }}}
			</button>
			<# } #>
			<div class="modal fade" id="{{ settings.modal_id }}" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog {{ settings.modal_size }} {{ settings.modal_position }}" role="document">
					<div class="modal-content">
						<div class="modal-body">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<?php echo tommusrhodus_svg_icons_pluck( 'Modal close', 'icon bg-dark' ); ?>	
							</button>
							<div class="m-xl-4 m-3">
								{{{ settings.content }}}
							</div>
						</div>
					</div>
				</div>
			</div>	
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Modal_Block() );