<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Card_Image_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-card-image-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Card', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
	}
	
	public function get_categories() {
		return [ 'wingman-elements' ];
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'layout_section', [
				'label' => __( 'Image Card Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Card Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text-outside-card',
				'options' => [
					'text-outside-card' => esc_html__( 'Image Top, Text Outside Card Below', 'tr-framework' ),
					'text-inside-card'  => esc_html__( 'Image Top, Text Inside Card Below', 'tr-framework' ),
					'left-image'        => esc_html__( 'Image Left, Text Right', 'tr-framework' ),
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Image Card Content', 'tr-framework' ),
			]
		);

		$this->add_control(
			'image', [
				'label'      => __( 'Card Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);
		
		$this->add_control(
			'url', [
				'label'         => esc_html__( 'Card Image URL', 'tr-framework' ),
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
			'content',
			[
				'label'       => __( 'Content', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();
		$target   = $settings['url']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['url']['nofollow']    ? ' rel="nofollow"'  : '';
		$link     = 'href="'. esc_url( $settings['url']['url'] ) .'"' . $target . $nofollow;
		
		if( 'text-inside-card' == $settings['layout'] ){
		
			echo '
				<div class="card card-lg">
				    <a '. $link .'>
				        '. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'card-img-top' ) ) .'
				    </a>
				    <div class="card-body">'. $settings['content'] .'</div>
				</div>
			';
		
		} elseif( 'left-image' == $settings['layout'] ){
		
			echo '
				<ul class="feature-list feature-list-lg">
				    <li>
				        <div class="media align-items-center">
				        	<a '. $link .'>
				        	    '. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'avatar avatar-lg mr-4' ) ) .'
				        	</a>
				            <div class="media-body">'. $settings['content'] .'</div>
				        </div>
				    </li>
				</ul>
			';
			
		} else {
		
			echo '
				<ul class="row feature-list feature-list-sm">
					<li class="col-12">
					
						<a '. $link .' class="card">
							'. wp_get_attachment_image( $settings['image']['id'], 'large', 0, array( 'class' => 'img-fluid rounded' ) ) .'
						</a>
						
						'. $settings['content'] .'
						
					</li>
				</ul>
			';
		
		}
		
	}

	protected function _content_template() {
		?>
		
		<# if ( 'text-inside-card' == settings.layout ) { #>
		
			<div class="card card-lg">
			    <a href="{{ settings.url.url }}">
			        <img src="{{ settings.image.url }}" alt="" class="card-img-top">
			    </a>
			    <div class="card-body">{{{ settings.content }}}</div>
			</div>
			
		<# } else if ( 'left-image' == settings.layout ) { #>
			
			<ul class="feature-list feature-list-lg">
			    <li>
			        <div class="media align-items-center">
			        	<a href="{{ settings.url.url }}">
							<img src="{{ settings.image.url }}" alt="" class="avatar avatar-lg mr-4">
			        	</a>
			            <div class="media-body">{{{ settings.content }}}</div>
			        </div>
			    </li>
			</ul>
			
		<# } else { #>
		
			<ul class="row feature-list feature-list-sm">
				<li class="col-12">
					<a href="{{ settings.url.url }}" class="card">
						<img src="{{ settings.image.url }}" alt="" class="img-fluid rounded">
					</a>
					{{{ settings.content }}}
				</li>
			</ul>
		
		<# } #>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Card_Image_Block() );