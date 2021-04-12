<?php 
/**
 * The Shortcode
 */
function tommusrhodus_documentation_feed_shortcode( $atts ) {
	global $wp_query, $post, $paged;
	
	extract( 
		shortcode_atts( 
			array(
				'pppage' 				=> '4',
				'filter' 				=> 'all',
				'custom_css_class' 		=> '',
				'layout' 				=> '',
				'paging' 				=> 'false',
				'offset' 				=> '0'
			), $atts 
		) 
	);
	
	if( 0 == $pppage || isset($wp_query->doing_documentation_shortcode) ){
		return false;	
	}
	
	if( is_front_page() ) { 
		$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1; 
	} else { 
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
	}
	
	/**
	 * Setup post query
	 */
	$query_args = array(
		'post_type'      => 'documentation',
		'post_status'    => 'publish',
		'posts_per_page' => $pppage,
		'paged'          => $paged
	); 
	
	// Check for and handle offset
	if( $offset ) {
		$query_args['offset'] = $offset;	
	}
	
	//Hide current post ID from the loop if we're in a singular view
	if( is_single() && isset($post->ID) ){
		$query_args['post__not_in']	= array($post->ID);
	}
	
	if (!( $filter == 'all' )) {
		
		//Check for WPML
		if( has_filter('wpml_object_id') ){
			global $sitepress;
			
			//WPML recommended, remove filter, then add back after
			remove_filter('terms_clauses', array($sitepress, 'terms_clauses'), 10, 4);
			
			$filterClass    = get_term_by('slug', $filter, 'documentation_category');
			$ID             = (int) apply_filters('wpml_object_id', (int) $filterClass->term_id, 'documentation_category', true);
			$translatedSlug = get_term_by('id', $ID, 'documentation_category');
			$filter         = $translatedSlug->slug;
			
			//Adding filter back
			add_filter('terms_clauses', array($sitepress, 'terms_clauses'), 10, 4);
		}
		
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'documentation_category',
				'field' => 'slug',
				'terms' => $filter
			)
		);
		
	}
	
	$old_query = $wp_query;
	$old_post = $post;
	$wp_query = new WP_Query( $query_args );
	$wp_query->{"doing_documentation_shortcode"} = 'true';	
	
	ob_start();
	
	echo '<div class="'. $custom_css_class .'">';
	get_template_part( 'loop/loop-documentation', $layout );
	echo '</div>';
	
	$output = ob_get_contents();
	ob_end_clean();
	
	wp_reset_postdata();
	$wp_query = $old_query;
	$post = $post;
	
	return $output;
}
add_shortcode( 'tommusrhodus_documentation_feed', 'tommusrhodus_documentation_feed_shortcode' );
/**
 * The VC Functions
 */
function tommusrhodus_documentation_feed_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
			"name" => esc_html__("Documention Feed", 'tommusrhodus'),
			"base" => "tommusrhodus_documentation_feed",
			"category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
			'description' => 'Show a list of articles.',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => esc_html__("Show How Many Posts?", 'tommusrhodus'),
					"param_name" => "pppage",
					"value" => '4'
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Article Display Type", 'tommusrhodus'),
					"param_name" => "layout",
					"value" => array_flip( tommusrhodus_get_documentation_layouts() ),
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Offset Posts?", 'tommusrhodus'),
					"param_name" => "offset",
					"value" => '0',
					"description" => '<code>DEVELOPERS ONLY</code> - Offset articles shown, 0 for newest articles, 5 starts at fifth most recent etc.'
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
					"param_name" => "custom_css_class",
					"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.',
				),
			)
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_documentation_feed_shortcode_vc');