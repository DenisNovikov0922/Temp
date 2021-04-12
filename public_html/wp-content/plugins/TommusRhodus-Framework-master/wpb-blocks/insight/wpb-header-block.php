<?php 

/**
 * tommusrhodus_header_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_header_shortcode' ) )){
	function tommusrhodus_header_shortcode( $atts, $content = null ) {
	
		extract( 
			shortcode_atts( 
				array(
					'image'            		=> '',
					'layout'           		=> 'standard',
					'opacity'		   		=> '50',
					'video_cover_image' 	=> '',					
					'video_url'        		=> '',
					'custom_css_class' 		=> ''
				), $atts 
			) 
		);

		$opacity = 'opacity-'.$opacity;
		
		$output = false;
		
		if( 'standard' == $layout ){
			
			$output = '
				<div class="p-0 bg-dark '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute '. $opacity ) ) .'
				
					<div class="container py-4 height-md-60">
						<div class="row">
							<div class="col-md-7 col-lg-6 spacer-y-3">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				
				</div>
			';
			
		} elseif( 'standard-gradient' == $layout ){
			
			$output = '
				<div class="p-0 bg-gradient '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute '. $opacity ) ) .'
				
					<div class="container spacer-y-5">
						<div class="row">
							<div class="col-md-6 col-lg-5">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				
				</div>
			';
			
		} elseif( 'standard-right' == $layout ){
			
			$output = '
				<div class="p-0 bg-dark '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute '. $opacity ) ) .'
				
					<div class="container py-4 height-md-60">
						<div class="row flex-md-row-reverse">
							<div class="col-md-7 col-lg-6 spacer-y-3">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				
				</div>
			';
			
		} elseif( 'breadcrumbs' == $layout ){
			
			$output = '
				<section class="bg-dark '. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'

					<div class="container height-lg-30">
						<div class="row">
							<div class="col-md-8 col-lg-7 col-xl-6">
								'. get_tommusrhodus_breadcrumbs( 'breadcrumb p-0 bg-dark bg-transparent' ) .'
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				
				</section>
			';
			
		} elseif( 'breadcrumbs-dark' == $layout ){
					
			$output = '
				<section class="'. $custom_css_class .'">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'

					<div class="container height-lg-30">
						<div class="row">
							<div class="col-md-8 col-lg-7 col-xl-6">
								'. get_tommusrhodus_breadcrumbs( 'breadcrumb p-0 bg-transparent' ) .'
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				
				</section>
			';
			
		} elseif( 'gradient' == $layout ){
		
			$output = '
				<section class="bg-gradient-2">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					
					<div class="container height-lg-50">
						<div class="row justify-content-center text-center">
							<div class="col-lg-10 col-xl-9">
								'. get_tommusrhodus_breadcrumbs( 'breadcrumb p-0 bg-dark bg-transparent justify-content-center' ) .'
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
					
				</section>
			';
		
		} elseif( 'gradient-no-breadcrumbs' == $layout ){
		
			$output = '
				<section class="bg-gradient-2">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					
					<div class="container height-lg-50">
						<div class="row justify-content-center text-center">
							<div class="col-lg-10 col-xl-9">
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
					
				</section>
			';
		
		} elseif( 'gradient-alt-no-breadcrumbs' == $layout ){
		
			$output = '
				<section class="height-70 bg-gradient">
					
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					
					<div class="container height-lg-50">
						<div class="row justify-content-center text-center">
							<div class="col-lg-10 col-xl-9">
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
					
				</section>
			';
		
		} elseif( 'half-text-half-image' == $layout ){
		
			$output = '
				<section class="row no-gutters p-0 bg-white">
					<div class="col-md-5 col-xl-6 d-flex flex-column order-md-2">

					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'flex-grow-1' ) ) .'

					</div>
					<div class="col-md-7 col-xl-6 order-md-1 height-60 height-md-70">
						<div class="row no-gutters justify-content-center py-4">
							<div class="col-md-10 col-lg-9 col-xl-8 p-3">
							'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( '60-text-40-image' == $layout ){
		
			$output = '
				<section class="row no-gutters p-0 bg-white">
					<div class="col-md-3 col-lg-4 col-xl-5 d-flex flex-column order-md-2">

					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'flex-grow-1' ) ) .'

					</div>
					<div class="col-md-9 col-lg-8 col-xl-7 order-md-1 height-md-60">
						<div class="row no-gutters justify-content-center w-100 py-3 py-md-4">
							<div class="col-md-11 col-lg-10 p-3">
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'standard-boxed-text' == $layout ){
		
			$output = '
				<section class="p-0 bg-dark">

					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image position-md-absolute' ) ) .'

					<div class="container">
						<div class="row height-md-60">
							<div class="col-md-8 col-lg-6">
								<div class="card p-0 p-md-3 my-3">
									<div class="card-body py-md-4 py-lg-5 px-md-4">
										'. do_shortcode( $content ) .'
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'inline-video' == $layout ){
		
			$output = '
				<section class="bg-dark height-70 overlay-dark overlay-top">

					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'

					<div class="container">
						<div class="row align-items-center justify-content-between">
							<div class="col-lg-5 mb-3 mb-lg-0">
								'. do_shortcode( $content ) .'
							</div>
							<div class="col-lg-7">
								<div class="video-cover rounded shadow-lg">
									'. wp_get_attachment_image( $video_cover_image, 'full', 0, array( 'class' => 'bg-image' ) ) .'
									<div class="video-play-icon"></div>
									<div class="embed-responsive embed-responsive-16by9">
										<iframe class="embed-responsive-item" data-src="'. esc_url( $video_url ) .'" allowfullscreen allow="autoplay; encrypted-media"></iframe>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'gradient-modal-video' == $layout ){
		
			$output = '
				<section class="height-80 bg-gradient-2">
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					<div class="container">
						<div class="row text-center">
							<div class="col">
								<a class="video-play-icon video-play-icon-lg" data-fancybox="" href="'. esc_url( $video_url ) .'"></a>
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'gradient-fullheight-modal-video' == $layout ){
		
			$output = '
				<section class="height-100 bg-gradient-2">
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					<div class="container">
						<div class="row justify-content-center text-center">
							<div class="col-xl-9 col-lg-11">
								<a class="video-play-icon video-play-icon-lg" data-fancybox="" href="'. esc_url( $video_url ) .'"></a>
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'standard-fullheight-text' == $layout ){
		
			$output = '
				<section class="header-3 p-0">
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					<div class="container">
						<div class="row no-gutters justify-content-center justify-content-md-start">
							<div class="col-10 col-md-8 col-lg-7 col-xl-6 bg-white spacer-y-4 height-md-50 height-lg-70">
								<div class="px-4">
							 		'. do_shortcode( $content ) .'
								</div>
							</div>
						</div>
					</div>
				</section>
			';
		
		} elseif( 'gradient-fullheight-no-breadcrumbs' == $layout ){
		
			$output = '
				<section class="height-100 bg-gradient">
					'. wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'bg-image '. $opacity ) ) .'
					<div class="container">
						<div class="row justify-content-center text-center">
							<div class="col-lg-9 col-md-11">
								'. do_shortcode( $content ) .'
							</div>
						</div>
					</div>
				</section>
			';
		
		}
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_header', 'tommusrhodus_header_shortcode' );
}

/**
 * tommusrhodus_header_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_header_shortcode_vc' ) )){

	function tommusrhodus_header_shortcode_vc() {
		
		vc_map( 
			array(
				"icon"                    => 'insight-vc-block',
			    'name'                    => __( 'Header' , 'tommusrhodus' ),
			    'base'                    => 'tommusrhodus_header',
			    'description'             => __( 'Create fancy images & text', 'tommusrhodus' ),
			    'as_parent'               => array('except' => 'tommusrhodus_tabs_content' ),
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
			    		"type" => "dropdown",
			    		"heading" => __("Header Background Image Overlay Opacity", 'tommusrhodus'),
			    		"param_name" => "opacity",
			    		"value" => array(
			    			'50%' => '50',
			    			'90%' => '90',
			    			'100%' => '100',
			    			'80%' => '80',
			    			'70%' => '70',
			    			'60%' => '60',			    			
			    			'40%' => '40',
			    			'30%' => '30',
			    			'20%' => '20',
			    			'10%' => '10',
			    			'0%' => '0',
			    		)
			    	),
			    	array(
			    		"type"       => "dropdown",
			    		"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
			    		"param_name" => "layout",
			    		"value"      => array(
			    			'Standard Header Text Left'                            						=> 'standard',
			    			'Standard Header Text Right'                           						=> 'standard-right',			    			
			    			'Standard Header Text Left with Gradient Background'   						=> 'standard-gradient',
			    			'Header with Colour Overlay & Breadcrumbs'             						=> 'breadcrumbs',
			    			'Header with Colour Overlay & Breadcrumbs (Dark Text)' 						=> 'breadcrumbs-dark',
			    			'Centered Header with Gradient Background'             						=> 'gradient',
			    			'Centered Header with Gradient Background (No Breadcrumbs)'					=> 'gradient-no-breadcrumbs',
			    			'Centered Header with Alt Gradient Background (No Breadcrumbs)'				=> 'gradient-alt-no-breadcrumbs',
			    			'Half Text & Half Image'             				   						=> 'half-text-half-image',
			    			'60% Text & 40% Image'             				   	   						=> '60-text-40-image',
			    			'Standard Header Boxed Text Left'                      						=> 'standard-boxed-text',			    			
			    			'Header with Inline Video'                             						=> 'inline-video',			    			
			    			'Centered Header with Gradient Background & Modal Video'					=> 'gradient-modal-video',			    			
			    			'Standard Header Fullheight Text Left'                      				=> 'standard-fullheight-text',    			
			    			'Centered Header Fullheight with Gradient Background'						=> 'gradient-fullheight-no-breadcrumbs',    			
			    			'Centered Header Fullheight with Gradient Background & Modal Video'			=> 'gradient-fullheight-modal-video',	
			    		)
			    	),		    	
			    	array(
			    		"type"        => "textfield",
			    		"heading"     => __( "Video Embed/Watch URL", 'tommusrhodus' ),
			    		"param_name"  => "video_url",
			    		"description" => "For inline videos, enter an embed URL (such as https://www.youtube.com/embed/DYaq2sWTWAA?rel=0&showinfo=0&autoplay=1) - for modal videos, enter a warch url (such as https://www.youtube.com/watch?v=c8aFcHFu8QM)"
			    	),
			    	array(
			    		"type"       => "attach_image",
			    		"heading"    => __( "Inline Video Cover Image", 'tommusrhodus' ),
			    		"param_name" => "video_cover_image"
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
	add_action( 'vc_before_init', 'tommusrhodus_header_shortcode_vc' );
	
	if( class_exists( 'WPBakeryShortCodesContainer' ) ){
	    class WPBakeryShortCode_tommusrhodus_header extends WPBakeryShortCodesContainer {}
	}

}