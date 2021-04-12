<?php 

/**
 * tommusrhodus_breadcrumbs_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_breadcrumbs_shortcode' ) )){
	function tommusrhodus_breadcrumbs_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'custom_css_class' => ''
				), $atts 
			) 
		);

		return get_tommusrhodus_breadcrumbs( 'breadcrumb p-0 bg-transparent ' . $custom_css_class );
		
	}
	add_shortcode( 'tommusrhodus_breadcrumbs', 'tommusrhodus_breadcrumbs_shortcode' );
}

/**
 * tommusrhodus_breadcrumbs_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_breadcrumbs_shortcode_vc' ) )){
	function tommusrhodus_breadcrumbs_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Breadcrumbs", 'tommusrhodus' ),
				"base"     => "tommusrhodus_breadcrumbs",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
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
	add_action( 'vc_before_init', 'tommusrhodus_breadcrumbs_shortcode_vc' );
}