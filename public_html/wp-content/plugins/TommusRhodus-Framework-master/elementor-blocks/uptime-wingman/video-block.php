<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Video_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-video-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Video Block', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
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
			'layout_section', [
				'label' => __( 'Video Block Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Video Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block' 		=> esc_html__( 'Block with Background Image', 'tr-framework' ),
					'modal' 		=> esc_html__( 'Modal with No Image', 'tr-framework' ),
					'button_modal' 	=> esc_html__( 'Button Modal with No Image', 'tr-framework' )
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Video Block', 'tr-framework' ),
			]
		);
		
		$this->add_control(
			'image', [
				'label'      => __( 'Video Cover Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'video', [
				'label'       => __( 'Video URL', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'button_label',
			[
				'label'       => __( 'Button Label', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Play Trailer',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		$result    = false;
		$cache_key = false;
		
		if( $settings['video'] ){
					
			$cache_key = 'tr-oembed-' . md5( $settings['video'] );
			$result    = get_transient( $cache_key );

			if( !$result ){
			
				// Cache is empty, resolve oEmbed
				$result = wp_oembed_get( $settings['video'], array( 'video-block' => 'true' ) );
				
				// Cache 4 hours for standard and 5 min for failed
				$ttl = $result ? 14400 : 300;
				
				set_transient( $cache_key, $result, $ttl );
				
			}
		
		}
		
		if( 'block' == $settings['layout'] ){
		
			echo '
				<div class="video-cover rounded">
				    '. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'bg-image' ) ) .'
				    <div class="video-play-icon">
				        <i class="icon-controller-play"></i>
				    </div>
				    <div class="embed-responsive embed-responsive-16by9">'. $result .'</div>
				</div>
			';
		
		} elseif( 'modal' == $settings['layout'] ){
		
			echo '
				<div class="video-play-icon" data-toggle="modal" data-target="#video-'. esc_attr( $cache_key ) .'">
				    <i class="icon-controller-play"></i>
				</div>
				
				<div class="modal fade" id="video-'. esc_attr( $cache_key ) .'" tabindex="-1" aria-hidden="true">
				    <div class="modal-dialog modal-lg modal-center-viewport">
				        <div class="modal-content">
				            <div class="embed-responsive embed-responsive-16by9">'. $result .'</div>
				        </div>
				    </div>
				</div>
			';
		
		} elseif( 'button_modal' == $settings['layout'] ){
		
			echo '
				<a href="#" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#video-'. esc_attr( $cache_key ) .'">
					<i class="icon-controller-play"></i> '. $settings['button_label'] .'
				</a>
				
				<div class="modal fade" id="video-'. esc_attr( $cache_key ) .'" tabindex="-1" aria-hidden="true">
				    <div class="modal-dialog modal-lg modal-center-viewport">
				        <div class="modal-content">
				            <div class="embed-responsive embed-responsive-16by9">'. $result .'</div>
				        </div>
				    </div>
				</div>
			';
		
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Video_Block() );