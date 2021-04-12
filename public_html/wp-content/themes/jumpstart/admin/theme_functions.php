<?php

/**
 * WordPress' missing is_blog_page() function.  Determines if the currently viewed page is
 * one of the blog pages, including the blog home page, archive, category/tag, author, or single
 * post pages.
 *
 * @see http://core.trac.wordpress.org/browser/tags/3.4.1/wp-includes/query.php#L1572
 *
 * @return bool
 */
function tommusrhodus_is_blog_page(){ 
    global $post;
    $post_type = get_post_type( $post );
    return ( ( is_home() || is_archive() || is_single() ) && ( $post_type == 'post' ) ) ? true : false ;
}

function tommusrhodus_page_title( $suptitle = false, $title = false, $subtitle = false ){
	
	// Escape early if none of the atts have values
	if( false == $suptitle && false == $title && false == $subtitle ){
		return false;
	}
	
	$output = '
		<div class="row justify-content-center text-center section-intro">
		    <div class="col-12 col-md-9 col-lg-8">
	';

		if( $suptitle ){
    		$output .= '<span class="title-decorative">'. wp_kses_post( $suptitle ) .'</span>';
		}
		
		if( $title ){
			$output .= '<h2 class="display-4">'. wp_kses_post( $title ) .'</h2>';
		}
		
		if( $subtitle ){
			$output .= '<span class="lead">'. wp_kses_post( $subtitle ) .'</span>';
		}
					
	$output .= '
		    </div><!--end of col-->
		</div><!--end of row-->
	';
	
	return $output;
	
}

/**
 * tommusrhodus_update_docs_upvotes()
 * 
 * Update the post upvote count from an AJAX call.
 * Returns the updated count number.
 * 
 * @param $docs_id -- ID of the docs object
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_update_docs_upvotes' ) )){
	function tommusrhodus_update_docs_upvotes(){

		if(!( isset( $_POST['docs_id'] ) )){
			wp_die();
		}
		
		$docs_id = $_POST['docs_id'];
		$count   = get_post_meta( $docs_id, 'tommusrhodus_docs_upvotes', 1 ) + 1;
		
		update_post_meta( $docs_id, 'tommusrhodus_docs_upvotes', $count );
		
		wp_die( $count );
		
	}
	add_action( 'wp_ajax_tommusrhodus_update_docs_upvotes', 'tommusrhodus_update_docs_upvotes' );
	add_action( 'wp_ajax_nopriv_tommusrhodus_update_docs_upvotes', 'tommusrhodus_update_docs_upvotes' );
}

/**
 * tommusrhodus_update_docs_downvotes()
 * 
 * Update the post upvote count from an AJAX call.
 * Returns the updated count number.
 * 
 * @param $docs_id -- ID of the docs object
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_update_docs_downvotes' ) )){
	function tommusrhodus_update_docs_downvotes(){

		if(!( isset( $_POST['docs_id'] ) )){
			wp_die();
		}
		
		$docs_id = $_POST['docs_id'];
		$count   = get_post_meta( $docs_id, 'tommusrhodus_docs_downvotes', 1 ) + 1;
		
		update_post_meta( $docs_id, 'tommusrhodus_docs_downvotes', $count );
		
		wp_die( $count );
		
	}
	add_action( 'wp_ajax_tommusrhodus_update_docs_downvotes', 'tommusrhodus_update_docs_downvotes' );
	add_action( 'wp_ajax_nopriv_tommusrhodus_update_docs_downvotes', 'tommusrhodus_update_docs_downvotes' );
}

/**
 * tommusrhodus_register_required_plugins()
 * 
 * Register the required and recommended plugins for the theme.
 * Uses TGMPA for plugin installation and activation.
 * 
 * @documentation http://tgmpluginactivation.com
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_register_required_plugins' ) )){
	function tommusrhodus_register_required_plugins() {
	
		$plugins = array(
			array(
			    'name'      => esc_html__( 'Contact Form 7', 'jumpstart' ),
			    'slug'      => 'contact-form-7',
			    'required'  => false,
			    'version' 	=> '3.7.2'
			),
			array(
			    'name'      => esc_html__( 'Custom Metaboxes 2', 'jumpstart' ),
			    'slug'      => 'cmb2',
			    'required'  => true,
			    'version' 	=> '1.0.0'
			),
			array(
			    'name'      => esc_html__( 'WP Job Manager', 'jumpstart' ),
			    'slug'      => 'wp-job-manager',
			    'required'  => false,
			    'version' 	=> '1.0.0'
			),
			array(
			    'name'      => esc_html__( 'Elementor', 'jumpstart' ),
			    'slug'      => 'elementor',
			    'required'  => false,
			    'version' 	=> '1.0.0'
			),
			array(
				'name'     				=> esc_html__( 'TommusRhodus Framework', 'jumpstart' ),
				'slug'     				=> 'TommusRhodus-Framework-master',
				'source'   				=> 'https://github.com/tommusrhodus/tommusrhodus-framework/archive/master.zip',
				'required' 				=> true,
				'version' 				=> '1.0.0',
				'external_url' 			=> 'https://github.com/tommusrhodus/tommusrhodus-framework/archive/master.zip',
			)
		);
		
		tgmpa( $plugins );
		
	}
	add_action( 'tgmpa_register', 'tommusrhodus_register_required_plugins' );
}

/**
 * get_tommusrhodus_breadcrumbs()
 * 
 * Builds the breadcrumb markup for the theme.
 * Uses the current post to create a tiered breadcrumb.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'get_tommusrhodus_breadcrumbs' ) )){ 
	function get_tommusrhodus_breadcrumbs( $class = 'breadcrumb' ){
	
		if ( is_front_page() || is_search() ){
			return;
		}
		
		global $post;
		
		$post_type = get_post_type();
		$ancestors = false;
		
		if( isset( $post->ID ) ){
			$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
		}
		
		$displays  = get_option( 'tommusrhodus_framework_cpt_display_options' );
		$before    = '<nav aria-label="breadcrumb"><ol class="'. $class .'">';
		$after     = '</ol></nav>';
		$home      = '<li class="breadcrumb-item"><a href="' . esc_url( home_url( "/" ) ) . '" class="home-link" rel="home">' . esc_html__( 'Home', 'jumpstart' ) . '</a></li>';
		
		if( 'portfolio' == $post_type ){
			$slug  = get_option( 'tommusrhodus_portfolio_slug', 'portfolio' );
			$title = str_replace( array( '-', '_' ), ' ', $slug );
			$home .= '<li class="breadcrumb-item active"><a href="'. esc_url( home_url( $slug ) ) .'">' . ucwords( $title ) . '</a></li>';
		}
		
		if( 'team' == $post_type ){
			$slug  = get_option( 'tommusrhodus_team_slug', 'team' );
			$title = str_replace( array( '-', '_' ), ' ', $slug );
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( $slug ) ) . '">' . ucwords( $title ) . '</a></li>';
		}
		
		if( 'career' == $post_type ){
			$slug = get_option('tommusrhodus_careers_slug', 'careers');
			$title = str_replace( array( '-', '_' ), ' ', $slug );
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( $slug ) ) . '">' . ucwords( $title ) . '</a></li>';
		}

		if( 'testimonial' == $post_type ){
			$slug = get_option('tommusrhodus_testimonials_slug', 'testimonials');
			$title = str_replace( array( '-', '_' ), ' ', $slug );
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( $slug ) ) . '">' . ucwords( $title ) . '</a></li>';
		}
		
		if( 'documentation' == $post_type ){
			$slug = get_option('tommusrhodus_documentation_slug', 'documentation');
			$title = str_replace( array( '-', '_' ), ' ', $slug );
			$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( home_url( $slug ) ) . '">' . ucwords( $title ) . '</a></li>';
		}
		
		if( class_exists('woocommerce') ){
			if( 'product' == $post_type && !(is_shop()) ){
				$home .= '<li class="breadcrumb-item active"><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'jumpstart' ) . '</a></li>';
			} elseif( 'product' == $post_type && is_archive() ) {
				$home .= '<li class="breadcrumb-item active">' . esc_html__( 'Shop', 'jumpstart' ) . '</li>';
			}
		}
		
		$breadcrumb = '';
		if ( $ancestors ) {
			foreach ( $ancestors as $ancestor ) {
				$breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url( get_permalink( $ancestor ) ) . '">'. get_the_title( $ancestor ) .'</a></li>';
			}
		}
		
		if( tommusrhodus_is_blog_page() && is_single() ){
			$breadcrumb .= '<li class="breadcrumb-item"><a href="' . esc_url( get_post_type_archive_link( 'post' ) ) . '">'. get_theme_mod( 'post_archive_title', 'Our Blog' ) .'</a></li><li class="breadcrumb-item active">'. get_the_title( $post->ID ) .'</li>';
		} elseif( tommusrhodus_is_blog_page() ){
			$breadcrumb .= '<li class="breadcrumb-item active">'. get_theme_mod( 'post_archive_title', 'Our Blog' ) .'</li>';
		} elseif( is_post_type_archive('product') || is_archive() ){		
			$breadcrumb .= '<li class="breadcrumb-item">'. single_cat_title('', false) .'</li>';		
		} else {
			$breadcrumb .= '<li class="breadcrumb-item active">' . get_the_title( $post->ID ) . '</li>';
		}
		
		return $before . $home . $breadcrumb . $after;
		
	}
}

/**
 * Add additional styling options to TinyMCE
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('tommusrhodus_mce_buttons_2') )){
	function tommusrhodus_mce_buttons_2( $buttons ) {
	    array_unshift( $buttons, 'styleselect' );
	    return $buttons;
	}
	add_filter( 'mce_buttons_2', 'tommusrhodus_mce_buttons_2' );
}

/**
 * Add additional styling options to TinyMCE
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('tommusrhodus_mce_before_init') )){
	function tommusrhodus_mce_before_init( $settings ) {
	    $style_formats = array(
	    	array(
				'title'	=> 'Button Styles',
				'items'	=> array(
			    	array(
			    		'title' => 'Button',
			    		'selector' => 'a',
			    		'classes' => 'btn',
			    	),
			    	array(
			    		'title' => 'Button Filled',
			    		'selector' => 'a',
			    		'classes' => 'btn btn-primary',
			    	),
			    	array(
			    		'title' => 'Button Filled Block',
			    		'selector' => 'a',
			    		'classes' => 'btn btn-block',
			    	),
			    	array(
			    		'title' => 'Button Filled Large',
			    		'selector' => 'a',
			    		'classes' => 'btn btn-primary btn-lg',
			    	),
			    	array(
			    		'title' => 'Button Outline Large',
			    		'selector' => 'a',
			    		'classes' => 'btn btn-lg btn-outline-primary',
			    	),
			    	array(
			    		'title' => 'Button Large Filled Block',
			    		'selector' => 'a',
			    		'classes' => 'btn btn-lg btn-block',
			    	)
				)
	    	),
	    	array(
				'title'	=> 'Link Styles',
				'items'	=> array(
			    	array(
			    		'title' => 'Arrow',
			    		'selector' => 'a',
			    		'classes' => 'hover-arrow',
			    	),
			    	array(
			    		'title' => 'Arrow Lead',
			    		'selector' => 'a',
			    		'classes' => 'lead hover-arrow',
			    	)
				)
	    	),
	    	array(
	    		'title'	=> 'Margins',
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => 'Zero Bottom Margin',
	    	    		'selector' => '*',
	    	    		'classes' => 'mb0',
	    	    	)
	    		)
	    	),
	    	array(
	    		'title'	=> 'List Styles',
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => 'Bulleted List',
	    	    		'selector' => 'ul',
	    	    		'classes' => 'bullets',
	    	    	),
	    	    	array(
	    	    		'title' => 'Numbered List',
	    	    		'selector' => 'ol',
	    	    		'classes' => 'numbered',
	    	    	),
	    	    	array(
	    	    		'title' => 'Vertical Menu',
	    	    		'selector' => 'ul',
	    	    		'classes' => 'menu-vertical',
	    	    	)
	    		)
	    	),
	    	array(
	    		'title'	=> 'Text Styles',
	    		'items'	=> array(
	    	    	array(
	    	    		'title' => 'Uppercase Text',
	    	    		'selector' => '*',
	    	    		'classes' => 'type--uppercase',
	    	    	),
	    	    	array(
	    	    		'title' => 'Faded Text',
	    	    		'selector' => '*',
	    	    		'classes' => 'type--fade',
	    	    	),
	    	    	array(
	    	    		'title' => 'Lead Paragraph',
	    	    		'selector' => 'p',
	    	    		'classes' => 'lead',
	    	    	),
	    	    	array(
	    	    		'title' => 'Fine Print',
	    	    		'selector' => '*',
	    	    		'classes' => 'type--fine-print',
	    	    	),
	    	    	array(
	    	    		'title' => 'Left Pull Quote',
	    	    		'selector' => 'p',
	    	    		'classes' => 'pull-quote left',
	    	    	),
	    	    	array(
	    	    		'title' => 'Right Pull Quote',
	    	    		'selector' => 'p',
	    	    		'classes' => 'pull-quote right',
	    	    	),
	    	    	array(
	    	    		'title' => 'Highlight',
	    	    		'selector' => '*',
	    	    		'inline' => 'mark',
	    	    		'classes' => 'highlight',
	    	    	),
	    		)
	    	),
	    	array(
	    		'title'	=> 'Colors',
	    		'items'	=> array(
	    			array(
	    				'title' => 'Primary',
	    				'selector' => '*',
	    				'classes' => 'color--primary',
	    			),
	    			array(
	    				'title' => 'Primary 1',
	    				'selector' => '*',
	    				'classes' => 'color--primary-1',
	    			),
	    			array(
	    				'title' => 'Primary 2',
	    				'selector' => '*',
	    				'classes' => 'color--primary-2',
	    			),
	    			array(
	    				'title' => 'Dark',
	    				'selector' => '*',
	    				'classes' => 'color--dark',
	    			),
	    			array(
	    				'title' => 'Secondary',
	    				'selector' => '*',
	    				'classes' => 'color--secondary',
	    			),
	    		)
	    	)
	    );
	    $settings['style_formats'] = json_encode( $style_formats );
	    return $settings;
	}
	add_filter( 'tiny_mce_before_init', 'tommusrhodus_mce_before_init' );
}

/**
 * Grab and list all categories for standard posts.
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists( 'tommusrhodus_get_post_categories' ) )) {
	function tommusrhodus_get_post_categories() {
	    $categories = get_categories();
	    $cat_array = [];
	    foreach ($categories as $category) :
	        $cat_array[$category->slug] = $category->name;
	    endforeach;
	    return $cat_array;
	}
}

if(!( function_exists( 'tommusrhodus_exclude_category' ) )) {
	function tommusrhodus_exclude_category( $query ) {
		$layout 						= get_theme_mod( 'blog_layout', '1' );
		$featured_post_category		    = get_category_by_slug( get_theme_mod( 'post_archive_featured_posts_category' ) );
		if( isset( $featured_post_category->term_id ) ) {
			$featured_post_category         = '-'. $featured_post_category->term_id;
			if ( $query->is_home() && $query->is_main_query() ) {
				if ( '2' == $layout || '3' == $layout  ) {
					$query->set( 'cat', $featured_post_category );
				}			
			}
		}
	}
	add_action( 'pre_get_posts', 'tommusrhodus_exclude_category' );
}

/**
 * tommusrhodus_pagination()
 * 
 * Simple numbered pagination system, creates a custom, functional pagination.
 * 
 * @param $pages -- Number of pages to give to the function
 * @param $range -- Range of pages to show at this time
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_pagination' ) )){
	function tommusrhodus_pagination( $pages = '', $range = 2 ){
		$showitems = ($range * 2)+1;
		
		global $paged;

		if(empty($paged)) $paged = 1;
		
		if($pages == ''){
			
			global $wp_query;
			$pages = $wp_query->max_num_pages;
				if(!$pages) {
					$pages = 1;
				}
		}
		
		$output = '';
			
		if(1 != $pages){
	  		$output .= "<div class=\"row justify-content-center mt-4 pagination-wrapper\"><div class=\"col-md-auto\"><nav><ul class=\"pagination\">";

	  		if( $paged > 1 ){
	  			$output .= "<li class=\"page-item\"><a href='". get_pagenum_link(1) ."' class='page-link rounded'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" class=\"icon icon-xs bg-primary\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">
						<path d=\"M12 5L5 12L12 19M19 12H6H19Z\" stroke=\"#2C3038\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
					</svg></a></li>";
	  		}		
			
			for ($i=1; $i <= $pages; $i++){
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					$output .= ($paged == $i)? "<li class='page-item active'><a href='#' class='page-link'>".$i."</a></li> ":"<li class='page-item'><a href='".get_pagenum_link($i)."' class='page-link'>".$i."</a></li> ";
				}
			}				

			if( $paged < $pages ){
				$output .= "<li class=\"page-item\"><a href='".get_pagenum_link($pages)."' class='page-link rounded'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" class=\"icon icon-xs bg-primary\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">
						<path d=\"M12 5L19 12L12 19M5 12H18H5Z\" stroke=\"#2C3038\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"></path>
					</svg></a></li>";
			}

			$output .= "</ul></nav></div></div>";	
		}
		
		return $output;
	}
}

if(!( function_exists('ebor_get_social_icons') )){
	function ebor_get_social_icons(){
		return array('socicon-behance','socicon-dribbble','socicon-facebook','socicon-flickr','socicon-github','socicon-grooveshark','socicon-houzz','socicon-icloud','socicon-instagram','socicon-lastfm','socicon-linkedin','socicon-medium','socicon-picasa','socicon-pinterest','socicon-rdio','socicon-skype','socicon-soundcloud','socicon-spotify','socicon-stumbleupon','socicon-tripadvisor','socicon-tumblr','socicon-twitter','socicon-vimeo','socicon-vine','socicon-vk','socicon-vk-alternitive','socicon-windows-store','socicon-xing','socicon-yelp','socicon-youko','socicon-youtube','socicon-google-plus');
	}
}

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
if(!( function_exists('tommusrhodus_get_post_layout') )){
	function tommusrhodus_get_post_layout($post_id = false){
			
		if( false == $post_id ){
			
			global $post;
			
			if( isset($post->ID) ){
				$post_id = $post->ID;
			}
			
		}
		
		$post_layout = get_post_meta($post_id, '_tommusrhodus_post_layout_override', 1);
		if( '' == $post_layout || false == $post_layout || 'none' == $post_layout ){
			$post_layout = get_theme_mod( 'post_single_layout', '2' );
		}
		
		return $post_layout;	
	}
}

/**
 * tommusrhodus_custom_comment()
 * 
 * The custom comment markup for the theme, includes additional functionality.
 * 
 * @param $comment -- the current comment object (Array)
 * @param $args -- the arguments of the current object (Array)
 * @param $depth -- the current depth of the comment (Integer)
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_custom_comment' ) )){
	function tommusrhodus_custom_comment( $comment, $args, $depth ) { 
		$GLOBALS['comment'] = $comment; 
	?>
		
		<li id="comment-<?php comment_ID() ?>" <?php comment_class( 'comment' ); ?>>
			
			<div class="d-flex justify-content-between align-items-center mb-3">
				<div class="d-flex align-items-center text-small">
					
					<?php 
						if( 'comment' == get_comment_type() ){
							echo get_avatar( $comment->comment_author_email, 52, false, false, array( 'class' => 'avatar avatar-xs' ) );
						}
					?>
					
					<div class="h6 mb-0 ml-2"><?php echo get_comment_author(); ?></div>
					
				</div>

				<div class="text-muted"><?php echo get_comment_date(); ?></div>
			</div>
			
			<div class="my-2 comment-content"><?php echo wpautop( get_comment_text() ); ?></div>
			
			<div class="text-small">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		<!-- /.media - closing li tag omitted on purpose -->

	<?php }
}

if(!( function_exists( 'tommusrhodus_hex2rgb' ) )){
	function tommusrhodus_hex2rgb($hex) {
	
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   
	   return implode(",", $rgb); // returns the rgb values separated by commas
	   
	}
}

if(!( function_exists( 'tommusrhodus_get_excerpt' ) )){
	function tommusrhodus_get_excerpt( $word_count_limit ) {

	    global $post;
	    
	    $excerpt = ( has_excerpt() ) ? get_the_excerpt() : get_the_content();
	    $content = wp_strip_all_tags( strip_shortcodes( $excerpt ), true );
	    
	    return wp_trim_words( $content, $word_count_limit );

	}
}