<?php 

/**
 * tommusrhodus_pricing_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pricing_shortcode' ) )){
	function tommusrhodus_pricing_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'title'            => '',
					'price'            => '',
					'custom_css_class' => ''
				), $atts 
			) 
		);
		
		$output = '
			<div class="card py-3 px-xl-3 '. $custom_css_class .' pricing-card">
				<div class="card-body">
					<span class="h6 d-block">'. $title .'</span>
					<span class="d-block display-4 mb-2">'. $price .'</span>
					'. do_shortcode( $content ) .'
				</div>
			</div>
		';
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_pricing', 'tommusrhodus_pricing_shortcode' );
}

/**
 * tommusrhodus_pricing_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pricing_shortcode_vc' ) )){
	function tommusrhodus_pricing_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Pricing Cards", 'tommusrhodus' ),
				"base"     => "tommusrhodus_pricing",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type"        => "textfield",
						"heading"     => __( "Card Title", 'tommusrhodus' ),
						"param_name"  => "title",
						'holder'      => 'div'
					),
					array(
						"type"        => "textfield",
						"heading"     => __( "Card Price", 'tommusrhodus' ),
						"param_name"  => "price",
						'holder'      => 'div'
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
	add_action( 'vc_before_init', 'tommusrhodus_pricing_shortcode_vc' );
}