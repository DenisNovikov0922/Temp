<?php 

/**
 * tommusrhodus_searchform_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_searchform_shortcode' ) )){
	function tommusrhodus_searchform_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'custom_css_class' => '',
					'text'			   => ''
				), $atts 
			) 
		);

		$searches = explode( ',', $text );

		ob_start();

		echo '<div class="'. $custom_css_class .'">';

			get_template_part( 'inc/content' , 'large-searchform' );

			if( !empty( $searches )) {

				echo '<ul class="list-unstyled d-flex flex-wrap mt-2 mb-0">';
					foreach ( $searches as $key => $value ) {
						echo '<li class="mr-2 mb-2 mb-lg-0"><a href="' . esc_url( home_url( '/?s=' ) ) . str_replace( ' ', '+', $value ) . '" class="btn btn-sm btn-light">' . wp_kses_post( $value ) . '</a></li>';
					}
				echo '</ul>';
				
			}

		echo '</div>';

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
		
	}
	add_shortcode( 'tommusrhodus_searchform', 'tommusrhodus_searchform_shortcode' );
}

/**
 * tommusrhodus_searchform_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_searchform_shortcode_vc' ) )){
	function tommusrhodus_searchform_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Search Form", 'tommusrhodus' ),
				"base"     => "tommusrhodus_searchform",
				"category" => __( 'Insight WP Theme', 'tommusrhodus' ),
				"params"   => array(
					array(
						"type"        => "textfield",
						"heading"     => __( "Extra CSS Class Name", 'tommusrhodus' ),
						"param_name"  => "custom_css_class",
						"description" => __( '<code>FOR DEVELOPERS</code> Add a class name and refer to it in custom CSS / JS', 'tommusrhodus' ),
					),
					array(
						"type" => "exploded_textarea",
						"heading" => esc_html__("Popular Search Terms", 'tommusrhodus'),
						"param_name" => "text",
						"description" => '1 per line, multiple words per line are fine, add a new line for each new pre defined search you wish to add.',
					),
				)
			) 
		);
	}
	add_action( 'vc_before_init', 'tommusrhodus_searchform_shortcode_vc' );
}