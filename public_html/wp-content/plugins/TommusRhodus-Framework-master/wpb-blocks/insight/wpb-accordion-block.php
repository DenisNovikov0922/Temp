<?php

/**
 * The Shortcode
 */
function tommusrhodus_accordion_shortcode( $atts, $content = null ) {
	extract( 
		shortcode_atts( 
			array(
				'custom_css_class' => ''
			), $atts 
		) 
	);
	
	$output = '<ul class="list-group '. esc_attr($custom_css_class) .'">'. do_shortcode($content) .'</ul>';

	return $output;
}
add_shortcode( 'insight_accordion', 'tommusrhodus_accordion_shortcode' );

/**
 * The Shortcode
 */
function tommusrhodus_accordion_content_shortcode( $atts, $content = null ) {
	extract( 
		shortcode_atts( 
			array(
				'title' => '',
				'custom_css_class' => ''
			), $atts 
		) 
	);
	
	$output = '
	    <li class="list-group-item '. esc_attr($custom_css_class) .'">
	        <div class="d-flex justify-content-between align-items-center">
	            <a class="d-flex align-items-center flex-fill" href="#" data-toggle="collapse" data-target="#'. sanitize_title($title) .'">
	                <span class="mb-0 text-primary py-1 font-weight-bold">'. $title .'</span>
	            </a>
	            <i class="material-icons d-block text-dark">keyboard_arrow_right</i>
	        </div>
	        <div id="'. sanitize_title($title) .'" class="collapse text-dark">
	            <div class="py-1">
	                '. do_shortcode($content) .'
	            </div>
	        </div>
	    </li>
	';

	return $output;
}
add_shortcode( 'insight_accordion_content', 'tommusrhodus_accordion_content_shortcode' );

// Parent Element
function tommusrhodus_accordion_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'                    => esc_html__( 'Accordion' , 'tommusrhodus' ),
		    'base'                    => 'insight_accordion',
		    'description'             => esc_html__( 'Create Accordion Content', 'tommusrhodus' ),
		    'as_parent'               => array('only' => 'insight_accordion_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
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
add_action( 'vc_before_init', 'tommusrhodus_accordion_shortcode_vc' );

// Nested Element
function tommusrhodus_accordion_content_shortcode_vc() {
	vc_map( 
		array(
			"icon" => 'insight-vc-block',
		    'name'            => esc_html__('Accordion Content', 'tommusrhodus'),
		    'base'            => 'insight_accordion_content',
		    'description'     => esc_html__( 'Toggle Content Element', 'tommusrhodus' ),
		    "category" => esc_html__('Insight WP Theme', 'tommusrhodus'),
		    'content_element' => true,
		    'as_child'        => array('only' => 'insight_accordion'), // Use only|except attributes to limit parent (separate multiple values with comma)
		    'params'          => array(
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
	            	"type" => "textfield",
	            	"heading" => esc_html__("Extra CSS Class Name", 'tommusrhodus'),
	            	"param_name" => "custom_css_class",
	            	"description" => '<code>DEVELOPERS ONLY</code> - Style particular content element differently - add a class name and refer to it in custom CSS.<br><br><div class="wpb_element_label">Need help with this block? Check out the <a target="_blank" href="https://www.youtube.com/watch?v=YHRpDbuybXw">Video Tutorial</a></div>',
	            ),
		    ),
		) 
	);
}
add_action( 'vc_before_init', 'tommusrhodus_accordion_content_shortcode_vc' );

// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
if(class_exists('WPBakeryShortCodesContainer')){
    class WPBakeryShortCode_insight_accordion extends WPBakeryShortCodesContainer {}
}

// Replace Wbc_Inner_Item with your base name from mapping for nested element
if(class_exists('WPBakeryShortCode')){
    class WPBakeryShortCode_insight_accordion_content extends WPBakeryShortCode {}
}