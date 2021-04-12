<?php

/**
 * The Shortcode
 */
function tommusrhodus_card_carousel_shortcode( $atts, $content = null ) {

	global $carousel_layout;

	extract( 
		shortcode_atts( 
			array(
				'custom_css_class' 	=> '',
				'carousel_layout'	=> 'large_images'
			), $atts 
		) 
	);
	
	if( 'large_images' == $carousel_layout ) {

		$output = '<div data-flickity="{ &quot;cellAlign&quot;: &quot;center&quot;, &quot;autoPlay&quot;: true, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }" class="controls-light slider-highlight-selected">'. do_shortcode($content) .'</div>';

	} elseif( 'small_images' == $carousel_layout ) {

		$output = '<div data-flickity="{ &quot;groupCells&quot;: 1, &quot;cellAlign&quot;: &quot;center&quot;, &quot;autoPlay&quot;: 1500, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }" class="controls-light slider-highlight-selected">'. do_shortcode($content) .'</div>';

	} elseif( 'medium_images' == $carousel_layout ) {

		$output = '<div data-flickity="{ &quot;cellAlign&quot;: &quot;center&quot;, &quot;autoPlay&quot;: true, &quot;contain&quot;: true, &quot;imagesLoaded&quot;: true, &quot;wrapAround&quot;: true }" class="controls-light slider-highlight-selected">'. do_shortcode($content) .'</div>';

	}

	return $output;
}
add_shortcode( 'insight_card_carousel', 'tommusrhodus_card_carousel_shortcode' );

/**
 * The Shortcode
 */
function tommusrhodus_card_carousel_content_shortcode( $atts, $content = null, $carousel_layout ) {

	global $carousel_layout;

	extract( 
		shortcode_atts( 
			array(
				'title' 			=> '',
				'layout' 			=> 'card-top',
				'icon' 				=> '',
				'link' 				=> '',
				'image'				=> '',
				'custom_css_class' 	=> ''
			), $atts 
		) 
	);

	$link_output = '#';

	if( function_exists( 'vc_build_link' ) ){
		$built_link  = vc_build_link( $link );
		$link_output = $built_link['url'];
	}

	if( 'large_images' == $carousel_layout ) {
		$column_class = 'col-9 col-md-5 col-lg-4';
	} elseif( 'small_images' == $carousel_layout ) {
		$column_class = 'col-5 col-md-3 col-lg-3 col-xl-2 px-1';
	} elseif( 'medium_images' == $carousel_layout ) {
		$column_class = 'col-6 col-md-4 col-lg-3 pt-3';
	} 
		
	if( 'card-top' == $layout ){

		if( $link ) {
			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' card flex-fill hover-effect">
						<div>
							<div class="card-body py-4">
								<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				</div>
			';
		} else {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<div class="'. $custom_css_class .' card">
						<div class="card-body py-4">
							<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				</div>
			';

		}
		
	} elseif( 'card-top-centered' == $layout ){

		if( $link ) {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' card flex-fill hover-effect">
						<div class="text-center">
							<div class="card-body py-4 text-center">
								<i class="'. $icon .' insight-v-large flex-shrink-0 text-primary mb-2"></i>
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				</div>
			';

		} else {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<div class="'. $custom_css_class .' card text-center">
						<div class="card-body py-4 text-center">
							<i class="'. $icon .' insight-v-large flex-shrink-0 text-primary mb-2"></i>
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				</div>
			';
		}

	} elseif( 'image-text-overlay' == $layout ){

		if( $link ) {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<a href="'. esc_url( $link_output ) .'" class="card bg-gradient-3 text-center justify-content-center align-items-center hover-effect shadow">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-90' ) ) .'
			            <div class="card-img-overlay">
			              	<span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span>
			            </div>
		          	</a>
				</div>
			';

		} else {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<div class="card bg-gradient-3 text-center justify-content-center align-items-center hover-effect shadow">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-90' ) ) .'
			            <div class="card-img-overlay">
			              	<span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span>
			            </div>
		          	</div>
				</div>
			';
		}

	} elseif( 'image-text-overlay-alt' == $layout ){

		if( $link ) {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<a href="'. esc_url( $link_output ) .'" class="card bg-gradient text-center justify-content-center align-items-center hover-effect shadow">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-90' ) ) .'
			            <div class="card-img-overlay">
			              	<span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span>
			            </div>
		          	</a>
				</div>
			';

		} else {

			$output = '
				<div class="'. esc_attr( $column_class ) .'">
					<div class="card bg-gradient-3 text-center justify-content-center align-items-center hover-effect shadow">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-90' ) ) .'
			            <div class="card-img-overlay">
			              	<span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span>
			            </div>
		          	</div>
				</div>
			';
		}

	}

	return $output;
}
add_shortcode( 'insight_card_carousel_content', 'tommusrhodus_card_carousel_content_shortcode' );

// Parent Element
function tommusrhodus_card_carousel_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'                    => esc_html__( 'Card Carousel List' , 'tommusrhodus' ),
		    'base'                    => 'insight_card_carousel',
		    'as_parent'               => array('only' => 'insight_card_carousel_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		    'content_element'         => true,
		    'show_settings_on_create' => true,
		    "js_view" => 'VcColumnView',
		    "category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
		    'params'          => array(
		    	array(
					"type"       => "dropdown",
					"heading"    => __( "Carousel Display Type", 'tommusrhodus' ),
					"param_name" => "carousel_layout",
					"value"      => array(
						'Large Images'  					=> 'large_images',
						'Small Images'     					=> 'small_images',
						'Medium Images'     				=> 'medium_images',
					)
				),
		    	array(
		    		"type" => "textfield",
		    		"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
		    		"param_name" => "custom_css_class",
		    		"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.',
		    	),
		    )
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_card_carousel_shortcode_vc' );

// Nested Element
function tommusrhodus_card_carousel_content_shortcode_vc() {

	$icons = array('Install TommusRhodus Framework' => 'Install TommusRhodus Framework');
		
	if( function_exists('tommusrhodus_get_icons') ){
		$icons = tommusrhodus_get_icons();	
	}

	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'            => esc_html__('Card Carousel Item', 'tommusrhodus'),
		    'base'            => 'insight_card_carousel_content',
		    "category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
		    'content_element' => true,
		    'as_child'        => array('only' => 'insight_card_carousel'), // Use only|except attributes to limit parent (separate multiple values with comma)
		    'params'          => array(
		    	array(
		    		"type"       => "dropdown",
		    		"heading"    => __( "Card Display Type", 'tommusrhodus' ),
		    		"param_name" => "layout",
		    		"value"      => array(
		    			'Card with Icon Top and Text'       				=> 'card-top',
		    			'Card with Icon Top and Text, Centered'				=> 'card-top-centered',		    			
		    			'Image with Text Overlay'							=> 'image-text-overlay',	    			
		    			'Image with Text Overlay, Alt Gradient'				=> 'image-text-overlay-alt',
		    		)
		    	),
				array(
					"type" => "tommusrhodus_icons",
					"heading" => esc_html__( "Click an Icon to choose", 'tommusrhodus' ),
					"param_name" => "icon",
					"value" => $icons,
					'description' => 'Type "none" or leave blank to hide icons.'
				),
				array(
					"type"        => "attach_image",
					"heading"     => __( "Card Image", 'tommusrhodus' ),
					"param_name"  => "image"
				),
		    	array(
		    		"type" => "textfield",
		    		"heading" => esc_html__("Title", 'tommusrhodus'),
		    		"param_name" => "title",
		    		'holder' => 'div'
		    	),
	            array(
	            	"type" => "textarea_html",
	            	"heading" => esc_html__("Block Content", 'tommusrhodus'),
	            	"param_name" => "content"
	            ),
	            array(
					"type"        => "vc_link",
					"heading"     => __( "Link", 'tommusrhodus' ),
					"param_name"  => "link"
				),
	            array(
	            	"type" => "textfield",
	            	"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
	            	"param_name" => "custom_css_class",
	            	"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.<br><br><div class="wpb_element_label">Need help with this block? Check out the <a target="_blank" href="https://www.youtube.com/watch?v=YHRpDbuybXw">Video Tutorial</a></div>',
	            ),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_card_carousel_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_insight_card_carousel extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_insight_card_carousel_content extends WPBakeryShortCode {}
}