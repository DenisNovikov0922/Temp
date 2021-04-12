<?php 

/**
 * tommusrhodus_carousel_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_caption/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_carousel_shortcode' ) )){
	function tommusrhodus_carousel_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'image'           	=> '',
					'layout'           	=> 'large_images',
					'custom_css_class' 	=> ''
				), $atts 
			) 
		);
		
		$output          = '';
		$images_exploded = explode( ',', $image );

		if( 'large_images' == $layout ) {
		
			if( is_array( $images_exploded ) ) {

				$output = '<div data-flickity="{ &quot;cellAlign&quot;: &quot;center&quot;, &quot;autoPlay&quot;: true, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }" class="controls-light slider-highlight-selected '. $custom_css_class .'">';
					
				foreach( $images_exploded as $slide ){

					$image = get_post( $slide );
					if( !empty( $image->post_excerpt ) ) {
						$image_caption = $image->post_excerpt;
					} else {
						$image_caption = '';
					}
					if( !empty( $image->post_content ) ) {
						$image_desc = $image->post_content;
					} else {
						$image_desc = '';
					}

					$output .= '
						<div class="col-9 col-md-5 col-lg-4">
							<a href="' . esc_url( $image_caption ) . '" class="card bg-transparent hover-effect shadow-sm">
								'. wp_get_attachment_image( $slide, 'full', '', array( 'class' => 'card-img-top' ) ) .'
								<div class="card-body bg-white">
									<div class="d-flex justify-content-between align-items-center text-dark">
										<h6 class="mb-0">' . wp_kses_post( $image_desc ) . '</h6>
										<i class="material-icons text-dark">keyboard_arrow_right</i>
									</div>
								</div>
							</a>
						</div>
		        	';
					
				}
					
				$output .= '</div>';
			
			}

		} elseif( 'small_images' == $layout ) {
		
			if( is_array( $images_exploded ) ) {

				$output = '<div data-flickity="{ &quot;groupCells&quot;: 1, &quot;cellAlign&quot;: &quot;center&quot;, &quot;autoPlay&quot;: 1500, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }" class="controls-light slider-highlight-selected '. $custom_css_class .'">';
					
				foreach( $images_exploded as $slide ){

					$image = get_post( $slide );
					if( !empty( $image->post_excerpt ) ) {
						$image_caption = $image->post_excerpt;
					} else {
						$image_caption = '';
					}
					if( !empty( $image->post_content ) ) {
						$image_desc = $image->post_content;
					} else {
						$image_desc = '';
					}

					$output .= '
						<div class="col-5 col-md-3 col-lg-3 col-xl-2 px-1">
							<a href="' . esc_url( $image_caption ) . '" class="card bg-transparent hover-effect shadow-sm">
								'. wp_get_attachment_image( $slide, 'full', '', array( 'class' => 'card-img-top' ) ) .'
								<div class="card-body bg-white">
									<div class="d-flex justify-content-between align-items-center text-dark">
										<h6 class="mb-0">' . wp_kses_post( $image_desc ) . '</h6>
										<i class="material-icons text-dark">keyboard_arrow_right</i>
									</div>
								</div>
							</a>
						</div>
		        	';
					
				}
					
				$output .= '</div>';
			
			}

		}
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_carousel', 'tommusrhodus_carousel_shortcode' );
}

/**
 * tommusrhodus_carousel_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_carousel_shortcode_vc' ) )){
	function tommusrhodus_carousel_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Image Carousel", 'tommusrhodus' ),
				"base"     => "tommusrhodus_carousel",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type"       => "dropdown",
						"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
						"param_name" => "layout",
						"value"      => array(
							'Large Images'  					=> 'large_images',
							'Small Images'     					=> 'small_images',
						)
					),
					array(
						"type"        => "attach_images",
						"heading"     => __( "Carousel Images", 'tommusrhodus' ),
						'description'  	=> __( 'These images will be used in the background of this header area - to have an image link to a page, simply place the URL you wish to link to into the <strong>CAPTION</strong> field.', 'tommusrhodus' ),
						"param_name"  => "image"
					),
					array(
						"type"        => "textfield",
						"heading"     => __( "Extra CSS Class Name", 'tommusrhodus' ),
						"param_name"  => "custom_css_class",
						"description" => __( '<code>FOR DEVELOPERS</code> Add a class name and refer to it in custom CSS / JS', 'tommusrhodus' ),
					),
				)
			) 
		);
	}
	add_action( 'vc_before_init', 'tommusrhodus_carousel_shortcode_vc' );
}