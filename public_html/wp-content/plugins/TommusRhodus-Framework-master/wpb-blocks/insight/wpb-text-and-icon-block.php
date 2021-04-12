<?php 

/**
 * tommusrhodus_text_icon_shortcode()
 * 
 * @documentation https://codex.wordpress.org/Function_Reference/add_shortcode/
 * @documentation https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_text_icon_shortcode' ) )){
	function tommusrhodus_text_icon_shortcode( $atts, $content = null ) {
	
		extract( 
			shortcode_atts( 
				array(
					'icon'             => 'insight-insight-1',
					'layout'           => 'card-left',
					'custom_css_class' => '',
					'link' 			   => '',
					'image' 		   => ''
				), $atts 
			) 
		);

		$link_output = '#';

		if( function_exists( 'vc_build_link' ) ){
			$built_link  = vc_build_link( $link );
			$link_output = $built_link['url'];
		}
		
		$output = false;
		
		if( 'card-left' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="card flex-fill hover-effect '. $custom_css_class .' '. $layout .'">
						<div>
							<div class="card-body d-sm-flex py-4">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
								}						
								$output .= '
								<div>'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="card '. $custom_css_class .' '. $layout .'">
						<div class="card-body d-sm-flex py-4">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
							}						
							$output .= '
							<div>'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';

			}
		
		} if( 'card-left-no-bg' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="d-flex mb-3 '. $custom_css_class .' '. $layout .'">
						<div>
							<div class="d-sm-flex">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
								}						
								$output .= '
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="d-flex mb-3 '. $custom_css_class .' '. $layout .'">
						<div class="d-sm-flex">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
							}						
							$output .= '
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';

			}
		
		} elseif( 'icon-left' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="flex-fill hover-effect '. $custom_css_class .' '. $layout .'">
						<div>
							<div class="d-flex">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
								}						
								$output .= '
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="'. $custom_css_class .' '. $layout .'">
						<div class="d-flex">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i>';
							}						
							$output .= '
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';

			}
		
		} elseif( 'card-left-small-padding' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="card card-body shadow flex-row align-items-center '. $custom_css_class .' '. $layout .'">
						<div class="icon-rounded bg-success mr-3 flex-shrink-0">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
							} else {
								$output .= '<i class="'. $icon .' text-white"></i>';
							}						
							$output .= '
						</div>
						<span class="h2 mb-0 text-dark">'. do_shortcode( $content ) .'</span>
					</a>
				';

			} else {

				$output = '
					<div class="card card-body shadow flex-row align-items-center '. $custom_css_class .' '. $layout .'">
						<div class="icon-rounded bg-success mr-3 flex-shrink-0">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon' ) );
							} else {
								$output .= '<i class="'. $icon .' text-white"></i>';
							}						
							$output .= '
						</div>
						<span class="h2 mb-0 text-dark">'. do_shortcode( $content ) .'</span>
					</div>
				';

			}
		
		} elseif( 'card-top' == $layout ){

			if( $link ) {
				$output = '
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' card flex-fill hover-effect '. $layout .'">
						<div>
							<div class="card-body py-4">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
								}						
								$output .= '						
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';
			} else {

				$output = '
					<div class="'. $custom_css_class .' card '. $layout .'">
						<div class="card-body py-4">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
							}						
							$output .= '						
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';

			}
			
		} elseif( 'card-top-centered' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' card flex-fill hover-effect '. $layout .'">
						<div class="text-center">
							<div class="card-body py-4">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
								}						
								$output .= '						
								<div>'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="'. $custom_css_class .' card text-center '. $layout .'">
						<div class="card-body py-4">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
							}						
							$output .= '						
							<div>'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';
			}
	
			
		} elseif( 'top-centered' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' flex-fill hover-effect '. $layout .'">
						<div class="text-center">
							<div class="card-body py-4">';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
								}						
								$output .= '						
								<div class="text-dark">'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="'. $custom_css_class .' text-center '. $layout .'">
						<div class="card-body py-4">';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
							}						
							$output .= '						
							<div class="text-dark">'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';
			}
	
			
		} elseif( 'top-centered-no-padding' == $layout ){

			if( $link ) {

				$output = '
					<a href="'. esc_url( $link_output ) .'" class="'. $custom_css_class .' flex-fill hover-effect '. $layout .'">
						<div class="text-center">
							<div>';
								if( $image ) {
									$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
								} else {
									$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
								}						
								$output .= '						
								<div>'. do_shortcode( $content ) .'</div>
							</div>
						</div>
					</a>
				';

			} else {

				$output = '
					<div class="'. $custom_css_class .' text-center '. $layout .'">
						<div>';
							if( $image ) {
								$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large flex-shrink-0 text-primary mb-2' ) );
							} else {
								$output .= '<i class="'. $icon .' insight-large flex-shrink-0 text-primary mb-2"></i>';
							}						
							$output .= '						
							<div>'. do_shortcode( $content ) .'</div>
						</div>
					</div>
				';
			}
	
			
		} elseif( 'inline' == $layout ){

			if( $link ) {
				$output = '<a href="'. esc_url( $link_output ) .'">';
			}
			
			$output = '
				<ul class="list-unstyled '. $layout .'">
					<li class="d-flex align-items-center my-2">';
						if( $image ) {
							$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-medium mr-3 text-primary' ) ) .'<div class="text-dark">'. do_shortcode( $content ) .'</div>';
						} else {
							$output .= '<i class="'. $icon .' insight-medium mr-3 text-primary"></i><div class="text-dark">'. do_shortcode( $content ) .'</div>';
						}						
						$output .= '
					</li>
				</ul>
			';

			if( $link ) {
				$output .= '</a>';
			}
		
		} elseif( 'inline-large-icon' == $layout ){

			if( $link ) {
				$output = '<a href="'. esc_url( $link_output ) .'">';
			}
			
			$output = '
				<ul class="list-unstyled '. $layout .'">
					<li class="d-flex align-items-center my-2">';
						if( $image ) {
							$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'insight-large mr-sm-3 flex-shrink-0 text-primary' ) ) .'<div class="text-dark">'. do_shortcode( $content ) .'</div>';
						} else {
							$output .= '<i class="'. $icon .' insight-large mr-sm-3 flex-shrink-0 text-primary"></i><div class="text-dark">'. do_shortcode( $content ) .'</div>';
						}						
						$output .= '
					</li>
				</ul>
			';

			if( $link ) {
				$output .= '</a>';
			}
		
		} elseif( 'card-left-arrow-right' == $layout ){

			$output = '
				<a href="'. esc_url( $link_output ) .'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center flex-fill '. $custom_css_class .' '. $layout .'">
					<div class="d-flex align-items-center">';
						if( $image ) {
							$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'd-block mr-3 icon' ) );
						} else {
							$output .= '<i class="'. $icon .' d-block mr-3 icon"></i>';
						}
						$output .= '
						<span class="mb-0 h6 mb-0">'. do_shortcode( $content ) .'</span>
					</div>
					<i class="material-icons d-block">keyboard_arrow_right</i>
				</a>
			';
		
		} elseif( 'card-large-icon' == $layout ){

			$output = '
				<a href="'. esc_url( $link_output ) .'" class="card text-center flex-fill hover-effect large-icon-card '. $custom_css_class .' '. $layout .'">
				  <div class="card-body d-flex flex-column align-items-center justify-content-center py-2">';
						if( $image ) {
							$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon-lg' ) );
						} else {
							$output .= '<i class="'. $icon .' icon mt-2 mb-3 icon-lg"></i>';
						}
						$output .= '
						<span class="h6 mb-2">'. do_shortcode( $content ) .'</span>
				  </div>
				</a>
			';
		
		} elseif( 'card-large-icon-dark' == $layout ){

			$output = '
				<a href="'. esc_url( $link_output ) .'" class="card text-center flex-fill bg-dark hover-effect large-icon-card '. $custom_css_class .' '. $layout .'">
				  <div class="card-body d-flex flex-column align-items-center justify-content-center py-2">';
						if( $image ) {
							$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'icon mt-2 mb-3 icon-lg text-white' ) );
						} else {
							$output .= '<i class="'. $icon .' icon mt-2 mb-3 icon-lg text-white"></i>';
						}
						$output .= '
						<span class="h6 mb-2 text-white">'. do_shortcode( $content ) .'</span>
				  </div>
				</a>
			';
		
		}
		
		return $output;
		
	}
	add_shortcode( 'tommusrhodus_text_icon', 'tommusrhodus_text_icon_shortcode' );
}

/**
 * tommusrhodus_text_icon_shortcode_vc()
 * 
 * @documentation https://kb.wpbakery.com/docs/inner-api/vc_map/
 * @theme Insight
 * @since 1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_text_icon_shortcode_vc' ) )){

	function tommusrhodus_text_icon_shortcode_vc() {

		$icons = array('Install TommusRhodus Framework' => 'Install TommusRhodus Framework');
		
		if( function_exists('tommusrhodus_get_icons') ){
			$icons = tommusrhodus_get_icons();	
		}
		
		vc_map( 
			array(
				"icon"     				  => 'insight-vc-block',
			    'name'                    => __( 'Text + Icon' , 'tommusrhodus' ),
			    'base'                    => 'tommusrhodus_text_icon',
			    'description'             => __( 'Create fancy images & text', 'tommusrhodus' ),
			    "category"                => __( 'Insight WP Theme', 'tommusrhodus' ),
			    'params'                  => array(			    	
			    	array(
			    		"type"       => "dropdown",
			    		"heading"    => __( "Image & Text Display Type", 'tommusrhodus' ),
			    		"param_name" => "layout",
			    		"value"      => array(
			    			'Card with Icon Left'      				=> 'card-left',
			    			'Card with Icon Left, Small Padding'	=> 'card-left-small-padding',
			    			'Card with Icon Left, No Background'	=> 'card-left-no-bg',
			    			'Card with Icon Top'       				=> 'card-top',
			    			'Card with Icon Top, Centered'			=> 'card-top-centered',
			    			'Card with Icon Left + Arrow Right'		=> 'card-left-arrow-right',			    			
			    			'Icon Left, No Background'      		=> 'icon-left',
			    			'Icon Top Centered'						=> 'top-centered',
			    			'Icon Top Centered, No Padding'			=> 'top-centered-no-padding',
			    			'Inline Icon with Heading' 				=> 'inline',
			    			'Inline Large Icon with Heading'		=> 'inline-large-icon',
			    			'Card with Large Icon'					=> 'card-large-icon',
			    			'Card with Large Icon, Dark Background'	=> 'card-large-icon-dark',
			    		)
			    	),
			    	array(
			    		"type"        => "textarea_html",
			    		"heading"     => __( "Block Content", 'tommusrhodus' ),
			    		"param_name"  => "content",
			    		'holder'      => 'div'
			    	),
			    	array(
			    		"type"       => "attach_image",
			    		"heading"    => __( "Icon Image", 'tommusrhodus' ),
			    		"param_name" => "image",
			    		"description" => __( 'Will override icon font if an image is set', 'tommusrhodus' ),
			    	),
			    	array(
						"type" => "tommusrhodus_icons",
						"heading" => esc_html__( "Click an Icon to choose", 'tommusrhodus' ),
						"param_name" => "icon",
						"value" => $icons,
						'description' => 'Type "none" or leave blank to hide icons.'
					),
			    	array(
						"type"        => "vc_link",
						"heading"     => __( "Link", 'tommusrhodus' ),
						"param_name"  => "link"
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
	add_action( 'vc_before_init', 'tommusrhodus_text_icon_shortcode_vc' );

}