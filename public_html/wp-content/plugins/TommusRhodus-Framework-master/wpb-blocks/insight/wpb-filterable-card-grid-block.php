<?php 

/**
 * tommusrhodus_filterable_card_grid_shortcode_vc()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_filterable_card_grid_shortcode' ) )){
	function tommusrhodus_filterable_card_grid_shortcode( $atts, $content = null ) {

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
		
		$output = '
			<section>
      			<div class="container filterable-card-grid">
      				<div class="row justify-content-center mb-4">
			          	<div class="col col-md-auto">
				            <nav class="nav nav-pills" data-isotope-id="pages">
				              	<a href="#" class="nav-link active" data-isotope-filter="*">All</a>
				            </nav>
				        </div>
        			</div>
	        		<div class="row isotope hover-effect" data-isotope-id="pages">
	        			'. do_shortcode($content) .'
	        		</div>
	        	</div>
        	</section>';

		return $output;
	}

	add_shortcode( 'tommusrhodus_filterable_card_grid', 'tommusrhodus_filterable_card_grid_shortcode' );

}

if(!( function_exists( 'tommusrhodus_filterable_card_grid_content_shortcode' ) )){
	function tommusrhodus_filterable_card_grid_content_shortcode( $atts, $content = null ) {

		global $header_height;

		extract( 
			shortcode_atts( 
				array(
					'filter'            => '',
					'title'            => '',
					'link'             => '',
					'image'            => '',
					'layout'           => 'image',
					'custom_css_class' => ''
				), $atts 
			) 
		);
		
		$link_output = '#';

		if( function_exists( 'vc_build_link' ) ){
			$built_link  = vc_build_link( $link );
			$link_output = $built_link['url'];
		}
		
		if( 'image' == $layout ){
		
			$output = '
				<div class="col-sm-6 col-md-4 mb-3 grid-item '. str_replace( ' ', '-', strtolower( $filter ) ) .' '. $custom_css_class .'" data-filter-name="'. $filter .'">
					<a href="'. esc_attr( $link_output ) .'" class="card hover-effect">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img-top' ) ) .'
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center">
								<h6 class="mb-0">'. $title .'</h6>
								<i class="material-icons text-dark">keyboard_arrow_right</i>
							</div>
						</div>
					</a>
				</div>
			';
		
		} elseif( 'text' == $layout ){
		
			$output = '
				<div class="col-sm-6 col-md-4 mb-3 grid-item '. str_replace( ' ', '-', strtolower( $filter ) ) .' '. $custom_css_class .'"  data-filter-name="'. $filter .'">
					<div class="card">
					
						<div class="card-body pb-5 pt-4">'. do_shortcode( $content ) .'</div>
						
						<div class="card-footer '. $custom_css_class .'">
							<div class="d-flex align-items-center justify-content-between">
								<a href="'. esc_attr( $link_output ) .'">'. $title .'</a>
								<i class="material-icons text-dark">keyboard_arrow_right</i>
							</div>
						</div>
						
					</div>
				</div>
			';
		
		} elseif( 'image_and_text' == $layout ){
		
			$output = '
				<div class="col-sm-6 col-md-4 mb-3 grid-item '. str_replace( ' ', '-', strtolower( $filter ) ) .' '. $custom_css_class .'"  data-filter-name="'. $filter .'">
					<div class="card">

						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img-top' ) ) .'

						<div class="card-body py-3">'. do_shortcode( $content ) .' </div>

						<div class="card-footer">
							<div class="d-flex align-items-center justify-content-between">
								<a href="'. esc_attr( $link_output ) .'">'. $title .'</a>
								<i class="material-icons text-dark">keyboard_arrow_right</i>
							</div>
						</div>

					</div>
				</div>
			';
		
		}
		
		return $output;
	}

	add_shortcode( 'tommusrhodus_filterable_card_grid_content', 'tommusrhodus_filterable_card_grid_content_shortcode' );

}

/**
 * The VC Functions
 */
function tommusrhodus_filterable_card_grid_shortcode_vc() {

	vc_map( 
		array(
			"icon" 						=> 'insight-vc-block',
			"name" 						=> __( 'Filterable Card Grid' , 'tommusrhodus' ),
			"base" 						=> "tommusrhodus_filterable_card_grid",
			"category" 					=> __( 'Insight WP Theme', 'tommusrhodus' ),
			'as_parent'               	=> array('only' => 'tommusrhodus_filterable_card_grid_content'),
			'content_element'         	=> true,
			'show_settings_on_create' 	=> false,
			"js_view" 					=> 'VcColumnView',
			"params" => array(
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
add_action( 'vc_before_init', 'tommusrhodus_filterable_card_grid_shortcode_vc' );

// Nested Element
function tommusrhodus_filterable_card_grid_content_shortcode_vc() {
	vc_map( 
		array(
			"icon" 					=> 'insight-vc-block',
		    'name'            		=> __( 'Filterable Card' , 'tommusrhodus' ),
		    'base'            		=> 'tommusrhodus_filterable_card_grid_content',
		    'description'     		=> __( 'A simple grid of cards which can be filtered.', 'tommusrhodus' ),
		    "category" 				=> __( 'Insight WP Theme', 'tommusrhodus' ),
		    'content_element' 		=> true,
		    'as_child'        		=> array('only' => 'tommusrhodus_filterable_card_grid'),
		    'params'          => array(
		    	array(
					"type"        => "textfield",
					"heading"     => __( "Card Filter", 'tommusrhodus' ),
					"param_name"  => "filter",
					'holder'      => 'div'
				),
	            array(
					"type"       => "dropdown",
					"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
					"param_name" => "layout",
					"value"      => array(
						'Image and Link (No Content)'  => 'image',
						'Text and Link (No Image)'     => 'text',
						'Image, Text and Link'     	   => 'image_and_text'
					)
				),
				array(
					"type"        => "attach_image",
					"heading"     => __( "Card Image", 'tommusrhodus' ),
					"param_name"  => "image"
				),
				array(
					"type"        => "textfield",
					"heading"     => __( "Card Title", 'tommusrhodus' ),
					"param_name"  => "title",
					'holder'      => 'div'
				),
				array(
					"type"        => "vc_link",
					"heading"     => __( "Card Link", 'tommusrhodus' ),
					"param_name"  => "link"
				),
				array(
					"type"        => "textarea_html",
					"heading"     => __( "Block Content", 'tommusrhodus' ),
					"param_name"  => "content"
				),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_filterable_card_grid_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_tommusrhodus_filterable_card_grid extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_tommusrhodus_filterable_card_grid_content extends WPBakeryShortCode {

    }
}