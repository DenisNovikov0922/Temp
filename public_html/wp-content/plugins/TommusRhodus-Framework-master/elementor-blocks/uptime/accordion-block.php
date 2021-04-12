<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Accordion_Block extends Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-accordion-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Accordion', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-accordion';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Accordion Item', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout', [
				'label'   => __( 'Panel Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'panel',
				'label_block' => true,
				'options' => [
					'panel'          	=> esc_html__( 'Basic', 'tr-framework' ),
					'inline'         	=> esc_html__( 'Inline', 'tr-framework' ),
					'inline_open'		=> esc_html__( 'Inline Open', 'tr-framework' ),
				],
			]
		);

		$this->add_control(
			'item_title', [
				'label'       => __( 'Accordion Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$this->add_control(
			'item_content', [
				'label'       => __( 'Accordion Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings   = $this->get_settings_for_display();
		$title      = $settings['item_title'];
		$attr_title = sanitize_title_with_dashes( $title );
		
		$user_selected_animation = (bool) $settings['_animation'];
		
		if( !$user_selected_animation ){
			echo '<div data-aos="fade-up" data-aos-delay="NaN">';
		}

		if( 'inline' == $settings['layout'] ) {
		
			echo '
				<div class="border-bottom pb-3 mb-3">
					<div data-target="#'. sanitize_file_name( $attr_title ) .'" class="accordion-panel-title" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="'. sanitize_file_name( $attr_title ) .'">
						<span class="h6 mb-0">'. $title .'</span>
						<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19 11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11Z" fill="#212529" />
							<path d="M13 19L13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5L11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" fill="#212529" />
						</svg>
					</div>
					<div class="collapse" id="'. sanitize_file_name( $attr_title ) .'">
						<div class="pt-3">
							<p class="mb-0">
								'. $settings['item_content'] .'
							</p>
						</div>
					</div>
				</div>
			';

		} elseif( 'inline_open' == $settings['layout'] ) {
		
			echo '
				<div class="border-bottom pb-3 mb-3">
					<div data-target="#'. sanitize_file_name( $attr_title ) .'" class="accordion-panel-title" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="'. sanitize_file_name( $attr_title ) .'">
						<span class="h6 mb-0">'. $title .'</span>
						<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19 11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11Z" fill="#212529" />
							<path d="M13 19L13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5L11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" fill="#212529" />
						</svg>
					</div>
					<div class="collapse show" id="'. sanitize_file_name( $attr_title ) .'">
						<div class="pt-3">
							<p class="mb-0">
								'. $settings['item_content'] .'
							</p>
						</div>
					</div>
				</div>
			';

		} else {

			echo '
				<div class="card mb-2 card-sm card-body hover-shadow-sm">
				
					<div data-target="#'. sanitize_file_name( $attr_title ) .'" class="accordion-panel-title" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="'. sanitize_file_name( $attr_title ) .'">
						<span class="h6 mb-0">'. $title .'</span>
						<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19 11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11Z" fill="#212529" />
							<path d="M13 19L13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5L11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" fill="#212529" />
						</svg>
					</div>
					
					<div class="collapse" id="'. sanitize_file_name( $attr_title ) .'">
						<div class="pt-3">
							<p class="mb-0">'. $settings['item_content'] .'</p>
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
		
			<# var $title = <?php echo rand( 0, 30000 ); ?>; #>

			<# if ( 'vertical-alt' == settings.layout ) { #>
				
				<div class="border-bottom pb-3 mb-3">
					<div data-target="#{{ $title }}" class="accordion-panel-title" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="{{ $title }}">
						<span class="h6 mb-0">{{{ settings.item_title }}}</span>
						<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19 11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11Z" fill="#212529" />
							<path d="M13 19L13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5L11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" fill="#212529" />
						</svg>
					</div>
					<div class="collapse" id="{{ $title }}">
						<div class="pt-3">
							<p class="mb-0">
								{{{ settings.item_content }}}
							</p>
						</div>
					</div>
				</div>

			<# } else { #>

				<div class="card mb-2 card-sm card-body hover-shadow-sm">
				
					<div data-target="#{{ $title }}" class="accordion-panel-title" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="{{ $title }}">
						<span class="h6 mb-0">{{{ settings.item_title }}}</span>
						<svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M19 11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11Z" fill="#212529" />
							<path d="M13 19L13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5L11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" fill="#212529" />
						</svg>
					</div>
					
					<div class="collapse" id="{{ $title }}">
						<div class="pt-3">
							<p class="mb-0">{{{ settings.item_content }}}</p>
						</div>
					</div>
				</div>

			<# } #>
		
		<?php
	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Accordion_Block() );