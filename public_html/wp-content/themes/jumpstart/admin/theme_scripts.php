<?php 

if(!( function_exists( 'tommusrhodus_wp_login_styles' ) )){
	function tommusrhodus_wp_login_styles() {
		
		// Get theme data for cache busting
		$theme_data    = wp_get_theme();
		$version       = $theme_data->get( 'Version' );
		$custom_css    = '';
		
		wp_enqueue_style( 
			'tommusrhodus-theme', 
			get_theme_file_uri( 'style/css/theme.css' ), 
			array(), 
			$version 
		);
		
	    wp_enqueue_style( 
	    	'tommusrhodus-wp-login', 
	    	get_theme_file_uri( 'style/css/login.css' ), 
	    	array(), 
	    	$version
	    );
	    
	    if( $logo_image_url = get_theme_mod( 'login_logo' ) ){
	    	$custom_css .= '
	    		.login h1 a {
	    			background-image: url('. esc_url( $logo_image_url ) .');
	    		}
	    	';
	    }
	    
	    wp_add_inline_style( 'tommusrhodus-wp-login', $custom_css );
	
	}
	add_action( 'login_enqueue_scripts', 'tommusrhodus_wp_login_styles', 80 );
}

if(!( function_exists( 'tommusrhodus_generate_skin' ) )){
	function tommusrhodus_generate_skin() {

		global $post;

		$colours = array(
			'primary'          => '#2568EF',
			'primary_hover'    => '#1054dd',
			'secondary'        => '#EAEDF2',
			'light'            => '#F7F9FC',
			'dark'             => '#2C3038',
			'primary_2'        => '#FF564F',
			'primary_2_hover'  => '#ff3129',
			'primary_3'        => '#051b35',
			'bg_primary'       => '#2568EF',
			'bg_primary_alt'   => '#0f50d2',
			'bg_secondary'     => '#EAEDF2',
			'bg_light'         => '#F7F9FC',
			'bg_dark'          => '#212529',
			'bg_primary_2'     => '#2C3038',
			'bg_primary_2_alt' => '#ff251c',
			'bg_primary_3'     => '#051b35',
			'bg_success'       => '#28a745',
			'bg_success_hover' => '#1e7e34'
		);
		
		foreach( $colours as $colour => $default ){
			${$colour} = get_theme_mod( $colour, $default );
		}
		
		if( isset( $post->ID ) ){
			
			$custom_colours = get_post_meta( $post->ID, '_tommusrhodus_custom_colours', 1 );
			
			if( !( 'no' == $custom_colours )){
				foreach( $colours as $colour => $default ){
					
					// Grab the post meta for this colour
					$check = get_post_meta( $post->ID, '_tommusrhodus_'. $colour .'_override', 1 );
					
					if( $check != '' ){
					
						// Compare the post meta
						if( strtolower( $check ) != strtolower( $default ) ){
							${$colour} = $check;
						}
					
					}
					
				}
			}
		
		}	

		$logo_height			= str_replace( 'px', '', get_theme_mod( 'logo_height', '26px' ) ) . 'px';
		$body_font_name 	= get_theme_mod( 'body_font_name', 'Nunito' );
		$sticky_header_logo 	= get_theme_mod( 'sticky_header_logo' );
		$body_colour      	= get_theme_mod( 'body_colour', '#555A64' );

		$skin = '
			.plyr--video .plyr__control:hover {
				background: '. $primary .';
			}
			.plyr--full-ui input[type=range] {
				color: '. $primary .';
			}
			body, .elementor-widget-text-editor {
				font-family: "'. $body_font_name .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
				color: '. $body_colour .';
			}			
			.navbar-brand img {
				max-height: '. $logo_height .';
				width: auto;
			}
			.navbar[data-sticky="top"].scrolled:not(.bg-white) .navbar-brand img,
			.navbar[data-sticky="top"].navbar-toggled-show:not(.bg-white) .navbar-brand img {
				visibility: hidden;
			}
			.navbar[data-sticky="top"].scrolled:not(.bg-white) .navbar-brand,
			.navbar[data-sticky="top"].navbar-toggled-show:not(.bg-white) .navbar-brand {
				background-image: url('. $sticky_header_logo .');
				background-size: contain;
				background-position: 50% 50%;
				background-repeat: no-repeat;
			}
			a {
			  	color: '. $primary .';
			}
			a:hover, .card > [data-toggle="collapse"][aria-expanded="false"]:hover h6 {
			    color: '. $primary_hover .';
			}
			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
		  		color: #2C3038; 
		  	}
		  	.btn {
				color: #555A64;
				background-color: transparent;
				border: 2px solid transparent;
			}
			.btn:hover {
				color: #555A64;
				text-decoration: none;
			}
			.btn-primary {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-primary:hover {
				color: #fff;
				background-color: '. $primary_hover .';
				border-color: '. $primary_hover .';
			}
			.flickity-prev-next-button {
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.flickity-prev-next-button:hover {
				background-color: '. $primary_hover .';
				border-color: '. $primary_hover .';
			}
			.btn-primary.disabled, .btn-primary:disabled {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active,.show > .btn-primary.dropdown-toggle {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-secondary {
				color: #2C3038;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-secondary:hover {
				color: #2C3038;
				background-color: #d2d9e3;
				border-color: #cbd2df;
			}
			.btn-secondary.disabled, .btn-secondary:disabled {
				color: #2C3038;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active,.show > .btn-secondary.dropdown-toggle {
				color: #2C3038;
				background-color: #cbd2df;
				border-color: #c3cbda;
			}
			.btn-success {
				color: #fff;
				background-color: #28a745;
				border-color: #28a745;
				box-shadow: none;
			}
			.btn-success:hover {
				color: #fff;
				background-color: #218838;
				border-color: #1e7e34;
			}
			.btn-success.disabled, .btn-success:disabled {
				color: #fff;
				background-color: #28a745;
				border-color: #28a745;
			}
			.btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active,.show > .btn-success.dropdown-toggle {
				color: #fff;
				background-color: #1e7e34;
				border-color: #1c7430;
			}
			.btn-info {
				color: #fff;
				background-color: #17a2b8;
				border-color: #17a2b8;
				box-shadow: none;
			}
			.btn-info:hover {
				color: #fff;
				background-color: #138496;
				border-color: #117a8b;
			}
			.btn-info.disabled, .btn-info:disabled {
				color: #fff;
				background-color: #17a2b8;
				border-color: #17a2b8;
			}
			.btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,.show > .btn-info.dropdown-toggle {
				color: #fff;
				background-color: #117a8b;
				border-color: #10707f;
			}
			.btn-warning {
				color: #2C3038;
				background-color: #ffc107;
				border-color: #ffc107;
				box-shadow: none;
			}
			.btn-warning:hover {
				color: #2C3038;
				background-color: #e0a800;
				border-color: #d39e00;
			}
			.btn-warning.disabled, .btn-warning:disabled {
				color: #2C3038;
				background-color: #ffc107;
				border-color: #ffc107;
			}
			.btn-warning:not(:disabled):not(.disabled):active, .btn-warning:not(:disabled):not(.disabled).active,.show > .btn-warning.dropdown-toggle {
				color: #2C3038;
				background-color: #d39e00;
				border-color: #c69500;
			}
			.btn-danger {
				color: #fff;
				background-color: #dc3545;
				border-color: #dc3545;
				box-shadow: none;
			}
			.btn-danger:hover {
				color: #fff;
				background-color: #c82333;
				border-color: #bd2130;
			}
			.btn-danger.disabled, .btn-danger:disabled {
				color: #fff;
				background-color: #dc3545;
				border-color: #dc3545;
			}
			.btn-danger:not(:disabled):not(.disabled):active, .btn-danger:not(:disabled):not(.disabled).active,.show > .btn-danger.dropdown-toggle {
				color: #fff;
				background-color: #bd2130;
				border-color: #b21f2d;
			}
			.btn-light {
				color: #2C3038;
				background-color: '. $light .';
				border-color: '. $light .';
				box-shadow: none;
			}
			.btn-light:hover {
				color: #2C3038;
				background-color: #dbe4f2;
				border-color: #d2ddee;
			}
			.btn-light.disabled, .btn-light:disabled {
				color: #2C3038;
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active,.show > .btn-light.dropdown-toggle {
				color: #2C3038;
				background-color: #d2ddee;
				border-color: #c9d6eb;
			}
			.btn-dark {
				color: #fff;
				background-color: #2C3038;
				border-color: #2C3038;
				box-shadow: none;
			}
			.btn-dark:hover {
				color: #fff;
				background-color: '. $dark .';
				border-color: #16181b;
			}
			.btn-dark.disabled, .btn-dark:disabled {
				color: #fff;
				background-color: #2C3038;
				border-color: #2C3038;
			}
			.btn-dark:not(:disabled):not(.disabled):active, .btn-dark:not(:disabled):not(.disabled).active,.show > .btn-dark.dropdown-toggle {
				color: #fff;
				background-color: #16181b;
				border-color: #101114;
			}
			.btn-primary-2 {
				color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
				box-shadow: none;
			}
			.btn-primary-2:hover {
				color: #fff;
				background-color: '. $primary_2_hover .';
				border-color: '. $primary_2_hover .';
			}
			.btn-primary-2.disabled, .btn-primary-2:disabled {
				color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-primary-2:not(:disabled):not(.disabled):active, .btn-primary-2:not(:disabled):not(.disabled).active,.show > .btn-primary-2.dropdown-toggle {
				color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-primary-3 {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
				box-shadow: none;
			}
			.btn-primary-3:hover {
				color: #fff;
				background-color: '. $bg_primary_3 .';
				border-color: '. $bg_primary_3 .';
			}
			.btn-primary-3.disabled, .btn-primary-3:disabled {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-primary-3:not(:disabled):not(.disabled):active, .btn-primary-3:not(:disabled):not(.disabled).active,.show > .btn-primary-3.dropdown-toggle {
				color: #fff;
				background-color: #010306;
				border-color: black;
			}
			.btn-outline-primary {
				color: '. $primary .';
				border-color: rgba('. tommusrhodus_hex2rgb( $primary ) .', 0.2);
			}
			.btn-outline-primary:hover {
				color: '. $primary .';
				background-color: rgba('. tommusrhodus_hex2rgb( $primary ) .', 0.2);
				border-color: rgba('. tommusrhodus_hex2rgb( $primary ) .', 0);
			}
			.btn-outline-primary.disabled, .btn-outline-primary:disabled {
				color: '. $primary .';
				background-color: transparent;
			}
			.btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active,.show > .btn-outline-primary.dropdown-toggle {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-outline-secondary {
				color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-secondary:hover {
				color: #2C3038;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-secondary.disabled, .btn-outline-secondary:disabled {
				color: '. $secondary .';
				background-color: transparent;
			}
			.btn-outline-secondary:not(:disabled):not(.disabled):active, .btn-outline-secondary:not(:disabled):not(.disabled).active,.show > .btn-outline-secondary.dropdown-toggle {
				color: #2C3038;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-success {
				color: #28a745;
				border-color: #28a745;
			}
			.btn-outline-success:hover {
				color: #fff;
				background-color: #28a745;
				border-color: #28a745;
			}
			.btn-outline-success.disabled, .btn-outline-success:disabled {
				color: #28a745;
				background-color: transparent;
			}
			.btn-outline-success:not(:disabled):not(.disabled):active, .btn-outline-success:not(:disabled):not(.disabled).active,.show > .btn-outline-success.dropdown-toggle {
				color: #fff;
				background-color: #28a745;
				border-color: #28a745;
			}
			.btn-outline-info {
				color: #17a2b8;
				border-color: #17a2b8;
			}
			.btn-outline-info:hover {
				color: #fff;
				background-color: #17a2b8;
				border-color: #17a2b8;
			}
			.btn-outline-info.disabled, .btn-outline-info:disabled {
				color: #17a2b8;
				background-color: transparent;
			}
			.btn-outline-info:not(:disabled):not(.disabled):active, .btn-outline-info:not(:disabled):not(.disabled).active,.show > .btn-outline-info.dropdown-toggle {
				color: #fff;
				background-color: #17a2b8;
				border-color: #17a2b8;
			}
			.btn-outline-warning {
				color: #ffc107;
				border-color: #ffc107;
			}
			.btn-outline-warning:hover {
				color: #2C3038;
				background-color: #ffc107;
				border-color: #ffc107;
			}
			.btn-outline-warning.disabled, .btn-outline-warning:disabled {
				color: #ffc107;
				background-color: transparent;
			}
			.btn-outline-warning:not(:disabled):not(.disabled):active, .btn-outline-warning:not(:disabled):not(.disabled).active,.show > .btn-outline-warning.dropdown-toggle {
				color: #2C3038;
				background-color: #ffc107;
				border-color: #ffc107;
			}
			.btn-outline-danger {
				color: #dc3545;
				border-color: #dc3545;
			}
			.btn-outline-danger:hover {
				color: #fff;
				background-color: #dc3545;
				border-color: #dc3545;
			}
			.btn-outline-danger.disabled, .btn-outline-danger:disabled {
				color: #dc3545;
				background-color: transparent;
			}
			.btn-outline-danger:not(:disabled):not(.disabled):active, .btn-outline-danger:not(:disabled):not(.disabled).active,.show > .btn-outline-danger.dropdown-toggle {
				color: #fff;
				background-color: #dc3545;
				border-color: #dc3545;
			}
			.btn-outline-light {
				color: '. $light .';
				border-color: rgba('. tommusrhodus_hex2rgb( $light ) .', 0.25);
			}
			.btn-outline-light:hover {
				color: #2C3038;
				background-color: rgba('. tommusrhodus_hex2rgb( $light ) .', 0.25);
				border-color: rgba(0,0,0,0);
			}
			.btn-outline-light.disabled, .btn-outline-light:disabled {
				color: '. $light .';
				background-color: transparent;
			}
			.btn-outline-light:not(:disabled):not(.disabled):active, .btn-outline-light:not(:disabled):not(.disabled).active,.show > .btn-outline-light.dropdown-toggle {
				color: #2C3038;
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-outline-dark {
				color: #2C3038;
				border-color: #2C3038;
			}
			.btn-outline-dark:hover {
				color: #fff;
				background-color: #2C3038;
				border-color: #2C3038;
			}
			.btn-outline-dark.disabled, .btn-outline-dark:disabled {
				color: #2C3038;
				background-color: transparent;
			}
			.btn-outline-dark:not(:disabled):not(.disabled):active, .btn-outline-dark:not(:disabled):not(.disabled).active,.show > .btn-outline-dark.dropdown-toggle {
				color: #fff;
				background-color: #2C3038;
				border-color: #2C3038;
			}
			.btn-outline-primary-2 {
				color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-2:hover {
				color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-2.disabled, .btn-outline-primary-2:disabled {
				color: '. $primary_2 .';
				background-color: transparent;
			}
			.btn-outline-primary-2:not(:disabled):not(.disabled):active, .btn-outline-primary-2:not(:disabled):not(.disabled).active,.show > .btn-outline-primary-2.dropdown-toggle {
				color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-3 {
				color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3:hover {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3.disabled, .btn-outline-primary-3:disabled {
				color: '. $primary_3 .';
				background-color: transparent;
			}
			.btn-outline-primary-3:not(:disabled):not(.disabled):active, .btn-outline-primary-3:not(:disabled):not(.disabled).active,.show > .btn-outline-primary-3.dropdown-toggle {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3:not(:disabled):not(.disabled):active:focus, .btn-outline-primary-3:not(:disabled):not(.disabled).active:focus,.show > .btn-outline-primary-3.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(5, 27, 53, 0.5);
			}
			.btn-link {
				font-weight: 600;
				color: '. $primary .';
			}
			.btn-link:hover {
				color: #0e47ba;
			}
			.btn-link:disabled, .btn-link.disabled {
				color: #6c757d;
			}
			.page-link {
			    color: '. $primary .';
			}
			.page-link:hover {
			    color: #0e47ba;
			}
			.page-item.active .page-link {
			    color: #fff;
			    background-color: '. $primary .';
			    border-color: '. $primary .';
			}
			.page-item.disabled .page-link {
			    color: rgba(85, 90, 100, 0.65);
			    cursor: auto;
			    background-color: #fff;
			    border-color: '. $secondary .';
			}
			.badge-primary {
				color: #fff;
				background-color: '. $bg_primary .';
			}
			a.badge-primary {
				color: '. $primary .';
				background: rgba('. tommusrhodus_hex2rgb( $bg_primary ) .', 0.1)
			}
			a.badge-primary:hover, a.badge-primary:focus {
				color: #fff;
				background-color: '. $bg_primary .';
			}
			.badge-secondary {
				color: '. $primary_2 .';
				background-color: '. $bg_primary_2 .';
			}
			a.badge-secondary:hover, a.badge-secondary:focus {
				color: #2C3038;
				background-color: #cbd2df;
			}
			a.badge-primary-2 {
				color: '. $primary_2 .';
				background: rgba('. tommusrhodus_hex2rgb( $bg_primary_2 ) .', 0.1)
			}
			a.badge-primary-3 {
				color: '. $primary_3 .';
				background: rgba('. tommusrhodus_hex2rgb( $bg_primary_3 ) .', 0.1)
			}
			.badge-success {
				color: #fff;
				background-color: #28a745;
			}
			a.badge-success:hover, a.badge-success:focus {
				color: #fff;
				background-color: #1e7e34;
			}
			.badge-info {
				color: #fff;
				background-color: #17a2b8;
			}
			a.badge-info:hover, a.badge-info:focus {
				color: #fff;
				background-color: #117a8b;
			}
			.badge-warning {
				color: #2C3038;
				background-color: #ffc107;
			}
			a.badge-warning:hover, a.badge-warning:focus {
				color: #2C3038;
				background-color: #d39e00;
			}
			.badge-danger {
				color: #fff;
				background-color: #dc3545;
			}
			a.badge-danger:hover, a.badge-danger:focus {
				color: #fff;
				background-color: #bd2130;
			}
			.badge-light {
				color: #2C3038;
				background-color: '. $light .';
			}
			a.badge-light:hover, a.badge-light:focus {
				color: #2C3038;
				background-color: #d2ddee;
			}
			.badge-dark {
				color: #fff;
				background-color: #2C3038;
			}
			a.badge-dark:hover, a.badge-dark:focus {
				color: #fff;
				background-color: #16181b;
			}
			.badge-primary-2 {
				color: #fff;
				background-color: '. $bg_primary_2 .';
			}
			a.badge-primary-2:hover, a.badge-primary-2:focus {
				color: #fff;
				background-color: '. $bg_primary_2 .';
			}
			.badge-primary-3 {
				color: #fff;
				background-color: '. $bg_primary_3 .';
			}
			a.badge-primary-3:hover, a.badge-primary-3:focus {
				color: #fff;
				background-color: '. $bg_primary_3 .';
			}
			.progress {
			    background-color: '. $light .';
			}
			.progress-bar {
			    color: #fff;
			    background-color: '. $primary .';
			}
			.bg-primary {
			    background-color: '. $bg_primary .' !important;
			}
			a.bg-primary:hover, a.bg-primary:focus,button.bg-primary:hover,button.bg-primary:focus {
			    background-color: #0f50d2 !important;
			}
			.bg-secondary {
			    background-color: '. $bg_secondary .' !important;
			}
			a.bg-secondary:hover, a.bg-secondary:focus,button.bg-secondary:hover,button.bg-secondary:focus {
			    background-color: #cbd2df !important;
			}
			.bg-success {
			    background-color: #28a745 !important;
			}
			a.bg-success:hover, a.bg-success:focus,button.bg-success:hover,button.bg-success:focus {
			    background-color: #1e7e34 !important;
			}
			.bg-info {
			    background-color: #17a2b8 !important;
			}
			a.bg-info:hover, a.bg-info:focus,button.bg-info:hover,button.bg-info:focus {
			    background-color: #117a8b !important;
			}
			.bg-warning {
			    background-color: #ffc107 !important;
			}
			a.bg-warning:hover, a.bg-warning:focus,button.bg-warning:hover,button.bg-warning:focus {
			    background-color: #d39e00 !important;
			}
			.bg-danger {
			    background-color: #dc3545 !important;
			}
			a.bg-danger:hover, a.bg-danger:focus,button.bg-danger:hover,button.bg-danger:focus {
			    background-color: #bd2130 !important;
			}
			.bg-light {
			    background-color: '. $bg_light .' !important;
			}
			a.bg-light:hover, a.bg-light:focus,button.bg-light:hover,button.bg-light:focus {
			    background-color: #d2ddee !important;
			}
			.bg-dark {
			    background-color: '. $bg_dark .' !important;
			}
			a.bg-dark:hover, a.bg-dark:focus,button.bg-dark:hover,button.bg-dark:focus {
			    background-color: #16181b !important;
			}
			.bg-primary-2 {
			    background-color: '. $bg_primary_2 .' !important;
			}
			a.bg-primary-2:hover, a.bg-primary-2:focus,button.bg-primary-2:hover,button.bg-primary-2:focus {
			    background-color: #ff251c !important;
			}
			.bg-primary-3 {
			    background-color: '. $bg_primary_3 .' !important;
			}
			a.bg-primary-3:hover, a.bg-primary-3:focus,button.bg-primary-3:hover,button.bg-primary-3:focus {
			    background-color: #010306 !important;
			}
			.border-primary {
				border-color: #2568EF !important;
			}
			.border-secondary {
				border-color: '. $bg_secondary .'!important;
			}
			.border-success {
				border-color: #28a745 !important;
			}
			.border-info {
				border-color: #17a2b8 !important;
			}
			.border-warning {
				border-color: #ffc107 !important;
			}
			.border-danger {
				border-color: #dc3545 !important;
			}
			.border-light {
				border-color: '. $bg_light .' !important;
			}
			.border-dark {
				border-color: '. $bg_dark .' !important;
			}
			.border-primary-2 {
				border-color: '. $bg_primary_2 .' !important;
			}
			.border-primary-3 {
				border-color: '. $bg_primary_3 .' !important;
			}
			svg[class*=\'bg-\'] {
				background: none !important;
			}
			svg.bg-primary [stroke]:not([stroke="none"]) {
				stroke: '. $primary .';
			}
			svg.bg-primary [fill]:not([fill="none"]) {
				fill: '. $primary .';
			}
			svg.bg-secondary [stroke]:not([stroke="none"]) {
				stroke: '. $secondary .';
			}
			svg.bg-secondary [fill]:not([fill="none"]) {
				fill: '. $secondary .';
			}
			svg.bg-success [stroke]:not([stroke="none"]) {
				stroke: #28a745;
			}
			svg.bg-success [fill]:not([fill="none"]) {
				fill: #28a745;
			}
			svg.bg-info [stroke]:not([stroke="none"]) {
				stroke: #17a2b8;
			}
			svg.bg-info [fill]:not([fill="none"]) {
				fill: #17a2b8;
			}
			svg.bg-warning [stroke]:not([stroke="none"]) {
				stroke: #ffc107;
			}
			svg.bg-warning [fill]:not([fill="none"]) {
				fill: #ffc107;
			}
			svg.bg-danger [stroke]:not([stroke="none"]) {
				stroke: #dc3545;
			}
			svg.bg-danger [fill]:not([fill="none"]) {
				fill: #dc3545;
			}
			svg.bg-light [stroke]:not([stroke="none"]) {
				stroke: '. $light .';
			}
			svg.bg-light [fill]:not([fill="none"]) {
				fill: '. $light .';
			}
			svg.bg-dark [stroke]:not([stroke="none"]) {
				stroke: #2C3038;
			}
			svg.bg-dark [fill]:not([fill="none"]) {
				fill: #2C3038;
			}
			svg.bg-primary-2 [stroke]:not([stroke="none"]) {
				stroke: '. $primary_2 .';
			}
			svg.bg-primary-2 [fill]:not([fill="none"]) {
				fill: '. $primary_2 .';
			}
			svg.bg-primary-3 [stroke]:not([stroke="none"]) {
				stroke: '. $primary_3 .';
			}
			svg.bg-primary-3 [fill]:not([fill="none"]) {
				fill: '. $primary_3 .';
			}
			svg.bg-white [stroke]:not([stroke="none"]) {
				stroke: #fff;
			}
			svg.bg-white [fill]:not([fill="none"]) {
				fill: #fff;
			}
			.text-primary-2 {
			  color: '. $primary_2 .' !important; 
			}
			a.text-primary-2:hover, a.text-primary-2:focus {
			  color: '. $primary_2_hover .' !important; 
			}
			.text-primary-3 {
			  color: '. $primary_3 .' !important; 
			}
			.text-primary {
			  color: '. $primary .' !important; 
			}
			a.text-primary:hover, a.text-primary:focus {
			  color: '. $primary_hover .' !important; 
			}
			.dropdown-item:hover, .dropdown-item:focus {
				color: '. $primary_hover .';
			}
		';
		
		if( 'yes' == get_theme_mod( 'disable_page_fade', 'no' ) ){
			$skin .= '
				body > div.loader {
					display: none !important;
				}
				.fade-page {
					opacity: 1 !important;
				}
			';
		}

		$terms = get_terms( array(
			'taxonomy'   => 'category',
			'hide_empty' => false
		) );
	
		if( is_array( $terms ) ) :

			foreach( $terms as $term ) :
			
				$colour = get_term_meta( $term->term_id, '_tommusrhodus_category_color', true );
				
				if( $colour ) {
					$skin .= '
					.'. $term->slug . '-custom-color { 
						background-color: rgba('. tommusrhodus_hex2rgb( get_term_meta( $term->term_id, '_tommusrhodus_category_color', true ) ) .', 0.2) !important;
						color: '. get_term_meta( $term->term_id, '_tommusrhodus_category_color', true ). ' !important;
					}
					.'. $term->slug . '-custom-color:hover { 
						background-color: '. get_term_meta( $term->term_id, '_tommusrhodus_category_color', true ). ' !important;
						color: #fff !important;
					}
					.'. $term->slug . '-custom-color.focus, .'. $term->slug . '-custom-color:focus {
					    box-shadow: 0 0 0 0.2rem rgba('. tommusrhodus_hex2rgb( get_term_meta( $term->term_id, '_tommusrhodus_category_color', true ) ) .',.5) !important;
					}';
				}	
							
			endforeach;		

		endif;

		return $skin;
				
	}
}

/**
 * tommusrhodus_load_scripts()
 * 
 * Properly Enqueues Scripts & Styles for the theme
 * 
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @since v1.0.0
 * @blame Tom Rhodes
 */ 
if(!( function_exists( 'tommusrhodus_load_scripts' ) )){
	function tommusrhodus_load_scripts() {
		
		// Get theme data for cache busting
		$theme_data    = wp_get_theme();
		$version       = $theme_data->get( 'Version' );

		// Enqueue Styles
		if( $font_url = tommusrhodus_google_fonts_url() ){
			wp_enqueue_style( 
				'tommusrhodus-google-font', 
				$font_url, 
				array(), 
				$version 
			);
		}
		
		wp_enqueue_style( 
			'tommusrhodus-theme', 
			get_theme_file_uri( 'style/css/theme.css' ), 
			array(), 
			$version 
		);
		
		wp_style_add_data( 'tommusrhodus-theme', 'rtl', 'replace' );
		
		if( class_exists( 'WP_Job_Manager' ) ){
			wp_enqueue_style( 
				'tommusrhodus-wp-job-manager', 
				get_theme_file_uri( 'style/css/wp-job-manager.css' ), 
				array(), 
				$version 
			);
		}
		
		wp_enqueue_style( 
			'tommusrhodus-style', 
			get_stylesheet_uri(), 
			array(), 
			$version 
		);
		
		// Enqueue Scripts
		wp_enqueue_script( 
			'popper', 
			get_theme_file_uri( 'style/js/popper.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'bootstrap', 
			get_theme_file_uri( 'style/js/bootstrap.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'aos', 
			get_theme_file_uri( 'style/js/aos.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'clipboard', 
			get_theme_file_uri( 'style/js/clipboard.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'fancybox', 
			get_theme_file_uri( 'style/js/jquery.fancybox.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'flatpickr', 
			get_theme_file_uri( 'style/js/flatpickr.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'flickity', 
			get_theme_file_uri( 'style/js/flickity.pkgd.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'ion-range-slider', 
			get_theme_file_uri( 'style/js/ion.rangeSlider.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'isotope', 
			get_theme_file_uri( 'style/js/isotope.pkgd.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax', 
			get_theme_file_uri( 'style/js/jarallax.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax-video', 
			get_theme_file_uri( 'style/js/jarallax-video.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax-element', 
			get_theme_file_uri( 'style/js/jarallax-element.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'countdown', 
			get_theme_file_uri( 'style/js/jquery.countdown.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'smartwizard', 
			get_theme_file_uri( 'style/js/jquery.smartWizard.min.js"' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'plyr', 
			get_theme_file_uri( 'style/js/plyr.polyfilled.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'prism', 
			get_theme_file_uri( 'style/js/prism.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'scroll-monitor', 
			get_theme_file_uri( 'style/js/scrollMonitor.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'smooth-scroll', 
			get_theme_file_uri( 'style/js/smooth-scroll.polyfills.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'svg-injector', 
			get_theme_file_uri( 'style/js/svg-injector.umd.production.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'twitter-fetcher', 
			get_theme_file_uri( 'style/js/twitterFetcher_min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'typed', 
			get_theme_file_uri( 'style/js/typed.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'fitvids', 
			get_theme_file_uri( 'style/js/fitvids.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		if( function_exists('tommusrhodus_social_sharing') ) {
			wp_enqueue_script( 
				'goodshare', 
				get_theme_file_uri( 'style/js/goodshare.js' ), 
				array('jquery'), 
				$version, 
				true  
			);
		}
		
		wp_enqueue_script( 
			'tommusrhodus-wp-scripts', 
			get_theme_file_uri( 'style/js/wp-scripts.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'tommusrhodus-scripts', 
			get_theme_file_uri( 'style/js/theme.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		// Enqueue Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Localise script data
		$script_array = array( 
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'tommusrhodus-voting' )
		);
		wp_localize_script( 'tommusrhodus-wp-scripts', 'jumpstart_data', $script_array );

		wp_add_inline_style( 'tommusrhodus-style', tommusrhodus_generate_skin() );
		
	}
	add_action( 'wp_enqueue_scripts', 'tommusrhodus_load_scripts', 110 );
}

/**
 * tommusrhodus_google_fonts_url()
 * 
 * Properly Enqueues Scripts & Styles for the theme
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_google_fonts_url' ) )){
	function tommusrhodus_google_fonts_url(){
	
	    $font_url = '';
	    if ( 'off' !== _x( 'on', 'Google font: on or off', 'jumpstart' ) ) {
	        $font_url = add_query_arg( 
				'family', 
				urlencode( 
					str_replace( '+', ' ', get_theme_mod( 'google_font_string', 'Nunito:400,400i,600,700&display=swap' ) ) 
				), 
				"//fonts.googleapis.com/css" 
	        );
	    }
	    
	    return $font_url;
	    
	}
}

/**
 * tommusrhodus_admin_load_scripts()
 * 
 * Properly enqueue styles and scripts to show on admin screens.
 * 
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @since v1.0.0
 * @blame Tom Rhodes
 */ 
if(!( function_exists( 'tommusrhodus_admin_load_scripts' ) )){
	function tommusrhodus_admin_load_scripts(){
	
		wp_enqueue_style( 
			'tommusrhodus-theme-admin-css', 
			get_theme_file_uri( 'admin/tommusrhodus-theme-admin.css' ) 
		);
		
		wp_enqueue_script( 
			'tommusrhodus-theme-admin-js', 
			get_theme_file_uri( 'admin/tommusrhodus-theme-admin.js' ), 
			array('jquery'), 
			false, 
			true
		);
		
	}
	add_action( 'admin_enqueue_scripts', 'tommusrhodus_admin_load_scripts', 200 );
}

// Add backend styles for Gutenberg.
add_action( 'enqueue_block_editor_assets', 'tommusrhodus_load_gutenberg_assets' );

/**
 * Load Gutenberg stylesheet.
 */
if(!( function_exists( 'tommusrhodus_load_gutenberg_assets' ) )){
	function tommusrhodus_load_gutenberg_assets(){
		
		$theme_data    = wp_get_theme();
		$version       = $theme_data->get( 'Version' );
		
		// Load the theme styles within Gutenberg.
		if( $font_url = tommusrhodus_google_fonts_url() ){
			wp_enqueue_style( 
				'tommusrhodus-google-font', 
				$font_url, 
				array(), 
				$version 
			);
		}

		wp_enqueue_style( 
			'tommusrhodus-gutenberg', 
			get_theme_file_uri( '/style/css/gutenberg-editor-style.css' ), 
			false 
		);

		if( get_theme_mod( 'body_font_name' ) ) {
			$body_font = get_theme_mod( 'body_font_name' );
		} else {
			$body_font = 'Nunito';
		}

		$body_text = get_theme_mod( 'body_text', '#555A64' );

		if( isset($post->ID) && get_post_meta( $post->ID, '_tommusrhodus_primary_override', 1 ) ) {
			$primary = get_post_meta( $post->ID, '_tommusrhodus_primary_override', 1 );
		} else {
			$primary = get_theme_mod( 'primary', '#2568EF' );
		}		

		$custom_css = '
			.editor-writing-flow, 
			.wp-block-paragraph, 
			.wp-block-table, 
			.wp-block-freeform,
			.mce-content-body:not(.wp-block-button__link) {
			    font-family: "'. $body_font .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
				    font-size: 1rem;
				    font-weight: 600;
				    line-height: 1.5;
				    color: #555A64;
			}
			.editor-post-title__block .editor-post-title__input {
				font-family: "'. $body_font .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
			}
	        .wp-block h1, 
	        .wp-block h2, 
	        .wp-block h3, 
	        .wp-block h4, 
	        .wp-block h5, 
	        .wp-block h6, 
	        .wp-block-freeform.block-library-rich-text__tinymce blockquote p,
	        .block-editor blockquote p {
	            color: '. $body_text .';
	        }
	        .wp-block p {
	            color: '. $body_text .';
	        }
	        .article a, .block-editor-rich-text__editable a, .wp-block-freeform.block-library-rich-text__tinymce a, .editor-styles-wrapper li a {
				color: '. $primary .';
				text-decoration: none;
			}
			.wp-block-button__link {				
			    color: #fff;
			}
			.wp-block .mce-content-body thead th, .wp-block-table thead th {
				background: '. $primary .' !important;
			}
        ';
        wp_add_inline_style( 'tommusrhodus-gutenberg', $custom_css );
		
	}
	add_action( 'enqueue_block_editor_assets', 'tommusrhodus_load_gutenberg_assets', 200 );
}