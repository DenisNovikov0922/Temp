<?php 

/**
 * tommusrhodus_card_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_card_shortcode' ) )){
	function tommusrhodus_card_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
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
				<a href="'. esc_attr( $link_output ) .'" class="card hover-effect '. $custom_css_class .'">
					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img-top' ) ) .'
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-center text-dark">
							<h6 class="mb-0">'. $title .'</h6>
							<i class="material-icons text-dark">keyboard_arrow_right</i>
						</div>
					</div>
				</a>
			';
		
		} elseif( 'text' == $layout ){
		
			$output = '
				<div class="card '. $custom_css_class .'">';

				if( $content ) {
					$output .= '<div class="card-body pb-5 pt-4">'. do_shortcode( $content ) .'</div>';					
				}					

				$output .= '
					<div class="card-footer '. $custom_css_class .'">
						<div class="d-flex align-items-center justify-content-between">
							<a href="'. esc_attr( $link_output ) .'">'. $title .'</a>
							<i class="material-icons text-dark">keyboard_arrow_right</i>
						</div>
					</div>
					
				</div>
			';
		
		} elseif( 'text-dark-bg' == $layout ){
		
			$output = '
				<div class="card bg-dark '. $custom_css_class .'">';

				if( $content ) {
					$output .= '<div class="card-body pb-5 pt-4">'. do_shortcode( $content ) .'</div>';					
				}					

				$output .= '
					<div class="card-footer bg-dark '. $custom_css_class .'">
						<div class="d-flex align-items-center justify-content-between">
							<a href="'. esc_attr( $link_output ) .'">'. $title .'</a>
							<i class="material-icons">keyboard_arrow_right</i>
						</div>
					</div>
					
				</div>
			';
		
		} elseif( 'image_and_text' == $layout ){
		
			$output = '
				<div class="card '. $custom_css_class .'">

					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img-top' ) );

					if( $content ) {
						$output .= '<div class="card-body py-3">'. do_shortcode( $content ) .'</div>';
					}

					$output .= '
					<div class="card-footer">
						<div class="d-flex align-items-center justify-content-between">
							<a href="'. esc_attr( $link_output ) .'">'. $title .'</a>
							<i class="material-icons text-dark">keyboard_arrow_right</i>
						</div>
					</div>

				</div>
			';
		
		} elseif( 'image_and_text_card_link' == $layout ){
		
			$output = '
				<a href="'. esc_attr( $link_output ) .'"" class="card hover-effect'. $custom_css_class .'">

					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img-top' ) );

					if( $content ) {
						$output .= '<div class="card-body py-3 text-dark">'. do_shortcode( $content ) .'</div>';
					}

					$output .= '
					<div class="card-footer text-dark">
						<div class="d-flex align-items-center justify-content-between">
							'. $title .'
							<i class="material-icons text-dark">keyboard_arrow_right</i>
						</div>
					</div>

				</a>
			';
		
		} elseif( 'image_background_and_text_overlay' == $layout ){
		
			$output = '
				<a href="'. esc_attr( $link_output ) .'" class="card bg-dark justify-content-end flex-fill mb-lg-0">
					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img flex-grow-1' ) ) .'<div class="card-img-overlay bg-dark w-100"><span class="mb-0 text-white">'. do_shortcode( $content ) .'</span></div>
				</a>
			';
		
		} elseif( 'image_left_and_text_card_link' == $layout ){
		
			$output = '
				<a href="'. esc_attr( $link_output ) .'" class="card row no-gutters flex-column flex-md-row flex-fill hover-effect '. $custom_css_class .'">
					<div class="flex-column col-md-4">
						'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img flex-grow-1 h-100' ) ) .'					
					</div>
					<div class="card-body d-flex align-items-center col-md-8 px-3">
						<div>
							<span class="h5 mb-0">'. do_shortcode( $content ) .'</span>
						</div>
					</div>
				</a>
			';
		
		} elseif( 'image_background_and_text_overlay_static' == $layout ){
		
			$output = '				
				<a href="'. esc_attr( $link_output ) .'" class="card bg-dark ext-center justify-content-center align-items-center">
					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-80' ) ) .'<div class="card-img-overlay"><span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span></div>
				</a>
			';
		
		} elseif( 'image_background_and_text_overlay_static_upper_left' == $layout ){
		
			$output = '
				<a href="'. esc_attr( $link_output ) .'" class="card bg-dark">
					'. wp_get_attachment_image( $image, 'large', 0, array( 'class' => 'card-img opacity-90' ) ) .'<div class="card-img-overlay"><span class="mb-0 h5 text-white">'. do_shortcode( $content ) .'</span></div>
				</a>
			';
		
		} 		


		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_card', 'tommusrhodus_card_shortcode' );
}

/**
 * tommusrhodus_card_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_card_shortcode_vc' ) )){
	function tommusrhodus_card_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Link Card", 'tommusrhodus' ),
				"base"     => "tommusrhodus_card",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type"       => "dropdown",
						"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
						"param_name" => "layout",
						"value"      => array(
							'Image and Link (No Content)'  						=> 'image',
							'Text and Link (No Image)'     						=> 'text',
							'Text and Link, Dark Background (No Image)'			=> 'text-dark-bg',
							'Image, Text and Link'     	   						=> 'image_and_text',	
							'Image, Text and Link Whole Cart'					=> 'image_and_text_card_link',							
							'Image Left, Text and Link Whole Cart'				=> 'image_left_and_text_card_link',						
							'Image Background, Text Overlay and Link'			=> 'image_background_and_text_overlay',						
							'Image Background, Text Overlay Static'				=> 'image_background_and_text_overlay_static',						
							'Image Background, Text Overlay Static Upper Left'	=> 'image_background_and_text_overlay_static_upper_left'
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
	add_action( 'vc_before_init', 'tommusrhodus_card_shortcode_vc' );
}