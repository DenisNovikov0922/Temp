<?php 

/**
 * tommusrhodus_header_slider_shortcode_vc()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_header_slider_shortcode' ) )){
	function tommusrhodus_header_slider_shortcode( $atts, $content = null ) {

		global $header_height;

		extract( 
			shortcode_atts( 
				array(
					'image' => '',
					'layout' => 'light-image-left',
					'opacity' => '',
					'custom_css_class' => '',
					'height' => '',
				), $atts 
			) 
		);
		
		$image = explode(',', $image);
		$header_height = ( '' == $height ) ? '40' : $height;
		
		$output = '<section class="controls-inside p-0 controls-light bg-dark '. $custom_css_class .'" data-flickity="{ &quot;cellAlign&quot;: &quot;left&quot;, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }">'. do_shortcode($content) .'</section>';

		return $output;
	}

	add_shortcode( 'tommusrhodus_header_slider', 'tommusrhodus_header_slider_shortcode' );

}

if(!( function_exists( 'tommusrhodus_header_slider_content_shortcode' ) )){
	function tommusrhodus_header_slider_content_shortcode( $atts, $content = null ) {

		global $header_height;

		extract( 
			shortcode_atts( 
				array(
					'image' 	=> '',
					'opacity' 	=> '50',
				), $atts 
			) 
		);

		$image_class = 'bg-image opacity-'.$opacity;
		
		$output = '
			<div class="carousel-cell py-3 py-md-4 height-'. $header_height .'">
				'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => $image_class ) ) .'
				<div class="container">
					<div class="row">
						<div class="col-12 col-md-6">'. do_shortcode($content) .'</div>
					</div>
				</div>
			</div>
		';
		
		return $output;
	}

	add_shortcode( 'tommusrhodus_header_slider_content', 'tommusrhodus_header_slider_content_shortcode' );

}

/**
 * The VC Functions
 */
function tommusrhodus_header_slider_shortcode_vc() {

	vc_map( 
		array(
			"icon" 						=> 'insight-vc-block',
			"name" 						=> __( 'Header Slider' , 'tommusrhodus' ),
			"base" 						=> "tommusrhodus_header_slider",
			"category" 					=> __( 'Insight WP Theme', 'tommusrhodus' ),
			'as_parent'               	=> array('only' => 'tommusrhodus_header_slider_content'),
			'content_element'         	=> true,
			'show_settings_on_create' 	=> true,
			"js_view" 					=> 'VcColumnView',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => esc_html__("Header Height", 'tommusrhodus'),
					"param_name" => "height",
					"description" => 'Leave blank for default height, enter 10, 20, 30, 40, 50, 60, 70, 80, 90 or 100 for custom height (percentage of window height)',
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
					"param_name" => "custom_css_class",
					"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.<br><br><div class="wpb_element_label">Need help with this block? Check out the <a target="_blank" href="https://www.youtube.com/watch?v=yoWmatY3jNU">Video Tutorial</a></div>',
				),
			)
		) 
	);
	
}
add_action( 'vc_before_init', 'tommusrhodus_header_slider_shortcode_vc' );

// Nested Element
function tommusrhodus_header_slider_content_shortcode_vc() {
	vc_map( 
		array(
			"icon" 					=> 'insight-vc-block',
		    'name'            		=> __( 'Header Slider Content' , 'tommusrhodus' ),
		    'base'            		=> 'tommusrhodus_header_slider_content',
		    'description'     		=> __( 'A slide for the header slider.', 'tommusrhodus' ),
		    "category" 				=> __( 'Insight WP Theme', 'tommusrhodus' ),
		    'content_element' 		=> true,
		    'as_child'        		=> array('only' => 'tommusrhodus_header_slider'),
		    'params'          => array(
	            array(
	            	"type" => "attach_image",
	            	"heading" => esc_html__("Slide Background Image", 'tommusrhodus'),
	            	"param_name" => "image"
	            ),
	            array(
		    		"type" => "dropdown",
		    		"heading" => __("Slide Background Image Overlay Opacity (Default 40%)", 'tommusrhodus'),
		    		"param_name" => "opacity",
		    		"value" => array(
		    			'40%' => '40',
		    			'90%' => '90',
		    			'80%' => '80',
		    			'70%' => '70',
		    			'60%' => '60',
		    			'50%' => '50',
		    			'30%' => '30',
		    			'20%' => '20',
		    			'10%' => '10',
		    			'0%' => '0',
		    		)
		    	),
	            array(
	            	"type" => "textarea_html",
	            	"heading" => esc_html__("Slide Content", 'tommusrhodus'),
	            	"param_name" => "content",
	            	'holder' => 'div'
	            ),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_header_slider_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_tommusrhodus_header_slider extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_tommusrhodus_header_slider_content extends WPBakeryShortCode {

    }
}