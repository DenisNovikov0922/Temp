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
			'carousel_items_section', [
				'label' => __( 'Accordion Items', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Accordion Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_content',
			[
				'label'       => __( 'Accordion Text', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Accordion Items', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'item_title'    => __( 'Accordion Title', 'tr-framework' ),
						'item_content'  => __( 'Accordion Content', 'tr-framework' )
					]
				],
				'title_field' => __( 'Accordion Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings = $this->get_settings_for_display();
		$i = 0;
	?>
		
		<?php if( is_array( $settings['list'] ) ) : ?>
		
			<div class="accordion" id="accordion-1" data-children=".accordion-item">
				
				<?php foreach( $settings['list'] as $item ) : ?>
					
				    <div class="accordion-item">
						
						<?php 
							$i++; 
							$selected = ( 1 == $i ) ? 'true' : 'false';
							$class    = ( 1 == $i ) ? 'show' : false;
						?>
						
				        <a data-toggle="collapse" data-parent="#accordion-1" href="#accordion-panel-<?php echo $i; ?>" aria-expanded="<?php echo $selected; ?>" aria-controls="accordion-panel-<?php echo $i; ?>">
				            <h5><?php echo $item['item_title']; ?></h5>
				            <i class="h5 icon-chevron-small-right"></i>
				        </a>
						
				        <div id="accordion-panel-<?php echo $i; ?>" class="collapse <?php echo $class; ?>" role="tabpanel">
				            <?php echo $item['item_content']; ?>
				        </div>
						
				    </div>
				
				<?php endforeach; ?>
				
			</div>
		
		<?php endif; ?>

	<?php
	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Accordion_Block() );