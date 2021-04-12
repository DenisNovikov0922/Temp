<?php 
/**
 * The Shortcode
 */
function tommusrhodus_post_shortcode( $atts ) {
	global $wp_query, $post, $paged;
	
	extract( 
		shortcode_atts( 
			array(
				'pppage' 				=> '4',
				'filter' 				=> 'all',
				'featured_category' 	=> 'all',
				'layout' 				=> '',
				'custom_css_class' 		=> '',
				'paging' 				=> 'false',
				'offset' 				=> '0',
				'show_pagination'		=> 'show'
			), $atts 
		) 
	);
	
	if( 0 == $pppage || isset($wp_query->doing_blog_shortcode) ){
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
		'post_type'      => 'post',
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
			
			$filterClass    = get_term_by('slug', $filter, 'category');
			$ID             = (int) apply_filters('wpml_object_id', (int) $filterClass->term_id, 'category', true);
			$translatedSlug = get_term_by('id', $ID, 'category');
			$filter         = $translatedSlug->slug;
			
			//Adding filter back
			add_filter('terms_clauses', array($sitepress, 'terms_clauses'), 10, 4);
		}
		
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $filter
			)
		);
		
	}

	if (!( $featured_category == 'all' )) {
		$featured_category_id = get_category_by_slug( $featured_category );
		$featured_category_id = $featured_category->term_id;
		$query_args['category__not_in']	= array($featured_category_id);
	} else {
		$featured = get_category_by_slug( get_theme_mod( 'post_archive_featured_posts_category' ) );
		if($featured) {
			$featured_category = $featured->term_id;
			$query_args['category__not_in']	= $featured;
		}
	}
	
	$old_query = $wp_query;
	$old_post = $post;
	$wp_query = new WP_Query( $query_args );
	$wp_query->{"doing_blog_shortcode"} = 'true';	
	$wp_query->{"featured_category"} =  $featured_category;	
	$wp_query->{"show_pagination"} = $show_pagination;	
	
	ob_start();
	
	echo '<div class="'. $custom_css_class .'">';
	get_template_part('loop/loop-post', $layout);
	echo '</div>';
	
	$output = ob_get_contents();
	ob_end_clean();
	
	wp_reset_postdata();
	$wp_query = $old_query;
	$post = $old_post;
	
	return $output;
}
add_shortcode( 'tommusrhodus_post', 'tommusrhodus_post_shortcode' );
/**
 * The VC Functions
 */
function tommusrhodus_post_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
			"name" => esc_html__("Blog Feed", 'tommusrhodus'),
			"base" => "tommusrhodus_post",
			"category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
			'description' => 'Show post posts with layout options.',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => esc_html__("Show How Many Posts?", 'tommusrhodus'),
					"param_name" => "pppage",
					"value" => '4'
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Post Display Type", 'tommusrhodus'),
					"param_name" => "layout",
					"value" => array_flip( tommusrhodus_get_blog_layouts() ),
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Offset Posts?", 'tommusrhodus'),
					"param_name" => "offset",
					"value" => '0',
					"description" => '<code>DEVELOPERS ONLY</code> - Offset posts shown, 0 for newest posts, 5 starts at fifth most recent etc.'
				),
				array(
		    		"type" => "dropdown",
		    		"heading" => esc_html__("Show Pagignation?", 'brailie'),
		    		"param_name" => "show_pagination",
		    		"value" => array(
		    			'Yes' 	=> 'show',
		    			'No' 	=> 'hide',
		    		)
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
add_action( 'vc_before_init', 'tommusrhodus_post_shortcode_vc');