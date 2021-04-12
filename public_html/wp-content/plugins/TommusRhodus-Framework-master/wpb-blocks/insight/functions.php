<?php 

if(!( function_exists( 'tommusrhodus_vc_page_template' ) )){
	function tommusrhodus_vc_page_template( $template ){
	
		global $post;
		
		if( is_archive() || is_404() || is_home() || !( isset( $post->post_content ) ) || is_search() ){
			return $template;
		}
			
		if ( has_shortcode( $post->post_content, 'vc_row' ) ){
			
			$new_template = locate_template( array( 'page_wp_bakery.php' ) );
			
			if (!( '' == $new_template )){
				return $new_template;
			}
			
		}
		
		return $template;
		
	}
	add_filter( 'template_include', 'tommusrhodus_vc_page_template', 100, 1 );
}


if( !( function_exists('tommusrhodus_icons_settings_field') ) && function_exists('vc_set_as_theme') ){
	function tommusrhodus_icons_settings_field( $settings, $value ) {
		
		$icons = $settings['value'];
		
		$output = '<a href="#" id="tommusrhodus-icon-toggle" class="button button-primary button-large">Show/Hide Icons</a><div class="tommusrhodus-icons"><div class="tommusrhodus-icons-wrapper">';
		foreach( $icons as $icon ){
			$active = ( $value == $icon) ? ' active' : '';
			$output .= '<i class="icon '. $icon . $active .'" data-icon-class="'. $icon .'"></i>';
		}
		$output .= '</div><input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput tommusrhodus-icon-value ' .
		esc_attr( $settings['param_name'] ) . ' ' .
		esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' . '</div>';
		
	   return $output;
	}
	vc_add_shortcode_param( 'tommusrhodus_icons', 'tommusrhodus_icons_settings_field' );
}

if( !( function_exists('tommusrhodus_vc_alter_class_field_desc') ) && function_exists('vc_set_as_theme') ){
	function tommusrhodus_vc_alter_class_field_desc() {
	 
	    $vc_column_text_new_params = array(
	         
	        array(
	        	"type"        => "textfield",
	    		"heading"     => __( "Extra class name", 'tommusrhodus' ),
	            'param_name' => 'el_class',
	            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS, or you can use one of the themes pre-defined classes - <strong>bg-dark</strong>, <strong>bg-white</strong>, <strong>bg-gradient</strong>, <strong>bg-gradient-1</strong>, <strong>bg-gradient-2</strong>', 'tommusrhodus' ),
	        ),      
	     
	    );
	     
	    vc_add_params( 'vc_section', $vc_column_text_new_params ); 
	         
	}
	add_action( 'vc_after_init', 'tommusrhodus_vc_alter_class_field_desc' );
}

/**
 * Add additional functions to certain blocks.
 * vc_map runs before custom post types and taxonomies are created, so this function is used
 * to add custom taxonomy selectors to VC blocks, a little annoying, but works perfectly.
 */
if( !( function_exists('tommusrhodus_vc_add_att') ) && function_exists('vc_set_as_theme') ){
	function tommusrhodus_vc_add_attr(){
		
		/**
		 * Add blog category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Show all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Blog Category?",
			'param_name' => 'filter',
			'value' => $final_blog_cats
		);
		vc_add_param('tommusrhodus_post', $attributes);

		/**
		 * Add blog featured category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Use all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Which category to use for featured items?",
			'param_name' => 'featured_category',
			'value' => $final_blog_cats
		);
		vc_add_param('tommusrhodus_post', $attributes);

		/**
		 * Add team category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'team_category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Show all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Team Category?",
			'param_name' => 'filter',
			'value' => $final_blog_cats
		);
		vc_add_param('tommusrhodus_team', $attributes);

		/**
		 * Add testimonial category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'testimonial_category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Show all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Testimonial Category?",
			'param_name' => 'filter',
			'value' => $final_blog_cats
		);
		vc_add_param('tommusrhodus_testimonial', $attributes);

		/**
		 * Add portfolio category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'portfolio_category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Show all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Portfolio Category?",
			'param_name' => 'filter',
			'value' => $final_blog_cats
		);
		vc_add_param('tommusrhodus_portfolio_feed', $attributes);
		
	}
	add_action('init', 'tommusrhodus_vc_add_attr', 999);
}

/* TYPED TEXT SHORTCODE */
if(!( function_exists( 'insight_typed_text_shortcode' ) )){
	function insight_typed_text_shortcode( $atts, $content = null ) {
		extract( 
			shortcode_atts( 
				array(
					'intro' => '',
					'outro' => '',
					'text' => '',
					'custom_css_class' => '',
					'size' => 'h4'
				), $atts 
			) 
		);
		
		$output = '
			<div class="typed-headline '. esc_attr($custom_css_class) .'">
				<span class="'. $size .' inline-block">'. $intro .'</span>
				<span class="'. $size .' inline-block typed-text typed-text--cursor color--primary" data-typed-strings="'. $text .'"></span>
				<span class="'. $size .' inline-block">'. $outro .'</span>
			</div>
		';
		return $output;
	}
	add_shortcode( 'insight_typed_text', 'insight_typed_text_shortcode' );
}