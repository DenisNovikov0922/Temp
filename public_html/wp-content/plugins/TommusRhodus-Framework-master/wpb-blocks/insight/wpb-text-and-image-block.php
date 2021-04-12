<?php 

/**
 * tommusrhodus_text_image_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_text_image_shortcode' ) )){
	function tommusrhodus_text_image_shortcode( $atts, $content = null ) {
	
		extract( 
			shortcode_atts( 
				array(
					'image'            => '',
					'layout'           => 'lozenge-right',
					'custom_css_class' => ''
				), $atts 
			) 
		);
		
		$output = false;
		
		if( 'lozenge-right' == $layout ){
			
			$output = '
				<div class="card row no-gutters flex-column flex-md-row flex-md-row-reverse '. $custom_css_class .'">
				
					<div class="flex-column col-md-6">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'card-img flex-grow-1 h-100' ) ) .'
					</div>
					
					<div class="card-body d-flex align-items-center col-md-6 p-lg-5 p-md-4 p-3">
						<div>'. do_shortcode( $content ) .'</div>
					</div>
					
				</div>
			';
			
		}  elseif( 'lozenge-left' == $layout ){
			
			$output = '
				<div class="card row no-gutters flex-column flex-md-row '. $custom_css_class .'">
				
					<div class="flex-column col-md-6">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'card-img flex-grow-1 h-100' ) ) .'
					</div>
					
					<div class="card-body d-flex align-items-center col-md-6 p-lg-5 p-md-4 p-3">
						<div>'. do_shortcode( $content ) .'</div>
					</div>
					
				</div>
			';
			
		} elseif( 'lozenge-right-small' == $layout ){
			
			$output = '
				<div class="card row no-gutters flex-column flex-md-row flex-md-row-reverse '. $custom_css_class .'">
				
					<div class="flex-column col-md-5">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'card-img flex-grow-1 h-100' ) ) .'
					</div>
					
					<div class="card-body d-flex align-items-center col-md-7 p-4">
						<div>'. do_shortcode( $content ) .'</div>
					</div>
					
				</div>
			';
			
		}  elseif( 'lozenge-left-small' == $layout ){
			
			$output = '
				<div class="card row no-gutters flex-column flex-md-row '. $custom_css_class .'">
				
					<div class="flex-column col-md-5">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'card-img flex-grow-1 h-100' ) ) .'
					</div>
					
					<div class="card-body d-flex align-items-center col-md-7 p-4">
						<div>'. do_shortcode( $content ) .'</div>
					</div>
					
				</div>
			';
			
		} elseif( 'lozenge-bottom-right' == $layout ){
			
			$output = '
				<div class="row no-gutters '. $custom_css_class .'">
					<div class="col text-image-2">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'join-bottom' ) ) .'
						<div class="bg-white position-lg-absolute join-top col-lg-6 px-3 py-3 px-md-5 py-md-4">'. do_shortcode( $content ) .'</div>
					</div>
				</div>
			';
			
		} elseif( 'full-right' == $layout ){
			
			$output = '
				<div class="row no-gutters p-0 '. $custom_css_class .'">
				
					<div class="col-md-5 col-lg-6 d-flex flex-column order-md-2">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'flex-grow-1' ) ) .'
					</div>
					
					<div class="col-md-7 col-lg-6 d-flex bg-white order-md-1 align-items-center">
						<div class="row no-gutters justify-content-center py-md-4">
							<div class="col-md-10 col-lg-9 col-xl-8 p-3 py-md-5">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
					
				</div>
			';
			
		} elseif( 'full-left' == $layout ){
			
			$output = '
				<div class="row no-gutters flex-md-row-reverse p-0 '. $custom_css_class .'">
				
					<div class="col-md-5 col-lg-6 d-flex flex-column order-md-2">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'flex-grow-1' ) ) .'
					</div>
					
					<div class="col-md-7 col-lg-6 d-flex bg-white order-md-1 align-items-center">
						<div class="row no-gutters justify-content-center py-md-4">
							<div class="col-md-10 col-lg-9 col-xl-8 p-3 py-md-5">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
					
				</div>
			';
			
		} elseif( 'block-left' == $layout ){
		
			$output = '
				<div class="row justify-content-around align-items-center '. $custom_css_class .'">
				
					<div class="col-sm-6 col-lg-4 mb-3 mb-sm-0">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'rounded' ) ) .'
					</div>
					
					<div class="col-sm-6 col-lg-5">'. do_shortcode( $content ) .'</div>
				
				</div>
			';
			
		} elseif( 'block-right' == $layout ){
		
			$output = '
				<div class="row justify-content-around flex-md-row-reverse align-items-center '. $custom_css_class .'">
				
					<div class="col-sm-6 col-lg-4 mb-3 mb-sm-0">
						'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'rounded' ) ) .'
					</div>
					
					<div class="col-sm-6 col-lg-5">'. do_shortcode( $content ) .'</div>
				
				</div>
			';
			
		} elseif( 'background-left' == $layout ){
			
			$output = '
				<div class="p-0 bg-white '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute' ) ) .'
				
					<div class="container-fluid p-0">
						<div class="row no-gutters">
							<div class="col-md-7 col-lg-6 col-xl-5">
								<div class="card p-0 p-md-3 m-3">
									<div class="card-body p-0 p-md-4">'. do_shortcode( $content ) .'</div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			';
			
		} elseif( 'background-right' == $layout ){
			
			$output = '
				<div class="p-0 bg-white '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute' ) ) .'
				
					<div class="container-fluid p-0">
						<div class="row no-gutters flex-md-row-reverse">
							<div class="col-md-7 col-lg-6 col-xl-5">
								<div class="card p-0 p-md-3 m-3">
									<div class="card-body p-0 p-md-4">'. do_shortcode( $content ) .'</div>
								</div>
							</div>
						</div>
					</div>
				
				</div>
			';
			
		}
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_text_image', 'tommusrhodus_text_image_shortcode' );
}

/**
 * tommusrhodus_text_image_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_text_image_shortcode_vc' ) )){

	function tommusrhodus_text_image_shortcode_vc() {
		
		vc_map( 
			array(
				"icon"                    => 'insight-vc-block',
			    'name'                    => __( 'Text + Image' , 'tommusrhodus' ),
			    'base'                    => 'tommusrhodus_text_image',
			    'description'             => __( 'Create fancy images & text', 'tommusrhodus' ),
			    'as_parent'               => array( 'except' => 'tommusrhodus_tabs_content' ),
			    'content_element'         => true,
			    'show_settings_on_create' => true,
			    "js_view"                 => 'VcColumnView',
			    "category"                => __( 'Insight WP Theme', 'tommusrhodus' ),
			    'params'                  => array(
			    	array(
			    		"type"       => "attach_image",
			    		"heading"    => __( "Block Image", 'tommusrhodus' ),
			    		"param_name" => "image"
			    	),
			    	array(
			    		"type"       => "dropdown",
			    		"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
			    		"param_name" => "layout",
			    		"value"      => array(
			    			'Lozenge with Image Right'         => 'lozenge-right',
			    			'Lozenge with Image Left'          => 'lozenge-left',
			    			'Lozenge with Smaller Image Right' => 'lozenge-right-small',
			    			'Lozenge with Smaller Image Left'  => 'lozenge-left-small',
			    			'Lozenge with Text Bottom Right'   => 'lozenge-bottom-right',
			    			'Fullwidth with Image Right'       => 'full-right',
			    			'Fullwidth with Image Left'        => 'full-left',
			    			'Block with Image Right'           => 'block-right',
			    			'Block with Image Left'            => 'block-left',
			    			'Background with Block Right'      => 'background-right',
			    			'Background with Block Left'       => 'background-left'
			    		)
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
	add_action( 'vc_before_init', 'tommusrhodus_text_image_shortcode_vc' );
	
	if( class_exists( 'WPBakeryShortCodesContainer' ) ){
	    class WPBakeryShortCode_tommusrhodus_text_image extends WPBakeryShortCodesContainer {}
	}

}