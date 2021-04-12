<?php 

function tommusrhodus_framework_register_elementor_locations( $elementor_theme_manager ) {

	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );

}
add_action( 'elementor/theme/register_locations', 'tommusrhodus_framework_register_elementor_locations', 10, 1 );

function tommusrhodus_framework_add_elementor_widget_categories( $elements_manager ) {
	
	$elements_manager->add_category(
		'uptime-elements',
		array(
			'title' => 'Uptime Elements'
		)
	);

}
add_action( 'elementor/elements/categories_registered', 'tommusrhodus_framework_add_elementor_widget_categories', 10, 1 );

/**
 * Add option for parallax settings to Sections
 */
add_action('elementor/element/section/section_typo/after_section_end', function( $section, $args ) {
	$section->start_controls_section(
		'section_custom_class',
		[
			'label' => __( 'Parallax', 'tr-framework' ),
			'tab' => \Elementor\Controls_Manager::TAB_STYLE,
		]
	);

	$section->add_control(
		'enable_parallax',
		[
			'label'        => __( 'Add Parallax Effect?', 'tr-framework' ),
			'type'         => Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default' => 'no',
		]
	);

	$section->end_controls_section();
}, 10, 2 );

/**
 * Render if parallax is enabled
 */
add_action( 'elementor/frontend/section/before_render', function( $element ) {
	// Make sure we are in a section element
	if( 'section' !== $element->get_name() ) {
		return;
	}

	$settings = $element->get_settings();

	if( 'yes' == $settings['enable_parallax'] && !empty( $settings['background_video_link'] ) ) {

		$element->add_render_attribute( '_wrapper', 'data-jarallax-video', $settings['background_video_link'] );
		$element->add_render_attribute( '_wrapper', 'data-speed', "0.5");
		$element->add_render_attribute( '_wrapper', 'class', 'jarallax' );	

	} elseif( 'yes' == $settings['enable_parallax'] ) {

		$element->add_render_attribute( '_wrapper', 'data-jarallax' );
		$element->add_render_attribute( '_wrapper', 'data-speed', "0.5");
		$element->add_render_attribute( '_wrapper', 'class', 'jarallax' );	

	}
	
});

add_filter('elementor/shapes/additional_shapes', function( $additional_shapes ){

	$additional_shapes['ramp'] = [
		'title' => _x('Ramp', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-1.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-1.svg',
	];
	
	$additional_shapes['ramp-flipped'] = [
		'title' => _x('Ramp Flipped', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-1-flipped.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-1-flipped.svg',
	];

	$additional_shapes['half-pipe'] = [
		'title' => _x('Half Pipe', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-2.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-2.svg',
	];

	$additional_shapes['curve-2'] = [
		'title' => _x('Curve 2', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-3.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-3.svg',
	];

	$additional_shapes['curve-2-flipped'] = [
		'title' => _x('Curve 2 Flipped', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-3-flipped.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-3-flipped.svg',
	];

	$additional_shapes['slope'] = [
		'title' => _x('Slope', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-4.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-4.svg',
	];

	$additional_shapes['fan'] = [
		'title' => _x('Fan', 'Shapes', 'elementor'),
		'has_negative' => true,
		'url' => get_template_directory_uri() . '/style/img/dividers/divider-5.svg',
		'path' => get_template_directory() . '/style/img/dividers/divider-5.svg',
	];

	return $additional_shapes;

});

/**
 * Icon Shortcode
 */
if(!( function_exists('tommusrhodus_typed_text_shortcode') )) {
	function tommusrhodus_typed_text_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'prefix'       => '',
	        	'heading_size' => 'h3',
	        	'text_colour'  => 'text-regular',
	        	'text'         => '',
	        	'underline'    => 'yes',
	        	'class'        => ''
	    	), 
    	$atts );
    	
    	// Actual uptime shortcode
    	$item_text = $values['text'];
    	$output = '';
    	
		if( 'yes' == $values['underline'] ) {

			$output .= "
				<span>". $values['prefix'] ."</span>
        		<span data-typed-text data-loop='true' data-type-speed='45' data-strings='[". $item_text ."]'></span>
        	";

		} else {
			
			$output .= "
				<span class='". $values['heading_size'] ."'>". $values['prefix'] ."</span>
	            <div class='highlight'>
	              	<span class='". $values['heading_size'] ."' data-typed-text data-loop='true' data-type-speed='45' data-strings='[". $item_text ."]'></span>
	            </div>
			";

		}
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_typed_text', 'tommusrhodus_typed_text_shortcode' );
}

/**
 * Login Shortcode
 */
if(!( function_exists('tommusrhodus_login_shortcode') )) {
	function tommusrhodus_login_shortcode( $atts ) {
		$find = array(
			'button button-primary'
		);
		
		$replace = array(
			'btn-block btn btn-primary'
		);
		
		return str_replace($find, $replace, wp_login_form( array( 'echo' => false ) ));
	}
	add_shortcode( 'uptime_login', 'tommusrhodus_login_shortcode' );
}

/**
 * Stars Shortcode
 */
if(!( function_exists('tommusrhodus_stars_shortcode') )) {
	function tommusrhodus_stars_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'number_of_stars' => '5',
	    	), 
    	$atts );

	    $output = '<div class="d-flex mr-2">';

	    	if ( strpos( $values['number_of_stars'], "." ) ) {
		      	$whole_number = floor( $values['number_of_stars'] );
		      	$html = tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' );
		      	$output .= str_repeat( $html, $whole_number );
		      	$output .= tommusrhodus_svg_icons_pluck( 'Half Star', $class = 'icon bg-warning' );
		    } else {
		        $whole_number = $values['number_of_stars'];
		      	$html = tommusrhodus_svg_icons_pluck( 'Star', $class = 'icon bg-warning' );
		      	$output .= str_repeat( $html, $whole_number );
		    }

	    $output .= '</div>';
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_stars', 'tommusrhodus_stars_shortcode' );
}

/**
 * Video Lightbox Shortcode
 */
if(!( function_exists('tommusrhodus_video_lightbox_button_shortcode') )) {
	function tommusrhodus_video_lightbox_button_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'media_url' 	=> '',
	        	'button_style'	=> 'icon',
	        	'button_label'	=> 'Watch the video'
	    	), 
    	$atts );

    	if( 'button' == $values['button_style'] ) {

			$output = '
				<a data-fancybox href="'. esc_url( $values['media_url'] ) .'" class="d-flex align-items-center">
					<span class="btn btn-primary btn-round btn-sm">
						'. tommusrhodus_svg_icons_pluck( 'Play', 'icon' ) .'
					</span>
					<span class="text-small ml-2">'. $values['button_label'] .'</span>
				</a>';

    	} else {

			$output = '
				<a data-fancybox href="'. esc_url( $values['media_url'] ) .'" class="btn btn-xlg btn-primary btn-round mx-auto mb-4 aos-init aos-animate" data-aos="fade-up">
		    		'. tommusrhodus_svg_icons_pluck( 'Play', 'icon' ) .'
		    	</a>';

    	}

	    
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_video_lightbox_button', 'tommusrhodus_video_lightbox_button_shortcode' );
}

/**
 * Icon Button Shortcode
 */
if(!( function_exists('tommusrhodus_icon_button_shortcode') )) {
	function tommusrhodus_icon_button_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'url' 		=> '#',
	        	'target' 	=> '_self',
	        	'label' 	=> 'App Store',
	        	'icon_name' => 'Apple icon',
	        	'button_style' => '',
	    	), 
    	$atts );

    	if( 'large' == $values['button_style'] ) {

 			$output = '<a href="'. esc_url( $values['url'] ) .'" class="btn btn-lg btn-primary mx-sm-2 mb-3 mb-sm-0"">
            	'. tommusrhodus_svg_icons_pluck( $values['icon_name'], 'icon' ) .'
            	<span>'. $values['label'] .'</span>
          	</a>';

    	} else {

 			$output = '<a href="'. esc_url( $values['url'] ) .'" class="btn btn-primary">
            	'. tommusrhodus_svg_icons_pluck( $values['icon_name'], 'icon' ) .'
            	<span>'. $values['label'] .'</span>
          	</a>';

    	}	   
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_icon_button', 'tommusrhodus_icon_button_shortcode' );
}

/**
 * Icon Shortcode
 */
if(!( function_exists('tommusrhodus_icon_shortcode') )) {
	function tommusrhodus_icon_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'icon_name' => 'Apple icon',
	        	'class' => '',
	    	), 
    	$atts );

	    $output = tommusrhodus_svg_icons_pluck( $values['icon_name'], 'icon ' . $values['class'] );
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_icon', 'tommusrhodus_icon_shortcode' );
}

/**
 * Icon Shortcode
 */
if(!( function_exists('tommusrhodus_counter_shortcode') )) {
	function tommusrhodus_counter_shortcode( $atts ) {

	    $values = shortcode_atts( 
	    	array(
	        	'start' => '1000',
	        	'end' => '5000',
	        	'counter_suffix' => '+',
	    	), 
    	$atts );

	    $output = '<h4 class="display-1 d-block mb-1" data-countup data-start="'. $values['start'] .'" data-end="'. $values['end'] .'" data-duration="3" data-grouping="true" data-suffix="'. $values['counter_suffix'] .'"></h4>';
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_counter', 'tommusrhodus_counter_shortcode' );
}

/**
 * Icon Shortcode
 */
if(!( function_exists('tommusrhodus_all_icons_shortcode') )) {
	function tommusrhodus_all_icons_shortcode( $atts ) {

		$all_icons = tommusrhodus_get_svg_icons();

		$output = '<div class="row justify-content-center text-center">';

			foreach ( array_slice( $all_icons, 1 ) as $key => $value) {
				$output .= '<div class="col-lg-2 col-md-2 mb-4">'. tommusrhodus_svg_icons_pluck( $key, 'icon icon-lg bg-primary clearfix' ). '<span class="small clearfix">'. $key .'</span></div>';
			}	

		$output .= '</div>';    
	     
	    return $output;
	 
	}
	add_shortcode( 'uptime_all_icons', 'tommusrhodus_all_icons_shortcode' );
}

/* SOCIAL SHARING */
if(!( function_exists('tommusrhodus_social_sharing') )) {
	function tommusrhodus_social_sharing() {

		$share_text = get_theme_mod( 'share_text', 'Share this:' );

		$output = '
			<div class="d-flex align-items-center">
				<span class="text-small mr-1">'. esc_html__( $share_text ) .'</span>
				<div class="d-flex mx-2">';

				if( 'yes' == get_theme_mod( 'show_twitter_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="twitter">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      		<title>Twitter icon</title>
                     		 <path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"></path>
                		</svg>
                	</a>
                    '; 
				}

				if( 'yes' == get_theme_mod( 'show_facebook_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="facebook">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>Facebook icon</title>
				     		 <path d="M22.676 0H1.324C.593 0 0 .593 0 1.324v21.352C0 23.408.593 24 1.324 24h11.494v-9.294H9.689v-3.621h3.129V8.41c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63H16.56V24h6.115c.733 0 1.325-.592 1.325-1.324V1.324C24 .593 23.408 0 22.676 0"></path>
						</svg>
					</a>
				    '; 
				}

				if( 'yes' == get_theme_mod( 'show_linkedin_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="linkedin">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>LinkedIn icon</title>
				     		 <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
						</svg>
					</a>
				    '; 
				}

				if( 'yes' == get_theme_mod( 'show_pinterest_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="pinterest">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>Pinterest icon</title>
                     	 <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"></path>
						</svg>
					</a>
				    '; 
				}

		$output .= '
				</div>
			</div>';    
	     
	    return $output;
	 
	}	
}
