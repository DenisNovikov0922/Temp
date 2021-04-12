<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Graphic_Tabs_Block extends Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-graphic-tabs-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Graphic Tabs', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-blockquote';
	}
	
	public function get_categories() {
		return [ 'wingman-elements' ];
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
				'label' => __( 'Tabs Layout', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'layout', [
				'label'   => __( 'Tabs Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'non-numbered',
				'options' => [
					'non-numbered' => esc_html__( 'Non-Numbered Tabs', 'tr-framework' ),
					'numbered'     => esc_html__( 'Numbered Tabs', 'tr-framework' )
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Tabs Items', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Tab Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_text',
			[
				'label'       => __( 'Tab Text', 'tr-framework' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_image',
			[
				'label'      => __( 'Tab Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Carousel Items', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title' => __( 'Tab Title', 'tr-framework' ),
						'item_text'  => __( 'Tab Text', 'tr-framework' ),
						'item_image' => __( 'Tab Image', 'tr-framework' )
					]
				],
				'title_field' => __( 'Tab Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings = $this->get_settings_for_display();
		$i = 0;
	?>
		
		<?php if( is_array( $settings['list'] ) ) : ?>
		
			<div class="row justify-content-around align-items-center">
				
			    <div class="col-12 col-md-6 col-lg-5 order-md-2">
					
					<?php if( 'non-numbered' == $settings['layout'] ) : ?>
					
				        <ul class="nav" id="myTab" role="tablist">
							
							<?php foreach( $settings['list'] as $item ) : ?>
								
								<?php 
									$i++; 
									$class    = ( 1 == $i ) ? 'active' : false;
									$selected = ( 1 == $i ) ? 'true' : 'false';
								?>
								
					            <li>
					                <a class="<?php echo $class; ?> card" id="tab-<?php echo $i; ?>" data-toggle="tab" href="#tab-content-<?php echo $i; ?>" role="tab" aria-controls="tab-<?php echo $i; ?>" aria-selected="<?php echo $selected; ?>">
					                    <div class="card-body">
					                        <h5><?php echo $item['item_title']; ?></h5>
					                        <?php echo wpautop( $item['item_text'] ); ?>
					                    </div>
					                </a>
					            </li>
							
							<?php endforeach; ?>
							
				        </ul>
					
					<?php else : ?>
					
						<ul class="nav nav-cards" role="tablist">
							
							<?php foreach( $settings['list'] as $item ) : ?>
								
								<?php 
									$i++; 
									$class    = ( 1 == $i ) ? 'active' : false;
									$selected = ( 1 == $i ) ? 'true' : 'false';
								?>
								
							    <li>
							        <a class="<?php echo $class; ?> card" id="tab-<?php echo $i; ?>" data-toggle="tab" href="#tab-content-<?php echo $i; ?>" role="tab" aria-controls="tab-<?php echo $i; ?>" aria-selected="<?php echo $selected; ?>">
							            <div class="card-body">
											<div class="media align-items-center">
											    <div class="step-circle mr-4"><?php echo $i; ?></div>
											    <div class="media-body">
													<h5><?php echo $item['item_title']; ?></h5>
													<?php echo wpautop( $item['item_text'] ); ?>
												</div>
											</div>
							            </div>
							        </a>
							    </li>
							
							<?php endforeach; ?>
							
						</ul>
					
					<?php endif; ?>
					
			    </div>
	
			    <div class="col-12 col-md-6 order-md-1">
			        <div class="tab-content" id="myTabContent">
						
						<?php $i = 0; ?>
						
						<?php foreach( $settings['list'] as $item ) : ?>
							
							<?php 
								$i++; 
								$class    = ( 1 == $i ) ? 'show active' : false;
							?>
							
							<div class="tab-pane fade <?php echo $class; ?>" id="tab-content-<?php echo $i; ?>" role="tabpanel" aria-labelledby="tab-<?php echo $i; ?>">
								<?php echo wp_get_attachment_image( $item['item_image']['id'], 'large', 0, array( 'class' => 'img-fluid shadow' ) ); ?>
							</div>
						
						<?php endforeach; ?>
	
			        </div>
			    </div>
	
			</div>
		
		<?php endif; ?>

	<?php
	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Graphic_Tabs_Block() );