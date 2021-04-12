<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Processes_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-processes-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Processes', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-call-to-action';
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
				'label'   => __( 'Processes Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'basic',
				'label_block' => true,
				'options' => [
					'basic'          	=> esc_html__( 'Basic', 'tr-framework' ),
					'vertical'			=> esc_html__( 'Vertical', 'tr-framework' ),
					'vertical-alt'		=> esc_html__( 'Vertical Alt', 'tr-framework' ),
					'numbered'			=> esc_html__( 'Numbered', 'tr-framework' ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'processes_content', [
				'label' => __( 'Content', 'tr-framework' )
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Process Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Create your account',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_description', [
				'label'       => __( 'Process Description', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'Voluptatem accusantium doloremque laudantium, totam rem aperiam.',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'icon_bg', [
				'label'   => __( 'Icon Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-primary',
				'label_block' => true,
				'options' => [
					'bg-primary'          	=> esc_html__( 'Primary', 'tr-framework' ),
					'bg-primary-2'			=> esc_html__( 'Primary 2', 'tr-framework' ),
					'bg-primary-3'			=> esc_html__( 'Primary 3', 'tr-framework' ),
				],
			]
		);


		$this->add_control(
			'list', [
				'label'   => __( 'Processes Items', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title'       => 'Create your account',
						'item_description' => 'Voluptatem accusantium doloremque laudantium, totam rem aperiam.',
						'icon_bg' 		   => 'bg-primary'
					]
				],
				'title_field' => __( 'Processes Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings                = $this->get_settings_for_display();
		$user_selected_animation = (bool) $settings['_animation'];
		$animation               = ( $user_selected_animation ) ? false : 'data-aos="fade-up"';
		$i 						 = 1;
		
		if( 'vertical-alt' == $settings['layout'] ) {
			
			$animation = ( $user_selected_animation ) ? false : 'data-aos="fade-left"';
			
			echo '<div class="row o-hidden o-lg-visible"><div class="col d-flex flex-column align-items-center"><ol class="process-vertical">';
			
			foreach( $settings['list'] as $item ){
			
				echo '
					<li '. $animation  .' data-aos-delay="'. $i .'00">
						<div class="process-circle '. $item['icon_bg'] .'"></div>
						<div>
							<span class="text-small text-muted">'. $item['item_title'] .'</span>
							<h5 class="mb-0">'. $item['item_description'] .'</h5>
						</div>
					</li>
				';
				
				$i++;
				
			}
			
			echo '</ol></div></div>';
			
		} elseif( 'vertical' == $settings['layout'] ) {

			echo '<div class="row">';
			
			foreach( $settings['list'] as $item ){
				echo '
					<div class="d-flex mb-4" '. $animation  .' data-aos-delay="'. $i .'00">
						<div class="process-circle '. $item['icon_bg'] .'"></div>
						<div class="ml-3">						
							<h5>'. $item['item_title'] .'</h5>
							'. $item['item_description'] .'
						</div>
					</div>
				';
				$i++;
			}
			
			echo '</div>';
			
		} elseif( 'basic' == $settings['layout'] ) {

			echo '<div class="row">';
			
			foreach( $settings['list'] as $item ){
				echo '
					<div class="col-md-4" '. $animation  .' data-aos-delay="'. $i .'00">
						<div class="process-circle '. $item['icon_bg'] .' mb-3"></div>
						<h4>'. $item['item_title'] .'</h4>
			            '. $item['item_description'] .'
					</div>
				';
				$i++;
			}
			
			echo '</div>';
			
		} elseif( 'numbered' == $settings['layout'] ) {

			echo '<div class="row text-center">';
			
			foreach( $settings['list'] as $item ){
				echo '
					<div class="col-md-4 mb-3 mb-md-0" '. $animation  .' data-aos-delay="'. $i .'00">
						<div class="px-xl-2">
							<div class="process-circle '. $item['icon_bg'] .' mb-3 d-inline-flex">'. $i .'</div>
							<h4>'. $item['item_title'] .'</h4>
				            '. $item['item_description'] .'
						</div>
					</div>
				';
				$i++;
			}
			
			echo '</div>';
			
		}
			
	}

	protected function _content_template() {
		?>	

			<?php $i = 1; ?>

			<# if ( 'vertical-alt' == settings.layout ) { #>
				
				<div class="row o-hidden o-lg-visible"><div class="col d-flex flex-column align-items-center"><ol class="process-vertical">
				
				<# _.each( settings.list, function( item ) { #>
					<li data-aos="fade-left">
						<div class="process-circle bg-primary"></div>
						<div>
							<span class="text-small text-muted">{{{ item.item_title }}}</span>
							<h5 class="mb-0">{{{ item.item_description }}}</h5>
						</div>
					</li>
				<# }); #>
				
				</ol></div></div>				
					
			<# } else if ( 'vertical' == settings.layout ) { #>

				<div class="row">

				<# _.each( settings.list, function( item ) { #>
					<div class="d-flex mb-4" data-aos="fade-up" data-aos-delay="NaN">
						<div class="process-circle {{{ item.icon_bg }}}"></div>
						<div class="ml-3">						
							<h5>{{{ item.item_title }}}</h5>
							{{{ item.item_description }}}
						</div>
					</div>
				<# }); #>

				</div>

			<# } else if ( 'basic' == settings.layout ) { #>

				<div class="row">

				<# _.each( settings.list, function( item ) { #>
					<div class="col-md-4" data-aos="fade-left">
						<div class="process-circle {{{ item.icon_bg }}} mb-3"></div>
						<h4>{{{ item.item_title }}}</h4>
			            {{{ item.item_description }}}
					</div>
				<# }); #>

				</div>

			<# } else if ( 'numbered' == settings.layout ) { #>

				<div class="row text-center">

				<# _.each( settings.list, function( item ) { #>
					<div class="col-md-4 mb-3 mb-md-0">
						<div class="px-xl-2">
							<div class="process-circle {{{ item.icon_bg }}} mb-3 d-inline-flex"><?php echo $i; ?></div>
							<h4>{{{ item.item_title }}}</h4>
				            {{{ item.item_description }}}
						</div>
					</div>

					<?php $i++; ?>

				<# }); #>

				</div>

			<# } #>
		
		<?php
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Processes_Block() );