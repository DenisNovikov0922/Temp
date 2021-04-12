<?php 

/**
 * tommusrhodus_header_image_grid_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_header_image_grid_shortcode' ) )){
	function tommusrhodus_header_image_grid_shortcode( $atts, $content = null ) {
	
		extract( 
			shortcode_atts( 
				array(
					'image'            		=> '',
					'custom_css_class' 		=> ''
				), $atts 
			) 
		);

		$image = explode( ',', $image );

		$output = '
		    <section class="bg-gradient height-70 o-hidden">
		      <div class="w-100 position-absolute demo-pages">
		        <div class="row">';
		
		        foreach ( $image as $id ) {
		
		        	$image         = get_post( $id );
					$image_caption = $image->post_excerpt;
					$url           = ( empty( $image_caption ) ) ? '#' : $image_caption;
		
					if( $image_caption ) {
			        	$output .= '
							<div class="col-6 col-md-4 col-lg-3 mb-3">
								<a href="' . esc_url( $url ) . '">
									'. wp_get_attachment_image( $id, 'full', '', array( 'class' => 'rounded' ) ) .'
								</a>
							</div>
			        	';
					} else {
				        $output .= '
							<div class="col-6 col-md-4 col-lg-3 mb-3">
								<a href="' . esc_url( $image_caption ) . '" class="disable-link">
									'. wp_get_attachment_image( $id, 'full', '', array( 'class' => 'rounded' ) ) .'
								</a>
							</div>
			        	';				
					}
		
		        }
		
		$output .= '
		        </div>
		      </div>
		      <div class="container">
		        <div class="row justify-content-center">
		          <div class="col-xl-6 col-lg-7 col-md-9">
		            <div class="card shadow-lg text-dark">
		              <div class="card-body p-4 p-md-5">'. do_shortcode( $content ) .'</div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </section>
		';
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_header_image_grid', 'tommusrhodus_header_image_grid_shortcode' );
}

/**
 * tommusrhodus_header_image_grid_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_header_image_grid_shortcode_vc' ) )){

	function tommusrhodus_header_image_grid_shortcode_vc() {
		
		vc_map( 
			array(
				"icon"                    => 'insight-vc-block',
			    'name'                    => __( 'Header Image Grid' , 'tommusrhodus' ),
			    'base'                    => 'tommusrhodus_header_image_grid',
			    'description'             => __( 'Create fancy images & text', 'tommusrhodus' ),
			    "category"                => __( 'Insight WP Theme', 'tommusrhodus' ),
			    'params'                  => array(
			    	array(
			    		"type"       	=> "attach_images",
			    		"heading"    	=> __( "Background Images", 'tommusrhodus' ),
			    		'description'  	=> __( 'These images will be used as each carousel item - to have an image link to a page, simply place the URL you wish to link to into the <strong>CAPTION</strong> field. To give the item a title, place the title in the <strong>DESCRIPTION</strong> field', 'tommusrhodus' ),
			    		"param_name" 	=> "image"
			    	),	    	
			    	array(
		            	"type" 			=> "textarea_html",
		            	"heading" 		=> esc_html__("Content", 'tommusrhodus'),
		            	"param_name" 	=> "content",
		            	'holder' 		=> 'div'
		            ),
			    	array(
			    		"type"        	=> "textfield",
			    		"heading"     	=> __( "Extra CSS Class Name", 'tommusrhodus' ),
			    		"param_name"  	=> "custom_css_class",
			    		"description" 	=> __( '<code>FOR DEVELOPERS</code> Add a class name and refer to it in custom CSS / JS', 'tommusrhodus' ),
			    	),
			    )
			) 
		);
		
	}
	add_action( 'vc_before_init', 'tommusrhodus_header_image_grid_shortcode_vc' );

}