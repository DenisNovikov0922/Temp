<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_tommusrhodus_intro_Carousel_Block extends Widget_Base {

	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-intro-carousel-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Intro Carousel', 'tr-framework' );
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
			'content_section',
			[
				'label' => __( 'Main Content', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
		
		$this->start_controls_section(
			'carousel_items_section',
			[
				'label' => __( 'Carousel Items', 'tr-framework' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title', [
				'label'       => __( 'Carousel Item Title', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);
		
		$repeater->add_control(
			'item_url', [
				'label'         => esc_html__( 'Carousel Item URL', 'tr-framework' ),
				'type'          => Controls_Manager::URL,
				'show_external' => true,
				'default' => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);
		
		$repeater->add_control(
			'item_image',
			[
				'label'      => __( 'Carousel Item Image', 'tr-framework' ),
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
						'item_title' => __( 'Carousel Item Title', 'tr-framework' ),
						'item_url'   => __( 'Carousel Item URL', 'tr-framework' ),
						'item_image' => __( 'Carousel Item Image', 'tr-framework' )
					]
				],
				'title_field' => __( 'Carousel Item', 'tr-framework' ),
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	
		$settings = $this->get_settings_for_display();
	?>
	
		<section class="space-lg bg-gradient overflow-hidden">
		    <div class="container">
		        <div class="row align-items-center">
					
		            <div class="col-12 col-md-6 mb-5 mb-md-0 position-relative">
		                <?php echo $settings['content']; ?>
		            </div>
					
					<?php if( is_array( $settings['list'] ) ) : ?>
					
			            <div class="col-12 col-md-6">
			                <div class="main-carousel overflow-visible" data-flickity='{ "cellAlign": "center", "contain": true, "prevNextButtons": false, "pageDots":true, "wrapAround":true, "autoPlay":5000, "imagesLoaded":true, "initialIndex":3, "draggable":false }'>
								
								<?php foreach( $settings['list'] as $item ) : ?>
									
									<?php
										$target   = $item['item_url']['is_external'] ? ' target="_blank"' : '';
										$nofollow = $item['item_url']['nofollow']    ? ' rel="nofollow"'  : '';
										$link     = 'href="'. esc_url( $item['item_url']['url'] ) .'"' . $target . $nofollow;
									?>
									
				                    <div class="carousel-cell col-11">
				                        <div class="card card-sm bg-gradient border-0">
											
				                            <a <?php echo $link; ?>>
												<?php echo wp_get_attachment_image( $item['item_image']['id'], 'large', 0, array( 'class' => 'card-image-top' ) ); ?>
				                            </a>
											
				                            <div class="card-footer d-flex justify-content-between bg-white">
				                                <a <?php echo $link; ?> class="h6 m-0"><?php echo $item['item_title']; ?></a>
				                                <a <?php echo $link; ?> data-toggle="tooltip" data-placement="top" title="Open in new tab"><i class="icon-popup"></i></a>
				                            </div>
											
				                        </div>
				                    </div>
								
								<?php endforeach; ?>
			
			                </div>
			            </div>
					
					<?php endif; ?>
					
		        </div>
		    </div>
		</section>

	<?php
	}
	
}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_tommusrhodus_intro_Carousel_Block() );