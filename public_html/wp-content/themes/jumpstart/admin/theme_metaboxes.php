<?php 

/**
 * tommusrhodus_custom_metaboxes()
 * 
 * Build the custom metaboxes for the theme.
 * Runs through the metabox framework CMB2.
 * 
 * @documentation https://github.com/CMB2/CMB2
 * @param $meta_boxes -- The metabox object of CMB2
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_custom_metaboxes' ) )){
	function tommusrhodus_custom_metaboxes( $meta_boxes ) {
		
		$prefix             		= '_tommusrhodus_';
		$social_options 			= array_keys( tommusrhodus_get_svg_icons() );
		$single_portfolio_layouts 	= tommusrhodus_get_portfolio_single_layouts();
		$single_post_layouts 		= tommusrhodus_get_blog_single_layouts();
		$header_options				= tommusrhodus_get_header_layouts();
		$footer_options				= tommusrhodus_get_footer_layouts();
		$bg_colour_options = array(
			'none'			=> 'Do Not Override CTA Background On This Page',
			'bg-white' 		=> 'White Background',
			'bg-light' 		=> 'Light Background',
			'bg-primary'	=> 'Primary Background',
			'bg-dark'		=> 'Dark Background',
			'bg-gradient' 	=> 'Gradient Background',
		);

		$header_overrides['none'] = 'Do Not Override Header Option On This Page';
		foreach( $header_options as $key => $value ){
			$header_overrides[$key] = 'Override Header: ' . $value; 	
		}

		$footer_overrides['none'] = 'Do Not Override Footer Option On This Page';
		foreach( $footer_options as $key => $value ){
			$footer_overrides[$key] = 'Override Footer: ' . $value; 	
		}

		$single_portfolio_layouts['none'] = 'Do Not Override Footer Option On This Page';
		foreach( $single_portfolio_layouts as $key => $value ){
			$single_portfolio_layouts[$key] = 'Override Layout: ' . $value; 	
		}

		$single_post_layoutss['none'] = 'Do Not Override Footer Option On This Page';
		foreach( $single_post_layouts as $key => $value ){
			$single_post_layouts[$key] = 'Override Layout: ' . $value; 	
		}

		$custom_menus = array();
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$custom_menus['no'] = 'Do not use side nav on this page';
		if ( is_array( $menus ) && ! empty( $menus ) ) {
			foreach ( $menus as $single_menu ) {
				if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
					$custom_menus[ $single_menu->slug ] = $single_menu->name;
				}
			}
		}

		$meta_boxes[] = array(
			'id' => 'override_header_metabox',
			'title' => esc_html__('Page Overrides', 'jumpstart'),
			'object_types' => array( 'page', 'portfolio', 'team', 'post', 'career', 'product', 'testimonial', 'documentation' ), // post type
			'context' => 'normal',
			'priority' => 'low',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'Override Logo?', 'jumpstart' ),
					'id'   => $prefix . 'logo_override',
					'desc' => esc_html__( 'Add an image here if you would like to show a custom logo for this page only.', 'jumpstart' ),
					'type' => 'file',
				),
				array(
					'name'         => esc_html__( 'Override Header?', 'jumpstart' ),
					'desc'         => esc_html__( 'Header Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'jumpstart' ),
					'id'           => $prefix . 'header_override',
					'type'         => 'select',
					'options'      => $header_overrides,
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Override Footer?', 'jumpstart' ),
					'desc'         => esc_html__( 'Footer Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'jumpstart' ),
					'id'           => $prefix . 'footer_override',
					'type'         => 'select',
					'options'      => $footer_overrides,
					'std'          => 'none'
				),
				array(
				    'name'             => 'Select a menu to display in side menu area',
				    'desc'             => 'Use this option to set a specific menu for area.',
				    'id'               => $prefix . 'side_menu',
				    'type'             => 'select',
				    'show_option_none' => false,
				    'default'          => 'none',
				    'options'          => $custom_menus,
				),
				array(
					'name'         => esc_html__( 'Use custom colors?', 'jumpstart' ),
					'desc'         => esc_html__( 'Allow the page to use the custom colour controls below.', 'jumpstart' ),
					'id'           => $prefix . 'custom_colours',
					'type'         => 'select',
					'options'      => array(
						'yes' => 'Yes',
						'no'  => 'No'
					),
					'std'          => 'no'
				),
				array(
					'name'    		=> esc_html__( 'Primary Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'primary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Hover Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'primary_hover_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Secondary Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'secondary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Light Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'light_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Dark Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'dark_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'primary_2_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Hover Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'primary_2_hover_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 3 Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'primary_3_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_primary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Alt Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_primary_alt_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Secondary Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_secondary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Light Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_light_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Dark Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_dark_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_primary_2_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Alt Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_primary_2_alt_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 3 Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_primary_3_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Success Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_success_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Success Hover Background Colour', 'jumpstart' ),
					'id'      		=> $prefix . 'bg_success_hover_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
			)
		);

		$meta_boxes[] = array(
			'id'           => 'portfolio_layout_metabox',
			'title'        => esc_html__( 'Portfolio Item Layout Options', 'jumpstart' ),
			'object_types' => array( 'portfolio' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => array(
				array(
					'name' => esc_html__( 'Project Logo Image', 'jumpstart' ),
					'id'   => $prefix . 'portfolio_item_logo',
					'desc' => esc_html__( 'Shown on Single and Archive View', 'jumpstart' ),
					'type' => 'file',
				),
				array(
					'name' => esc_html__( 'Project Subtitle', 'jumpstart' ),
					'desc' => '',
					'id'   => $prefix . 'porfolio_item_subtitle',
					'type' => 'text',
				),
				array(
				    'id'          => $prefix . 'meta_repeat_group',
				    'type'        => 'group',
				    'description' => esc_html__( 'Meta Titles & Descriptions', 'jumpstart' ),
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Entry', 'jumpstart' ),
				        'remove_button' => esc_html__( 'Remove Entry', 'jumpstart' ),
				        'sortable'      => true, // beta
				    ),
				    'fields'       => array(
						array(
							'name' => esc_html__( 'Additional Item Title', 'jumpstart' ),
							'desc' => esc_html__( "Title of your Additional Meta", 'jumpstart' ),
							'id'   => $prefix . 'the_additional_title',
							'type' => 'text'
						),
						array(
							'name' => esc_html__( 'Additional Item Detail', 'jumpstart' ),
							'desc' => esc_html__( "Detail of your Additional Meta", 'jumpstart' ),
							'id'   => $prefix . 'the_additional_detail',
							'type' => 'text'
						),
				    ),
				),
				array(
					'name' 		=> esc_html__( 'Addition Detail Website Label', 'jumpstart' ),
					'desc' 		=> '',
					'id'   		=> $prefix . 'porfolio_item_website_label',					
					'default'	=> 'Website',
					'type' 		=> 'text',
				),
				array(
					'name' 		=> esc_html__( 'Addition Detail Website URL', 'jumpstart' ),
					'desc' 		=> '',
					'id'   		=> $prefix . 'porfolio_item_website_url',					
					'default'	=> 'linktosite.io',
					'type' 		=> 'text',
				),
			)
		);
		
		$meta_boxes[] = array(
			'id'           => 'team_metabox',
			'title'        => esc_html__( 'Team Member Details', 'jumpstart' ),
			'object_types' => array('team'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'Job Title', 'jumpstart' ),
					'desc' => '(Optional) Enter a Job Title for this Team Member',
					'id'   => $prefix . 'the_job_title',
					'type' => 'text',
				),
				array(
				    'id'          => $prefix . 'team_social_icons',
				    'type'        => 'group',
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Icon', 'jumpstart' ),
				        'remove_button' => esc_html__( 'Remove Icon', 'jumpstart' ),
				        'sortable'      => true
				    ),
				    'fields' => array(
						array(
							'name' => 'Social Icon',
							'desc' => 'What icon would you like for this team members first social profile?',
							'id' => $prefix . 'social_icon',
							'type' => 'select',
							'options' => $social_options
						),
						array(
							'name' => esc_html__('URL for Social Icon', 'jumpstart'),
							'desc' => esc_html__("Enter the URL for Social Icon 1 e.g www.google.com", 'jumpstart'),
							'id'   => $prefix . 'social_icon_url',
							'type' => 'text'
						),
				    ),
				),
			)
		);
		
		$meta_boxes[] = array(
			'id'           => 'testimonial_layout_metabox',
			'title'        => esc_html__( 'Testimonial Item Layout Options', 'jumpstart' ),
			'object_types' => array( 'testimonial' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => array(
				array(
					'name' => esc_html__( 'Company Logo', 'jumpstart' ),
					'id'   => $prefix . 'testimonial_logo',
					'type' => 'file'
				),
				array(
				    'id'          => $prefix . 'meta_repeat_group',
				    'type'        => 'group',
				    'description' => esc_html__( 'Meta Titles & Descriptions', 'jumpstart' ),
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Entry', 'jumpstart' ),
				        'remove_button' => esc_html__( 'Remove Entry', 'jumpstart' ),
				        'sortable'      => true, // beta
				    ),
				    'fields'       => array(
						array(
							'name' => esc_html__( 'Additional Item Title', 'jumpstart' ),
							'desc' => esc_html__( "Title of your Additional Meta", 'jumpstart' ),
							'id'   => 'meta_title',
							'type' => 'text'
						),
						array(
							'name' => esc_html__( 'Additional Item Detail', 'jumpstart' ),
							'desc' => esc_html__( "Detail of your Additional Meta", 'jumpstart' ),
							'id'   => 'meta_detail',
							'type' => 'textarea'
						),
				    ),
				),
			)
		);

		$meta_boxes[] = array(
			'id'           => 'post_format_metabox',
			'title'        => esc_html__( 'Post Format Data', 'jumpstart' ),
			'object_types' => array( 'post' ), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'URL (Link Format)', 'jumpstart' ),
					'desc' => 'Enter a URL to link to',
					'id'   => $prefix . 'link_format_url',
					'type' => 'text',
				),
				array(
					'name' => esc_html__( 'Embed ID (Video Format) - eg 40842620', 'jumpstart' ),
					'desc' => 'Enter a video URL',
					'id'   => $prefix . 'video_url',
					'type' => 'text',
				),
				array(
					'name'         => esc_html__( 'Media Provider (Video Format)', 'jumpstart' ),
					'id'           => $prefix . 'video_provider',
					'type'         => 'select',
					'options'      => array(
						'vimeo' 		=> 'Vimeo',
						'youtube' 		=> 'YouTube'
					),
					'std'          => 'vimeo'
				),
				array(
					'name' => esc_html__( 'Author (Quote Format)', 'jumpstart' ),
					'desc' => 'Enter quote author',
					'id'   => $prefix . 'quote_format_author',
					'type' => 'text',
				),
			)
		);

		$meta_boxes[] = array(
			'id'           => 'standard_page_metabox',
			'title'        => esc_html__( 'Standard Page Metabox (no page builder)', 'jumpstart' ),
			'object_types' => array( 'page' ), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name'         => esc_html__( 'Show Sharing Buttons?', 'jumpstart' ),
					'id'           => $prefix . 'show_page_sharing',
					'type'         => 'select',
					'options'      => array(
						'yes' 			=> 'Yes',
						'no' 			=> 'No'
					),
					'std'          => 'vimeo'
				),
				array(
					'name' => esc_html__( 'Small Print', 'jumpstart' ),
					'desc' => '',
					'id'   => $prefix . 'page_small_print',
					'type' => 'textarea',
				),
			)
		);
		
		return $meta_boxes;
		
	}
	add_filter( 'cmb2_meta_boxes', 'tommusrhodus_custom_metaboxes' );
}

if(!( function_exists( 'tommusrhodus_taxonomy_metabox' ) )) {

	/**
	 * Hook in and add a metabox to add fields to taxonomy terms
	 */
	function tommusrhodus_taxonomy_metabox() {

		$prefix             		= '_tommusrhodus_';

		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'documentation_category_edit',
			'title'            => esc_html__( 'Category Metabox', 'jumpstart' ),
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'documentation_category' ),
		) );

		$cmb_term->add_field( array(
			'name' => esc_html__( 'Icon Name', 'jumpstart' ),
			'id'   => $prefix . 'documentation_category_icon',
			'type' => 'text',
		) );

		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . '_category_edit',
			'title'            => esc_html__( 'Category Colour', 'jumpstart'  ),
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'category' ),
		) );

		$cmb_term->add_field( array(
			'name' => esc_html__( 'Category Colour', 'jumpstart'  ),
			'id'   => $prefix . 'category_color',
			'type'    => 'colorpicker',
			'default' => '#dc3545',
		) );

	}

	add_action( 'cmb2_admin_init', 'tommusrhodus_taxonomy_metabox' );

}