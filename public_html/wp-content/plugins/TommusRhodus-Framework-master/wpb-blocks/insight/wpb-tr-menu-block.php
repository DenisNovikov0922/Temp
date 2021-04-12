<?php 

if(!( function_exists( 'tommusrhodus_tommusrhodus_nav_menu_shortcode' ) )){
	function tommusrhodus_tommusrhodus_nav_menu_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'menu'					=> 'footer-navigation',
					'custom_css_class'		=> ''
				), $atts 
			) 
		);

		ob_start();

		the_widget( 'TommusRhodus_Nav_Menu_Widget' , 'tommusrhodus_nav_menu='.$menu );

		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_nav_menu', 'tommusrhodus_tommusrhodus_nav_menu_shortcode' );
}

/**
 * tommusrhodus_tommusrhodus_nav_menu_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_tommusrhodus_nav_menu_shortcode_vc' ) )){
	function tommusrhodus_tommusrhodus_nav_menu_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Menu Widget", 'tommusrhodus' ),
				"base"     => "tommusrhodus_nav_menu",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type" => "dropdown",
						"heading" => esc_html__("Select a Menu", 'tommusrhodus'),
						"param_name" => "menu",
						"value" => array_flip( tommusrhodus_get_all_wordpress_menus() ),
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
	add_action( 'vc_before_init', 'tommusrhodus_tommusrhodus_nav_menu_shortcode_vc' );
}