<?php 


function tommusrhodus_append_menu_button( $items, $args ) {
	
	$label = get_theme_mod( 'header_button_label', 'Purchase Now' );
	$url   = get_theme_mod( 'header_button_url' ) ;
	
    if( $args->theme_location == 'primary' && $label && $url ){
        $items .= '<li class="d-block d-sm-none nav-item"><a href="'. esc_url( $url ) .'" class="nav-link">'. wp_kses_post( $label ) .'</a></li>';
    }
    
    return $items;
    
}
add_filter( 'wp_nav_menu_items', 'tommusrhodus_append_menu_button', 10, 2 );

function tommusrhodus_filter_pre_get_posts( $query ) {
	
    if( !$query->is_main_query() ) {
        return $query;
    }
    
    $layout    = get_theme_mod( 'blog_layout', 'listing-2' );
    $page      = $query->get( 'paged' );
    $pppage    = get_option( 'posts_per_page' );
    
    if( !( 'listing-1' == $layout ) ){
    	return $query;
    }
    
    if( !is_home() && !is_category() && !is_tag() ){
    	return $query;
    }
    
    if( $page <= 1 ){
    	return $query;
    }
    
    $div   = $pppage / 3;
    $round = round( $div );
    $final = $round * 3;
    
    $query->set( 'posts_per_page', $final );
    
    return $query;

}
add_filter( 'pre_get_posts', 'tommusrhodus_filter_pre_get_posts', 10, 1 );

/**
 * tommusrhodus_get_header_layout
 * 
 * Use to conditionally check the page header meta layout against the theme option for the same
 * In short, this function can override the global header option on a post by post basis
 * Call within get_header() for this to override the global header choice
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists( 'tommusrhodus_filter_header_layout' ) )){ 
	function tommusrhodus_filter_header_layout( $value ){
		
		// Exit early if this is the 404 page
		if( is_404() ){
			return 'none';
		}
		
		if( is_post_type_archive('post') || is_home() || is_search() ){
			$header = get_theme_mod('post_archive_header_layout', 'white');
			return $header;
		}

		if( is_post_type_archive('documentation') || is_tax('documentation_category') ){
			$header = get_theme_mod('documentation_archive_header_layout', 'white');
			return $header;
		}

		if( is_post_type_archive('portfolio') || is_tax('portfolio_category') ){
			$header = get_theme_mod('portfolio_archive_header_layout', 'white');
			return $header;
		}

		if( is_post_type_archive('testimonial') || is_tax('testimonial_category') ){
			$header = get_theme_mod('testimonial_archive_header_layout', 'white');
			return $header;
		}

		if( is_post_type_archive('team') || is_tax('team_category') ){
			$header = get_theme_mod('team_archive_header_layout', 'white');
			return $header;
		}

		global $post;
		
		// Exit early if we don't have a post ID to work from
		if( !isset( $post->ID ) ){
			return $value;
		}
		
		// Get the header override meta
		$header = get_post_meta( $post->ID, '_tommusrhodus_header_override', 1 );
		
		// If the override is not empty or set to none, then assign the theme mod value to the override
		if(!( '' == $header || false == $header || 'none' == $header )){
			$value = $header;
		}
		
		return $value;
		
	}
	add_filter( 'theme_mod_header_layout', 'tommusrhodus_filter_header_layout', 10, 1 );
}

if(!( function_exists( 'tommusrhodus_filter_footer_layout' ) )){ 
	function tommusrhodus_filter_footer_layout( $value ){
		
		if( is_404() ){
			$value = 'none';
		}

		global $post;
		
		// Exit early if we don't have a post ID to work from
		if( !isset( $post->ID ) ){
			return $value;
		}
		
		// Get the footer override meta
		$footer = get_post_meta( $post->ID, '_tommusrhodus_footer_override', 1 );
		
		// If the override is not empty or set to none, then assign the theme mod value to the override
		if(!( '' == $footer || false == $footer || 'none' == $footer )){
			$value = $footer;
		}
	
		return $value;
		
	}
	add_filter( 'theme_mod_footer_layout', 'tommusrhodus_filter_footer_layout', 10, 1 );
}

if(!( function_exists( 'tommusrhodus_filter_footer_cta_background_style' ) )){ 
	function tommusrhodus_filter_footer_cta_background_style( $value ){
		
		// Exit early if this is the 404 page
		if( is_404() ){
			return 'none';
		}

		if( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ){
			$footer = get_theme_mod( 'portfolio_cta_background_style', 'bg-white' );
			return $footer;
		}

		global $post;
		
		// Exit early if we don't have a post ID to work from
		if( !isset( $post->ID ) ){
			return $value;
		}
		
		// Get the footer override meta
		$footer = get_post_meta( $post->ID, '_tommusrhodus_cta_background_color_override', 1 );
		
		// If the override is not empty or set to none, then assign the theme mod value to the override
		if(!( '' == $footer || false == $footer || 'none' == $footer )){
			$value = $footer;
		}
		
		return $value;
		
	}
	add_filter( 'theme_mod_footer_cta_background_style', 'tommusrhodus_filter_footer_cta_background_style', 10, 1 );
}

if(!( function_exists( 'tommusrhodus_filter_footer_cta' ) )){ 
	function tommusrhodus_filter_footer_cta( $value ){
		
		// Exit early if this is the 404 page
		if( is_404() ){
			return 'none';
		}

		if( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ){
			$header = get_theme_mod( 'portfolio_cta' );
			if( $header ) {
				return $header;
			} else {
				return false;
			}		
		}
		
		global $post;
		
		// Exit early if we don't have a post ID to work from
		if( !isset( $post->ID ) ){
			return $value;
		}
		
		// Get the footer override meta
		$header = get_post_meta( $post->ID, '_tommusrhodus_cta_override', 1 );
		
		// If the override is not empty or set to none, then assign the theme mod value to the override
		if(!( '' == $header || false == $header || 'none' == $header )){
			$value = $header;
		} elseif( empty( $header ) ) {
			return false;
		}
		
		return $value;
		
	}
	add_filter( 'theme_mod_footer_cta', 'tommusrhodus_filter_footer_cta', 10, 1 );
}

if(!( function_exists( 'tommusrhodus_filter_footer_form_shortcode' ) )){ 
	function tommusrhodus_filter_footer_form_shortcode( $value ){
		
		// Exit early if this is the 404 page
		if( is_404() ){
			return 'none';
		}

		if( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ){
			$header = get_theme_mod( 'portfolio_cta_form_shortcode',  get_theme_mod( 'footer_cta_form_shortcode') );
			return $header;
		}
		
		global $post;
		
		// Exit early if we don't have a post ID to work from
		if( !isset( $post->ID ) ){
			return $value;
		}
		
		// Get the footer override meta
		$header = get_post_meta( $post->ID, '_tommusrhodus_cta_form_shortcode_override', 1 );
		
		// If the override is not empty or set to none, then assign the theme mod value to the override
		if(!( '' == $header || false == $header || 'none' == $header )){
			if( $header ) {
				return $header;
			} else {
				return false;
			}
		}
		
		return $value;
		
	}
	add_filter( 'theme_mod_footer_footer_form_shortcode', 'footer_form_shortcode', 10, 1 );
}

if(!( function_exists( 'tommusrhodus_the_wp_login_url' ) )){ 
	function tommusrhodus_the_wp_login_url( $url ) {
	    return esc_url( home_url( '/' ) );
	}
	add_filter( 'login_headerurl', 'tommusrhodus_the_wp_login_url', 10, 1 );
}

function tommusrhodus_tag_cloud_class( $tags_data ) {

    foreach( $tags_data as $key => $tag ) {
        $tags_data[$key]['class'] = 'badge badge-primary';
    }
    return $tags_data;
}

add_filter( 'wp_generate_tag_cloud_data', 'tommusrhodus_tag_cloud_class', 10, 1 );

/**
 * Turn off autop behaviour in Contact Form 7
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

function tommusrhodus_add_body_class( $classes ){

	global $post;

	$classes[] = 'window-loading';
	
	if( is_active_sidebar( 'primary' ) && is_single() ){
		$classes[] = 'has-sidebar';
	}
	
	if( 'no' == get_theme_mod( 'use_sticky_header', 'yes' ) ){
		$classes[] = 'disable-sticky-menu';
	}
	
	$classes[] = get_theme_mod( 'menu_drop_type', 'click-menu' );

	if( 'yes' == get_post_meta( $post->ID, '_tommusrhodus_show_side_menu', 1 ) || !empty( get_post_meta( $post->ID, '_tommusrhodus_show_side_menu', 1 ) ) ) {
	 	$classes[] = '" data-spy="scroll" data-target="#side-menu';
	} 
	
	return $classes;
	
}
add_filter( 'body_class', 'tommusrhodus_add_body_class', 100, 1 );

/**
 * tommusrhodus_modify_custom_logo_classes()
 * 
 * Modify custom logo string to change the class.
 * 
 * @param $html -- Logo HTML (String)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_modify_custom_logo_classes' ) )){ 
	function tommusrhodus_modify_custom_logo_classes( $html ){
	
		if( is_search() ){
			return str_replace( 'custom-logo-link', 'navbar-brand', $html );
		}

		if( is_post_type_archive('post') || is_home() ){
			$custom_logo = get_theme_mod( 'post_archive_logo' );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page"><img src="'. esc_url( $custom_logo  ) .'" class="custom-logo" alt="'. esc_attr( 'Logo' , 'jumpstart' ) .'" /></a>';
			} else {
				return str_replace( 'custom-logo-link', 'navbar-brand', $html );
			}
		}

		if( is_post_type_archive('documentation') || is_tax('documentation_category') ){
			$custom_logo = get_theme_mod( 'documentation_archive_logo' );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page"><img src="'. esc_url( $custom_logo  ) .'" class="custom-logo" alt="'. esc_attr( 'Logo' , 'jumpstart' ) .'" /></a>';
			} else {
				return str_replace( 'custom-logo-link', 'navbar-brand', $html );
			}
		}


		if( is_post_type_archive('testimonial') || is_tax('testimonial_category') ){
			$custom_logo = get_theme_mod( 'testimonial_archive_logo' );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page"><img src="'. esc_url( $custom_logo  ) .'" class="custom-logo" alt="'. esc_attr( 'Logo' , 'jumpstart' ) .'" /></a>';
			} else {
				return str_replace( 'custom-logo-link', 'navbar-brand', $html );
			}
		}

		if( is_post_type_archive('team') || is_tax('team_category') ){
			$custom_logo = get_theme_mod( 'team_archive_logo' );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page"><img src="'. esc_url( $custom_logo  ) .'" class="custom-logo" alt="'. esc_attr( 'Logo' , 'jumpstart' ) .'" /></a>';
			} else {
				return str_replace( 'custom-logo-link', 'navbar-brand', $html );
			}
		}

		if( is_404() ){
			$custom_logo = get_theme_mod( '404_logo' );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page mr-0"><img src="'. esc_url( $custom_logo  ) .'" class="custom-logo" alt="'. esc_attr( 'Logo' , 'jumpstart' ) .'" /></a>';
			} else {
				return str_replace( 'custom-logo-link', 'navbar-brand', $html );
			}
		}
		
		global $post;
		
		if( isset( $post ) ){
			
			$custom_logo = get_post_meta( $post->ID, '_tommusrhodus_logo_override_id', 1 );
			
			if( !empty( $custom_logo ) ){
				return '<a href="'. esc_url( home_url( '/' ) ) .'" class="navbar-brand fade-page">'. wp_get_attachment_image( $custom_logo, 'full' ) .'</a>';
			}
			
		}
		
		if( empty( $html ) ){
			$html = '<a class="navbar-brand fade-page" href="'. esc_url( home_url( '/' ) ) .'">'. get_bloginfo( 'title' ) .'</a>';
		}
		
		return str_replace( 'custom-logo-link', 'navbar-brand', $html );
		
	}
	add_filter( 'get_custom_logo', 'tommusrhodus_modify_custom_logo_classes' );
}

/**
 * tommusrhodus_nav_menu_classes()
 * 
 * Ensures all menu items have proper classes applied, even in widgets etc.
 * 
 * @param $items -- Collection of menu items (Array)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
function tommusrhodus_nav_menu_classes( $items ) {
    _wp_menu_item_classes_by_context( $items );
    return $items;
}
add_filter( 'wp_get_nav_menu_items', 'tommusrhodus_nav_menu_classes', 10, 1 );

/**
 * tommusrhodus_replace_tag_class()
 * 
 * Add additional classes to the tags for a post.
 * 
 * @param $class -- Reply link (String)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_replace_tag_class' ) )){
	function tommusrhodus_replace_tag_class( $html ){
	    return str_replace( 'rel="tag"', 'class="badge badge-primary mr-1 mb-1" rel="tag"', $html );
	}
	add_filter( 'the_tags', 'tommusrhodus_replace_tag_class', 20, 1 );
}

/**
 * tommusrhodus_excerpt_length()
 * 
 * Add additional classes to comment reply link for styling.
 * 
 * @documentation https://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length
 * @param $length -- Excerpt Trim Length (Int)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_excerpt_length' ) )){
	function tommusrhodus_excerpt_length( $length ) {
		return 15;
	}
	add_filter( 'excerpt_length', 'tommusrhodus_excerpt_length', 90, 1 );
}

/**
 * tommusrhodus_excerpt_more()
 * 
 * Add additional classes to comment reply link for styling.
 * 
 * @param $class -- Reply link (String)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_excerpt_more' ) )){
	function tommusrhodus_excerpt_more( $more ) {
		return '...';
	}
	add_filter( 'excerpt_more', 'tommusrhodus_excerpt_more' );
}

/**
 * Add ability to add classes to menu items and menu li items 
 */
if(!( function_exists( 'tommusrhodus_add_menu_link_class' ) )) {
	function tommusrhodus_add_menu_link_class( $atts, $item, $args ) {
	  	if (property_exists($args, 'link_class')) {
	    	$atts['class'] = $args->link_class;
	  	}
	  	return $atts;
	}
	add_filter( 'nav_menu_link_attributes', 'tommusrhodus_add_menu_link_class', 1, 3 );
}

if(!( function_exists( 'tommusrhodus_add_menu_list_item_class' ) )) {
	function tommusrhodus_add_menu_list_item_class($classes, $item, $args) {
		if (property_exists($args, 'list_item_class')) {
			$classes[] = $args->list_item_class;
		}
		return $classes;
	}
	add_filter('nav_menu_css_class', 'tommusrhodus_add_menu_list_item_class', 1, 3 );
}

/**
 * Add a clearfix to the end of the_content()
 */
if(!( function_exists('tommusrhodus_add_clearfix') )){ 
	function tommusrhodus_add_clearfix( $content ) { 
	    return $content . '<div class="clearfix"></div>';
	}
	add_filter( 'the_content', 'tommusrhodus_add_clearfix' ); 
}

/**
 * Add data attributes for Fancybox
 */
if(!( function_exists( 'tommusrhodus_fancybox_gallery_attribute' ) )){
	function tommusrhodus_fancybox_gallery_attribute( $content, $id, $size, $permalink, $icon, $text ) {
	    if ($permalink) {
	    	return $content;    
	    }
		// Restore title attribute
		$title = get_the_title( $id );
		return str_replace('<a', '<a data-fancybox="fancy-gallery" data-options="{&quot;loop&quot;:true}" title="' . esc_attr( $title ) . '" ', $content);
	}
	add_filter( 'wp_get_attachment_link', 'tommusrhodus_fancybox_gallery_attribute', 10, 6 );
}

if(!( function_exists( 'tommusrhodus_fancybox_image_attribute' ) )){
	function tommusrhodus_fancybox_image_attribute( $content ) {
	       global $post;
	       $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
	       $replace = '<a$1href=$2$3.$4$5 data-fancybox>';
	       $content = preg_replace( $pattern, $replace, $content );
	       return $content;
	}
	add_filter( 'the_content', 'tommusrhodus_fancybox_image_attribute' );
}


/**
 * Add classes to wrappers of CF7 form elements
 */
if(!( function_exists('tommusrhodus_filter_contact_form_7') )){ 
	function tommusrhodus_filter_contact_form_7( $output, $tag ) {
		
		//IF we're not dealing with contact form 7
		if ( 'contact-form-7' !== $tag ) {
			return $output;
		}
		
		$search = array(
			'wpcf7-checkbox',	
			'wpcf7-radio',		
			'<input type="radio"',
			'<input type="checkbox"',
			'"><label></label><input type="radio"',
			'"><label></label><input type="checkbox"',
		);
		
		$replace = array(
			'wpcf7-checkbox custom-control custom-checkbox mb-3',			
			'wpcf7-radio custom-control custom-radio mb-3',		
			'<label></label><input type="radio"',
			'<label></label><input type="checkbox"',
			' input-radio"><label class="custom-control-label"></label><input type="radio" class="custom-control-input"',
			' input-checkbox"><label class="custom-control-label"></label><input type="checkbox" class="custom-control-input"',
		);
		
		$output = str_replace($search, $replace, $output);
		
		//If we ARE dealing with contact form 7
		return $output;
	}
	add_filter('do_shortcode_tag', 'tommusrhodus_filter_contact_form_7', 999, 2);
}


/**
 * Add a clearfix to the end of the_content()
 */
if(!( function_exists('tommusrhodus_add_clearfix') )){ 
	function tommusrhodus_add_clearfix( $content ) { 
	    return $content . '<div class="clearfix"></div>';
	}
	add_filter( 'the_content', 'tommusrhodus_add_clearfix' ); 
}

/**
 * Customize password protection form
 */
if(!( function_exists('tommusrhodus_password_form') )){ 
	function tommusrhodus_password_form() {
		
	    global $post;
	    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );

	    $output = '
	    ' . esc_html__( 'This content is password protected. To view it please enter your password below:' , 'jumpstart' ) . '
	    <form class="protected-post-form d-flex" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">	    	
	    	<div class="input-group input-group-lg mr-2 mt-2">
	    		<input name="post_password" id="' . $label . '" type="password" class="form-control" size="20" placeholder="'. esc_html__( 'Enter Password', 'jumpstart' ) .'"/>	    		
	    	</div>
	    	<input type="submit" name="Submit" class="button btn btn-primary btn-lg mt-2" value="' . esc_attr__( 'Submit' , 'jumpstart' ) . '" />
	    </form>
	    ';

	    return $output;
	}
	add_filter( 'the_password_form', 'tommusrhodus_password_form' );
}

/* ALLOW DATA SCROLL ATTR ON RELEVANT MENU ITEMS */
if(!( function_exists('tommusrhodus_smooth_nav_menu_link_attributes') )){ 
	function tommusrhodus_smooth_nav_menu_link_attributes( $atts, $item, $args ) {
	    if ( 'smooth-scroll' === $item->classes[0] ) {
	        $atts['data-smooth-scroll'] = ' ';
	        $atts['class'] = str_replace('fade-page', '', $atts['class']);
	    }

	    return $atts;
	};

	add_filter( 'nav_menu_link_attributes', 'tommusrhodus_smooth_nav_menu_link_attributes', 10, 3 );
}
