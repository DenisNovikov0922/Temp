<?php 

/**
 * tommusrhodus_pricing_table_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pricing_table_shortcode' ) )){
	function tommusrhodus_pricing_table_shortcode( $atts, $content = null ){
	
		extract( 
			shortcode_atts( 
				array(
					'title'            => '',
					'price'            => '',
					'currency'         => '',
					'featured'         => '',
					'footer'           => '',
					'small'            => '',
					'custom_css_class' => ''
				), $atts 
			) 
		);
		
		$output = '
			<div class="d-flex '. $custom_css_class .'">
				<div class="card flex-fill text-center shadow-lg">
		';
			
		if( $featured ){	
			$output .= '<span class="badge badge-md badge-success position-absolute"><i class="material-icons">star</i> '. $featured .'</span>';
		}
		
		$output .= '		
					<div class="card-body py-4">
						<div class="mb-3">
						
							<span class="h4 d-block">'. $title .'</span>
							
							<div class="d-flex justify-content-center align-items-center">
								<span class="h5 mb-0">'. $currency .'</span>
								<span class="h1 display-3 mb-0">'. $price .'</span>
							</div>
							
							<span class="text-muted">'. $small .'</span>
							
						</div>
						
						'. do_shortcode( $content ) .'
						
					</div>
					
					<div class="card-footer">
						<span class="text-small">'. $footer .'</span>
					</div>
				
				</div>
			</div>
		';
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_pricing_table', 'tommusrhodus_pricing_table_shortcode' );
}

/**
 * tommusrhodus_pricing_table_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pricing_table_shortcode_vc' ) )){
	function tommusrhodus_pricing_table_shortcode_vc(){
		vc_map( 
			array(
				"icon"     => 'insight-vc-block',
				"name"     => __( "Pricing Table", 'tommusrhodus' ),
				"base"     => "tommusrhodus_pricing_table",
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
						"type"        => "textfield",
						"heading"     => __( "Currency", 'tommusrhodus' ),
						"param_name"  => "currency"
					),
					array(
						"type"        => "textfield",
						"heading"     => __( "Featured Text", 'tommusrhodus' ),
						"param_name"  => "featured"
					),
					array(
						"type"        => "textfield",
						"heading"     => __( "Small Text", 'tommusrhodus' ),
						"param_name"  => "small"
					),
					array(
						"type"        => "textfield",
						"heading"     => __( "Footer Text", 'tommusrhodus' ),
						"param_name"  => "footer"
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
	add_action( 'vc_before_init', 'tommusrhodus_pricing_table_shortcode_vc' );
}