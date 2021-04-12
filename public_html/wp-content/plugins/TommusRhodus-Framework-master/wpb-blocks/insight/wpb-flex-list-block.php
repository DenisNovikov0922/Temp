<?php

/**
 * The Shortcode
 */
function tommusrhodus_flex_list_shortcode( $atts, $content = null ) {
	extract( 
		shortcode_atts( 
			array(
				'custom_css_class' => ''
			), $atts 
		) 
	);
	
	$output = '<div class="list-group d-flex flex-fill '. esc_attr($custom_css_class) .'">'. do_shortcode($content) .'</div>';

	return $output;
}
add_shortcode( 'insight_flex_list', 'tommusrhodus_flex_list_shortcode' );

/**
 * The Shortcode
 */
function tommusrhodus_flex_list_content_shortcode( $atts, $content = null ) {
	extract( 
		shortcode_atts( 
			array(
				'title' 			=> '',
				'layout' 			=> 'icon_title',
				'icon' 				=> '',
				'custom_css_class' 	=> '',
				'image' 		   	=> '',
				'link' 		   		=> ''
			), $atts 
		) 
	);

	$link_output = '#';

	if( function_exists( 'vc_build_link' ) ){
		$built_link  = vc_build_link( $link );
		$link_output = $built_link['url'];
	}

	$output = false;
		
	if( 'icon_title' == $layout ){
	
		$output = '
			<a href="'. esc_attr( $link_output ) .'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center flex-fill '. esc_attr($custom_css_class) .'">
			    <div class="d-flex align-items-center remove-p-margin">
			      	<i class="'. $icon .' insight-large d-block mr-3 icon"></i>
			      	<span class="mb-0 h6 mb-0">'. $title .'</span>
			    </div>
			    <i class="material-icons d-block">keyboard_arrow_right</i>
		  	</a>
		';

	} elseif( 'text_title' == $layout ){

		$output = '
			<a class="list-group-item list-group-item-action d-flex align-items-center w-100 flex-fill '. esc_attr($custom_css_class) .'" href="'. esc_attr( $link_output ) .'">
				<div class="d-flex align-items-center">
					<div class="py-2 px-3 mr-2 text-center remove-p-margin">
						'. do_shortcode($content) .'
					</div>
					<span class="mb-0 h6 text-primary">'. $title .'</span>
				</div>
			</a>
		';

	} elseif( 'title_arrow' == $layout ){

		$output = '
			<a href="'. esc_attr( $link_output ) .'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-lg-0 '. esc_attr($custom_css_class) .'">
				<div class="d-flex align-items-center">
					<span class="mb-0 text-primary py-1 font-weight-bold">'. $title .'</span>
				</div>
				<i class="material-icons d-block">keyboard_arrow_right</i>
			</a>
		';

	}  elseif( 'icon_title_arrow' == $layout ){

		$output = '
			<a href="'. esc_attr( $link_output ) .'" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
				<div class="d-flex align-items-center">';
					if( $image ) {
						$output .= wp_get_attachment_image( $image, 'full', 0, array( 'class' => 'd-block mr-3 icon icon-sm avatar' ) );
					} else {
						$output .= '<i class="'. $icon .' d-block mr-3 icon icon-sm text-primary"></i>';
					}						
					$output .= '
				  	<span class="mb-0 h6">'. $title .'</span>
				</div>
				<i class="material-icons d-block">keyboard_arrow_right</i>
			</a>
		';

	}

	return $output;
}
add_shortcode( 'insight_flex_list_content', 'tommusrhodus_flex_list_content_shortcode' );

// Parent Element
function tommusrhodus_flex_list_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'                    => esc_html__( 'Flex List' , 'tommusrhodus' ),
		    'base'                    => 'insight_flex_list',
		    'as_parent'               => array('only' => 'insight_flex_list_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
		    'content_element'         => true,
		    'show_settings_on_create' => true,
		    "js_view" => 'VcColumnView',
		    "category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
		    'params'          => array(
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
add_action( 'vc_before_init', 'tommusrhodus_flex_list_shortcode_vc' );

// Nested Element
function tommusrhodus_flex_list_content_shortcode_vc() {

	$icons = array('Install TommusRhodus Framework' => 'Install TommusRhodus Framework');
		
	if( function_exists('tommusrhodus_get_icons') ){
		$icons = tommusrhodus_get_icons();	
	}

	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'            => esc_html__('Flex List Item', 'tommusrhodus'),
		    'base'            => 'insight_flex_list_content',
		    "category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
		    'content_element' => true,
		    'as_child'        => array('only' => 'insight_flex_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
		    'params'          => array(
		    	array(
					"type"       => "dropdown",
					"heading"    => __( "Item Type", 'tommusrhodus' ),
					"param_name" => "layout",
					"value"      => array(
						'Icon Left and Title Right'  								=> 'icon_title',
						'Text Left and Title Right'     							=> 'text_title',
						'Title Left and Arrow Right'     							=> 'title_arrow',
						'Icon/Image & Title Left, Arrow Right'						=> 'icon_title_arrow',
					)
				),
				array(
					"type" => "tommusrhodus_icons",
					"heading" => esc_html__( "Click an Icon to choose", 'tommusrhodus' ),
					"param_name" => "icon",
					"value" => $icons,
					'description' => 'Type "none" or leave blank to hide icons.'
				),
		    	array(
		    		"type" => "textfield",
		    		"heading" => esc_html__("Title", 'tommusrhodus'),
		    		"param_name" => "title",
		    		'holder' => 'div'
		    	),
	            array(
	            	"type" => "textarea_html",
	            	"heading" => esc_html__("Block Content", 'tommusrhodus'),
	            	"param_name" => "content"
	            ),
	            array(
		    		"type"       => "attach_image",
		    		"heading"    => __( "Icon Image", 'tommusrhodus' ),
		    		"param_name" => "image",
		    		"description" => __( 'Will override icon font if an image is set - Only used in the "Icon/Image & Title Left, Arrow Right" layout.', 'tommusrhodus' ),
		    	),
	            array(
					"type"        => "vc_link",
					"heading"     => __( "Link", 'tommusrhodus' ),
					"param_name"  => "link"
				),
	            array(
	            	"type" => "textfield",
	            	"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
	            	"param_name" => "custom_css_class",
	            	"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.<br><br><div class="wpb_element_label">Need help with this block? Check out the <a target="_blank" href="https://www.youtube.com/watch?v=YHRpDbuybXw">Video Tutorial</a></div>',
	            ),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_flex_list_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_insight_flex_list extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_insight_flex_list_content extends WPBakeryShortCode {}
}