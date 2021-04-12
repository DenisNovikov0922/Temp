<?php 

/**
 * tommusrhodus_slider_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_caption/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_slider_shortcode' ) )){
	function tommusrhodus_slider_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'image'           => '',
					'custom_css_class' => ''
				), $atts 
			) 
		);
		
		$output          = '';
		$images_exploded = explode( ',', $image );
		
		if( is_array( $images_exploded ) ){
		
			$output = '<div class="slider-cards '. $custom_css_class .'" data-flickity="{ &quot;cellAlign&quot;: &quot;left&quot;, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }">';
				
			foreach( $images_exploded as $slide ){
			
				$output .= '<div class="carousel-cell">'. wp_get_attachment_image( $slide, 'full', 0, array( 'class' => 'rounded' ) );
				
				if( $caption = wp_get_attachment_caption( $slide ) ){
					$output .= '<div class="card col-lg-5 col-xl-4 py-2 flex-fill"><div class="card-body">'. $caption .'</div></div>';
				}
						
				$output .= '</div>';
				
			}
				
			$output .= '</div>';
		
		}
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_slider', 'tommusrhodus_slider_shortcode' );
}

/**
 * tommusrhodus_slider_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_slider_shortcode_vc' ) )){
	function tommusrhodus_slider_shortcode_vc(){
	
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Slider", 'tommusrhodus' ),
				"base"     => "tommusrhodus_slider",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type"        => "attach_images",
						"heading"     => __( "Slider Images", 'tommusrhodus' ),
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
	add_action( 'vc_before_init', 'tommusrhodus_slider_shortcode_vc' );
}