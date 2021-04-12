<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Image_Gallery_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-image-gallery-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Image Gallery', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-carousel';
	}
	
	public function get_categories() {
		return [ 'missio-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'carousel_items_section', [
				'label' => __( 'Gallery Content', 'tr-framework' )
			]
		); 

		$this->add_control(
			'layout', [
				'label'   => __( 'Gallery Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image-and-title-card',
				'label_block' => true,
				'options' => [
					'image-and-title-card'								=> esc_html__( 'Image + Title Card', 'tr-framework' ),
					'image-and-title-card-custom-grid'					=> esc_html__( 'Image + Title Card Custom Grid', 'tr-framework' ),	
					'image-and-title-card-custom-grid-transparent'		=> esc_html__( 'Image + Title Card Custom Grid Transparent', 'tr-framework' ),					
					'filterable-image-and-title-card'					=> esc_html__( 'Filterable Image + Title Card', 'tr-framework' ),
					'image-and-title-card-carousel'						=> esc_html__( 'Image + Title Card Carousel', 'tr-framework' ),			
					'filterable-image-lightbox'							=> esc_html__( 'Filterable Image Lightbox', 'tr-framework' ),			
					'filterable-image-lightbox-4-columns'				=> esc_html__( 'Filterable Image Lightbox, 4 Columns', 'tr-framework' ),		
					'filterable-image-lightbox-2-columns'				=> esc_html__( 'Filterable Image Lightbox, 2 Columns', 'tr-framework' ),
					'featured-gallery'									=> esc_html__( 'Feature Gallery with Fullscreen Link', 'tr-framework' ),
					'featured-gallery-no-zoom'							=> esc_html__( 'Feature Gallery', 'tr-framework' ),
					'polaroid-carousel'									=> esc_html__( 'Polaroid Carousel', 'tr-framework' ),
					'polaroid'											=> esc_html__( 'Polaroid', 'tr-framework' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image', [
				'label'      => __( 'Gallery Image', 'tr-framework' ),
				'type'       => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'description', [
				'label'       => __( 'Description', 'tr-framework' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => ''
			]
		);

		$repeater->add_control(
			'overlay_caption', [
				'label'       => __( 'Overlay Caption (Used in applicable layouts)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'See Gallery',
				'label_block' => true
			]
		);

		$repeater->add_control(
			'item_link', [
				'label' => __( 'Link Image to URL? (Used in applicable layouts)', 'tr-framework' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( '#', 'tr-framework' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$repeater->add_control(
			'item_link_target', [
				'label'   => __( 'Link Behaviour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '_self',
				'label_block' => true,
				'options' => [
					'_self'			=> esc_html__( 'Open in Current Window', 'tr-framework' ),
					'_blank'		=> esc_html__( 'Open in New Window', 'tr-framework' ),
				],
			]
		);

		$repeater->add_control(
			'item_size', [
				'label'   => __( 'Item Size (Custom Grid Only)', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'col-md-4',
				'label_block' => true,
				'options' => [
					'col-md-6'			=> esc_html__( '6/12', 'tr-framework' ),
					'col-md-4'			=> esc_html__( '4/12', 'tr-framework' ),
					'col-md-3'			=> esc_html__( '3/12', 'tr-framework' ),
				],
			]
		);

		$repeater->add_control(
			'bg_color', [
				'label'   => __( 'Background Colour', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bg-white',
				'label_block' => true,
				'options' => [
					'bg-white'					=> esc_html__( 'White Background', 'tr-framework' ),
					'bg-inverse'				=> esc_html__( 'Dark Background', 'tr-framework' ),
					'bg-pastel-default'			=> esc_html__( 'Pastel Background', 'tr-framework' ),
				],
			]
		);

		$repeater->add_control(
			'item_category', [
				'label'       => __( 'Item Category? (Used for filtering)', 'tr-framework' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true
			]
		);

		$this->add_control(
			'list', [
				'label'   => __( 'Carousel Content', 'tr-framework' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [],
				'title_field' => __( 'Carousel Content', 'tr-framework' ),
			]
		);		

		$this->end_controls_section();

	}

	protected function render() {
		
		$settings = $this->get_settings_for_display();

		if( 'image-and-title-card-custom-grid' == $settings['layout'] ) {
		
			echo '
				<div class="tiles grid">
          			<div class="items row align-items-start isotope boxed grid-view text-center">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item grid-sizer '. $item['item_size'] .'">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
											'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										</a>
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					} else {

						echo '
							<div class="item grid-sizer '. $item['item_size'] .'">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'image-and-title-card-custom-grid-transparent' == $settings['layout'] ) {
		
			echo '
				<div class="tiles grid">
          			<div class="items row align-items-start isotope boxed grid-view text-center">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item grid-sizer '. $item['item_size'] .'">
				              	<div class="box p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
											'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										</a>
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					} else {

						echo '
							<div class="item grid-sizer '. $item['item_size'] .'">
				              	<div class="box p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'image-and-title-card' == $settings['layout'] ) {
		
			echo '
				<div class="tiles grid">
          			<div class="items row isotope boxed grid-view">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item grid-sizer col-md-6 col-lg-4">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
											'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										</a>
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					} else {

						echo '
							<div class="item grid-sizer col-md-6 col-lg-4">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'image-and-title-card-carousel' == $settings['layout'] ) {
		
			echo '<div class="cube-carousel cbp boxed grid-view text-center">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="cbp-item">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
											'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										</a>
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					} else {

						echo '
							<div class="cbp-item">
				              	<div class="box '. $item['bg_color'] .' shadow p-30">
				                	<figure class="main mb-20 overlay overlay1 rounded">
				                		<span></span>
					                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
					                  	<figcaption>
					                    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
					                  	</figcaption>
					                </figure>
				                	'. $item['description'] .'
				              	</div>
				            </div>
						';

					}

					

				}

				echo '
				</div>		
			';

		} elseif( 'featured-gallery' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-slider-container fullscreen">
          			<div class="flickity flickity-slider-main">';

					foreach( $settings['list'] as $item ) {

						echo '
							<div class="item">
				              	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
				            </div>
						';						

					}

					echo '
					</div>
					<div class="flickity flickity-slider-nav">';

					foreach( $settings['list'] as $item ) {

						echo '
							<div class="item">
				              	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
				            </div>
						';						

					}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'featured-gallery-no-zoom' == $settings['layout'] ) {
		
			echo '
				<div class="flickity-slider-container">
          			<div class="flickity flickity-slider-main">';

					foreach( $settings['list'] as $item ) {

						echo '
							<div class="item">
				              	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
				            </div>
						';						

					}

					echo '
					</div>
					<div class="flickity flickity-slider-nav">';

					foreach( $settings['list'] as $item ) {

						echo '
							<div class="item">
				              	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
				            </div>
						';						

					}

				echo '
					</div>	
				</div>		
			';

		} elseif( 'filterable-image-and-title-card' == $settings['layout'] ) {

			$filter_categories = array();

			foreach( $settings['list'] as $item ) {

				$filter_categories[] = $item['item_category'];				

			}

			$filters = array_unique(array_filter($filter_categories));

			echo '
			<div id="cube-grid-filter" class="cbp-filter-container text-center">
	          	<div data-filter="*" class="cbp-filter-item-active cbp-filter-item">All</div>';

	          	foreach( $filters as $filter ) {

					echo '<div data-filter=".'. sanitize_file_name( strtolower( $filter ) ) .'" class="cbp-filter-item">'. $filter .'</div>';			

				}

				echo '
	        </div>
	        <div class="clearfix"></div>
	        <div class="space20"></div>
	        <div id="cube-grid" class="cbp">
        	';

        	foreach( $settings['list'] as $item ) {

				echo '
					<div class="cbp-item '. sanitize_file_name( strtolower( $item['item_category'] ) ) .'">
						<figure class="overlay overlay1 rounded">
							<span></span>
							'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
						  	<figcaption>
						    	<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
						  	</figcaption>
						</figure>
						'. $item['description'] .'
					</div>
				';			

			}

        	echo '
        	</div>
        	';

		} elseif( 'polaroid-carousel' == $settings['layout'] ) {

			echo '<div class="cube-carousel cbp boxed grid-view text-center">';

        	foreach( $settings['list'] as $item ) {

        		if( $item['item_link']['url'] ) {

        			echo '
						<div class="cbp-item">
							<div class="box bg-white shadow p-30">
								<figure class="main polaroid overlay overlay1">
									<span></span>
				                	<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
										'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
									</a>
									<figcaption>
										<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
									</figcaption>
								</figure>
								'. $item['description'] .'
							</div>
						</div>
        			';

        		} else {

        			echo '
						<div class="cbp-item">
							<div class="box bg-white shadow p-30">
								<figure class="main polaroid overlay overlay1">
									<span></span>
				                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
									<figcaption>
										<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
									</figcaption>
								</figure>
								'. $item['description'] .'
							</div>
						</div>
        			';

        		}		

			}

        	echo '
        	</div>
        	';

		} elseif( 'filterable-image-lightbox' == $settings['layout'] ) {

			$filter_categories = array();

			foreach( $settings['list'] as $item ) {

				$filter_categories[] = $item['item_category'];				

			}

			$filters = array_unique(array_filter($filter_categories));

			if( $filters ) {

				echo '
				<div id="cube-grid-filter" class="cbp-filter-container text-center">
		          	<div data-filter="*" class="cbp-filter-item-active cbp-filter-item">All</div>';

		          	foreach( $filters as $filter ) {

						echo '<div data-filter=".'. sanitize_file_name( strtolower( $filter ) ) .'" class="cbp-filter-item">'. $filter .'</div>';			

					}

					echo '
		        </div>
		        <div class="clearfix"></div>
		        <div class="space20"></div>';

		    }

		    echo '
	        <div id="cube-grid" class="cbp light-gallery">
        	';

        	$i = 0;

        	foreach( $settings['list'] as $item ) {

				echo '
					<div class="cbp-item '. sanitize_file_name( strtolower( $item['item_category'] ) ) .'">
						<figure class="overlay overlay3 rounded">
							<a href="'. $item['image']['url'] .'" data-sub-html="#caption'. $i .'">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								<div id="caption'. $i .'" class="d-none">
									'. $item['description'] .'
								</div>
							</a>
						</figure>
					</div>
				';			

				$i++;

			}

        	echo '
        	</div>
        	';

		} elseif( 'filterable-image-lightbox-2-columns' == $settings['layout'] ) {

			$filter_categories = array();

			foreach( $settings['list'] as $item ) {

				$filter_categories[] = $item['item_category'];				

			}

			$filters = array_unique(array_filter($filter_categories));

			echo '
			<div id="cube-grid-large-filter" class="cbp-filter-container text-center">
	          	<div data-filter="*" class="cbp-filter-item-active cbp-filter-item">All</div>';

	          	foreach( $filters as $filter ) {

					echo '<div data-filter=".'. sanitize_file_name( strtolower( $filter ) ) .'" class="cbp-filter-item">'. $filter .'</div>';			

				}

				echo '
	        </div>
	        <div class="clearfix"></div>
	        <div class="space20"></div>
	        <div id="cube-grid-large" class="cbp light-gallery">
        	';

        	$i = 0;

        	foreach( $settings['list'] as $item ) {

				echo '
					<div class="cbp-item '. sanitize_file_name( strtolower( $item['item_category'] ) ) .'">
						<figure class="overlay overlay3 rounded">
							<a href="'. $item['image']['url'] .'" data-sub-html="#caption'. $i .'">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								<div id="caption'. $i .'" class="d-none">
									'. $item['description'] .'
								</div>
							</a>
						</figure>
					</div>
				';			

				$i++;

			}

        	echo '
        	</div>
        	';

		} elseif( 'filterable-image-lightbox-4-columns' == $settings['layout'] ) {

			$filter_categories = array();

			foreach( $settings['list'] as $item ) {

				$filter_categories[] = $item['item_category'];				

			}

			$filters = array_unique(array_filter($filter_categories));

			echo '
			<div id="cube-grid-large-filter" class="cbp-filter-container text-center">
	          	<div data-filter="*" class="cbp-filter-item-active cbp-filter-item">All</div>';

	          	foreach( $filters as $filter ) {

					echo '<div data-filter=".'. sanitize_file_name( strtolower( $filter ) ) .'" class="cbp-filter-item">'. $filter .'</div>';			

				}

				echo '
	        </div>
	        <div class="clearfix"></div>
	        <div class="space20"></div>
	        <div id="cube-grid-full" class="cbp light-gallery">
        	';

        	$i = 0;

        	foreach( $settings['list'] as $item ) {

				echo '
					<div class="cbp-item '. sanitize_file_name( strtolower( $item['item_category'] ) ) .'">
						<figure class="overlay overlay3 rounded">
							<a href="'. $item['image']['url'] .'" data-sub-html="#caption'. $i .'">
								'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
								<div id="caption'. $i .'" class="d-none">
									'. $item['description'] .'
								</div>
							</a>
						</figure>
					</div>
				';			

				$i++;

			}

        	echo '
        	</div>
        	';

		} elseif( 'polaroid' == $settings['layout'] ) {
		
			echo '
				<div class="tiles grid">
          			<div class="items row isotope boxed grid-view text-center">';

				foreach( $settings['list'] as $item ) {

					if( $item['item_link']['url'] ) {

						echo '
							<div class="item grid-sizer col-md-6 col-lg-4">
								<div class="box '. $item['bg_color'] .' shadow p-30">
									<figure class="main polaroid overlay overlay1">
										<a href="'. esc_url( $item['item_link']['url'] ) .'" target="'. $item['item_link_target'] .'">
											'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										</a>
										<figcaption>
											<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
										</figcaption>
									</figure>
									'. $item['description'] .'
								</div>
							</div>
						';

					} else {

						echo '
							<div class="item grid-sizer col-md-6 col-lg-4">
								<div class="box '. $item['bg_color'] .' shadow p-30">
									<figure class="main polaroid overlay overlay1">
										<span></span>
					                	'. wp_get_attachment_image( $item['image']['id'], 'large', 0 ) .'
										<figcaption>
											<h5 class="text-uppercase from-top mb-0">'. strip_tags( $item['overlay_caption'] ) .'</h5>
										</figcaption>
									</figure>
									'. $item['description'] .'
								</div>
							</div>
						';

					}

					

				}

				echo '
					</div>	
				</div>		
			';

		}

		if ( Plugin::$instance->editor->is_edit_mode() ) { ?>

 	 		<script>
				jQuery(document).ready(function(){



				});
 	 		</script>

		<?php 
		}
		
	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Image_Gallery_Block() );