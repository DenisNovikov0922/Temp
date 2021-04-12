<?php 

/**
 * tommusrhodus_initialise_framework()
 * 
 * Add theme support items for the theme setup.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */

if(!( function_exists( 'tommusrhodus_initialise_framework' ) )){
	function tommusrhodus_initialise_framework() {

		$social_options = array_keys( tommusrhodus_get_svg_icons() );
		$bg_colour_options = array(
			'bg-white' 		=> 'White Background',
			'bg-light' 		=> 'Light Background',
			'bg-primary'	=> 'Primary Background',
			'bg-dark'		=> 'Dark Background',
			'bg-gradient' 	=> 'Gradient Background',
		);
		
		// Portfolio Post Type Options
		$framework_args['post_types']['portfolio'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Portfolio', 'jumpstart' ),
				'singular_name'      => esc_html__( 'Portfolio', 'jumpstart' ),
				'add_new'            => esc_html__( 'Add New', 'jumpstart' ),
				'add_new_item'       => esc_html__( 'Add New Portfolio', 'jumpstart' ),
				'edit_item'          => esc_html__( 'Edit Portfolio', 'jumpstart' ),
				'new_item'           => esc_html__( 'New Portfolio', 'jumpstart' ),
				'view_item'          => esc_html__( 'View Portfolio', 'jumpstart' ),
				'search_items'       => esc_html__( 'Search Portfolios', 'jumpstart' ),
				'not_found'          => esc_html__( 'No portfolios found', 'jumpstart' ),
				'not_found_in_trash' => esc_html__( 'No portfolios found in Trash', 'jumpstart' ),
				'parent_item_colon'  => esc_html__( 'Parent Portfolio:', 'jumpstart' ),
				'menu_name'          => esc_html__( 'Portfolio', 'jumpstart' ),
			),
			'supports' => array( 
				'title', 
				'editor', 
				'thumbnail', 
				'post-formats', 
				'comments', 
				'author',
				'excerpt'
			),
			'taxonomies' => array( 
				'portfolio_category' // See line 90
			),
			'rewrite' => array( 
				'slug' => get_option( 'portfolio_post_type_slug', 'portfolio' )
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'jumpstart Portfolio Entries', 'jumpstart' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-portfolio',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Portfolio taxonomy type options
		$framework_args['taxonomy_types']['portfolio_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Portfolio Categories', 'jumpstart' ),
				'singular_name'     => esc_html__( 'Portfolio Category', 'jumpstart' ),
				'search_items'      => esc_html__( 'Search Portfolio Categories', 'jumpstart' ),
				'all_items'         => esc_html__( 'All Portfolio Categories', 'jumpstart' ),
				'parent_item'       => esc_html__( 'Parent Portfolio Category', 'jumpstart' ),
				'parent_item_colon' => esc_html__( 'Parent Portfolio Category:', 'jumpstart' ),
				'edit_item'         => esc_html__( 'Edit Portfolio Category', 'jumpstart' ), 
				'update_item'       => esc_html__( 'Update Portfolio Category', 'jumpstart' ),
				'add_new_item'      => esc_html__( 'Add New Portfolio Category', 'jumpstart' ),
				'new_item_name'     => esc_html__( 'New Portfolio Category Name', 'jumpstart' ),
				'menu_name'         => esc_html__( 'Portfolio Categories', 'jumpstart' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'portfolio' )
		);
		
		// Team Post Type Options
		$framework_args['post_types']['team'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Team', 'jumpstart' ),
				'singular_name'      => esc_html__( 'Team', 'jumpstart' ),
				'add_new'            => esc_html__( 'Add New', 'jumpstart' ),
				'add_new_item'       => esc_html__( 'Add New Team', 'jumpstart' ),
				'edit_item'          => esc_html__( 'Edit Team', 'jumpstart' ),
				'new_item'           => esc_html__( 'New Team', 'jumpstart' ),
				'view_item'          => esc_html__( 'View Team', 'jumpstart' ),
				'search_items'       => esc_html__( 'Search Teams', 'jumpstart' ),
				'not_found'          => esc_html__( 'No Teams found', 'jumpstart' ),
				'not_found_in_trash' => esc_html__( 'No Teams found in Trash', 'jumpstart' ),
				'parent_item_colon'  => esc_html__( 'Parent Team:', 'jumpstart' ),
				'menu_name'          => esc_html__( 'Team', 'jumpstart' ),
			),
			'supports' => array( 
				'title', 
				'editor', 
				'thumbnail', 
				'post-formats', 
				'comments', 
				'author',
				'excerpt'
			),
			'taxonomies' => array( 
				'team_category'
			),
			'rewrite' => array( 
				'slug' => get_option( 'team_post_type_slug', 'team' ),
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'jumpstart Team Entries', 'jumpstart' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-id-alt',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Team taxonomy type options
		$framework_args['taxonomy_types']['team_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Team Categories', 'jumpstart' ),
				'singular_name'     => esc_html__( 'Team Category', 'jumpstart' ),
				'search_items'      => esc_html__( 'Search Team Categories', 'jumpstart' ),
				'all_items'         => esc_html__( 'All Team Categories', 'jumpstart' ),
				'parent_item'       => esc_html__( 'Parent Team Category', 'jumpstart' ),
				'parent_item_colon' => esc_html__( 'Parent Team Category:', 'jumpstart' ),
				'edit_item'         => esc_html__( 'Edit Team Category', 'jumpstart' ), 
				'update_item'       => esc_html__( 'Update Team Category', 'jumpstart' ),
				'add_new_item'      => esc_html__( 'Add New Team Category', 'jumpstart' ),
				'new_item_name'     => esc_html__( 'New Team Category Name', 'jumpstart' ),
				'menu_name'         => esc_html__( 'Team Categories', 'jumpstart' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'team' )
		);
		
		// Documentation Post Type Options
		$framework_args['post_types']['documentation'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Documentation', 'jumpstart' ),
				'singular_name'      => esc_html__( 'Documentation Item', 'jumpstart' ),
				'add_new'            => esc_html__( 'Add New', 'jumpstart' ),
				'add_new_item'       => esc_html__( 'Add New Documentation Item', 'jumpstart' ),
				'edit_item'          => esc_html__( 'Edit Documentation Item', 'jumpstart' ),
				'new_item'           => esc_html__( 'New Documentation Item', 'jumpstart' ),
				'view_item'          => esc_html__( 'View Documentation Item', 'jumpstart' ),
				'search_items'       => esc_html__( 'Search Documentation Items', 'jumpstart' ),
				'not_found'          => esc_html__( 'No Documentation Items found', 'jumpstart' ),
				'not_found_in_trash' => esc_html__( 'No Documentation Items found in Trash', 'jumpstart' ),
				'parent_item_colon'  => esc_html__( 'Parent Documentation Item:', 'jumpstart' ),
				'menu_name'          => esc_html__( 'Documentation', 'jumpstart' ),
			),
			'supports' => array( 
				'title', 
				'editor',  
				'post-formats', 
				'comments', 
				'author',
				'excerpt',
				'thumbnail'
			),
			'taxonomies' => array( 
				'documentation_category'
			),
			'rewrite' => array( 
				'slug' => get_option( 'documentation_post_type_slug', 'documentation' ),
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'jumpstart documentation Entries', 'jumpstart' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-document',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Documentation taxonomy type options
		$framework_args['taxonomy_types']['documentation_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Documentation Categories', 'jumpstart' ),
				'singular_name'     => esc_html__( 'Documentation Category', 'jumpstart' ),
				'search_items'      => esc_html__( 'Search Documentation Categories', 'jumpstart' ),
				'all_items'         => esc_html__( 'All Documentation Categories', 'jumpstart' ),
				'parent_item'       => esc_html__( 'Parent Documentation Category', 'jumpstart' ),
				'parent_item_colon' => esc_html__( 'Parent Documentation Category:', 'jumpstart' ),
				'edit_item'         => esc_html__( 'Edit Documentation Category', 'jumpstart' ), 
				'update_item'       => esc_html__( 'Update Documentation Category', 'jumpstart' ),
				'add_new_item'      => esc_html__( 'Add New Documentation Category', 'jumpstart' ),
				'new_item_name'     => esc_html__( 'New Documentation Category Name', 'jumpstart' ),
				'menu_name'         => esc_html__( 'Documentation Categories', 'jumpstart' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'documentation' )
		);
		
		if( did_action( 'elementor/loaded' ) ){
		
			// Enqueue Elementor Blocks
			$framework_args['elementor_blocks'] = array(
				'theme_name' => 'jumpstart',
				'blocks'     => array(
					'alert-block',
					'accordion-block',
					'blog-block',
					'card-block',
					'countdown-block',
					'counter-block',
					'hero-header-block',
					'hero-header-cta-block',
					'hero-slider-block',
					'icon-text-block',
					'image-carousel-block',
					'image-divider-block',
					'image-over-image-block',
					'inner-decorations-block',
					'maps-block',
					'modal-block',
					'single-testimonial-block',
					'section-decorations-block',
					'tabs-block',
					'tabs-html-block',
					'testimonial-carousel-block',
					'team-block',
					'twitter-block',
					'typed-text-block',
					'portfolio-block',
					'floating-image-block',
					'processes-block',
					'pricing-table-block',
					'video-lightbox-block',
				)
			);
		
		}

		// Enqueue Widgets
		$framework_args['widgets'] = array(
			'theme_name' => 'jumpstart',
			'widgets'    => array(
				'contact-widget',
				'menu-widget',
				'twitter-widget',
				'popular-posts-widget',
				'recent-posts-widget',
				'sticky-widget'
			)
		);
		
		// Theme Options
		$framework_args['theme_options'] = array(
			array(
				'title'        => 'Sitewide Settings',
				'id'           => 'style_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'site_settings',
						'title'       => 'Sitewide Settings',
						'description' => '',
						'options' => array(		
							array(
								'id'        => 'show_preloader',
								'title'     => esc_html__( 'Show site preloader?', 'jumpstart' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, use preloader', 'jumpstart' ),
									'no'    => esc_html__( 'No, remove preloader', 'jumpstart' )
								)
							),
							array(
								'id'        => 'show_sharing_buttons',
								'title'     => esc_html__( 'Show sharing buttons?', 'jumpstart' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, show sharing buttons', 'jumpstart' ),
									'no'    => esc_html__( 'No, hide sharing buttons', 'jumpstart' )
								)
							),
							array(
								'id'        => 'disable_page_fade',
								'title'     => esc_html__( 'Disable page fade effect on load?', 'jumpstart' ),
								'default'   => 'no',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'no'    => esc_html__( 'No, Keep Page Fading Effect', 'jumpstart' ),
									'yes'   => esc_html__( 'Yes, Remove Page Fading Effect', 'jumpstart' ),
								)
							),	
							array(
								'id'        => 'menu_drop_type',
								'title'     => esc_html__( 'Menu dropdown method (Desktop)', 'jumpstart' ),
								'default'   => 'click-menu',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'click-menu'   => esc_html__( 'Click', 'jumpstart' ),
									'hover-menu'    => esc_html__( 'Hover', 'jumpstart' )
								)
							),			
						)
					),
					array(
						'id'          => 'tyography_settings',
						'title'       => 'Typography Settings',
						'description' => 'Here you can take control of your themes google fonts.',
						'options' => array(		
							array(
								'id'        => 'google_font_string',
								'title'     => esc_html__( 'Google Font URL (Default - Nunito:400,400i,600,700&display=swap)' , 'jumpstart' ),
								'default'   => 'Nunito:400,400i,600,700&display=swap',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),	
							array(
								'id'        => 'body_font_name',
								'title'     => esc_html__( 'Body Font Name', 'jumpstart' ),
								'default'   => 'Nunito',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),					
						)
					)
				)
			),
			array(
				'title'        => 'Theme Colors',
				'id'           => 'theme_colors',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'theme_colors',
						'title'       => 'All Theme Colors',
						'description' => 'Here you can take control of your themes colours.',
						'options' => array(		
							array(
								'id'        => 'body_colour',
								'title'     => esc_html__( 'Body Text Color', 'jumpstart' ),
								'default'   => '#555A64',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary',
								'title'     => esc_html__( 'Primary Color', 'jumpstart' ),
								'default'   => '#2568EF',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'primary_hover',
								'title'     => esc_html__( 'Primary Hover Color', 'jumpstart' ),
								'default'   => '#1054dd',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'secondary',
								'title'     => esc_html__( 'Secondary Color', 'jumpstart' ),
								'default'   => '#EAEDF2',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'light',
								'title'     => esc_html__( 'Light Color', 'jumpstart' ),
								'default'   => '#F7F9FC',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'dark',
								'title'     => esc_html__( 'Dark Color', 'jumpstart' ),
								'default'   => '#2C3038',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary_2',
								'title'     => esc_html__( 'Primary 2 Color', 'jumpstart' ),
								'default'   => '#FF564F',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'primary_2_hover',
								'title'     => esc_html__( 'Primary 2 Hover Color', 'jumpstart' ),
								'default'   => '#ff3129',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary_3',
								'title'     => esc_html__( 'Primary 3 Color', 'jumpstart' ),
								'default'   => '#051B35',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary',
								'title'     => esc_html__( 'Primary Background Color', 'jumpstart' ),
								'default'   => '#2568EF',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_alt',
								'title'     => esc_html__( 'Primary Alt Background Color', 'jumpstart' ),
								'default'   => '#0f50d2',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_secondary',
								'title'     => esc_html__( 'Secondary Background Color', 'jumpstart' ),
								'default'   => '#EAEDF2',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_light',
								'title'     => esc_html__( 'Light Background Color', 'jumpstart' ),
								'default'   => '#F7F9FC',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_dark',
								'title'     => esc_html__( 'Dark Background Color', 'jumpstart' ),
								'default'   => '#212529',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_2',
								'title'     => esc_html__( 'Primary 2 Background Color', 'jumpstart' ),
								'default'   => '#2C3038',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_2_alt',
								'title'     => esc_html__( 'Primary 2 Alt Background Color', 'jumpstart' ),
								'default'   => '#ff251c',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_3',
								'title'     => esc_html__( 'Primary 3 Background Color', 'jumpstart' ),
								'default'   => '#051b35',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'bg_success',
								'title'     => esc_html__( 'Success Background Color', 'jumpstart' ),
								'default'   => '#28a745',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'bg_success_hover',
								'title'     => esc_html__( 'Sucess Hover Background Color', 'jumpstart' ),
								'default'   => '#1e7e34',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
						)
					)
				)
			),
			array(
				'title'        => 'Header Settings',
				'id'           => 'header',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'header',
						'title'       => 'Header Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'header_layout',
								'title'     => esc_html__( 'Header Layout', 'jumpstart' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'use_sticky_header',
								'title'     => esc_html__( 'Use sticky menu?', 'jumpstart' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Stick Menu on Scroll', 'jumpstart' ),
									'no'    => esc_html__( 'No, Menu Stays in Place', 'jumpstart' )
								)
							),
							array(
								'id'        => 'sticky_header_logo',
								'title'     => esc_html__( 'Sticky Header Logo (should be the inverse of your normal logo)', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					),
					array(
						'id'          => 'header',
						'title'       => 'Logo Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'logo_height',
								'title'     => esc_html__( 'Logo Height (in px)', 'jumpstart' ),
								'default'   => '36',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					),
					array(
						'id'          => 'header_button',
						'title'       => 'Header Button Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'header_button_label',
								'title'     => esc_html__( 'Button Label', 'jumpstart' ),
								'default'   => 'Login',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'header_button_url',
								'title'     => esc_html__( 'Button URL', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => 'Blog Settings',
				'id'           => 'blog_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'blog_archive',
						'title'       => 'Blog Archive Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'blog_hero',
								'title'     => esc_html__( 'Use hero section on blog?', 'jumpstart' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show the hero section', 'jumpstart' ),
									'no'    => esc_html__( 'No, Hide the hero section', 'jumpstart' )
								)
							),
							array(
								'id'        => 'blog_layout',
								'title'     => esc_html__( 'Blog Layout', 'jumpstart' ),
								'default'   => 'listing-2',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_blog_layouts()
							),
							array(
								'id'        => 'blog_layout_listing_3_title',
								'title'     => esc_html__( 'Blog Layout Listing 3 Title', 'jumpstart' ),
								'default'   => 'Newsroom',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'blog_layout_listing_3_subtitle',
								'title'     => esc_html__( 'Blog Layout Listing 3 Subtitle', 'jumpstart' ),
								'default'   => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_header_layout',
								'title'     => esc_html__( 'Blog Archive Header Layout', 'jumpstart' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'post_archive_logo',
								'title'     => esc_html__( 'Blog Archive Logo', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_title',
								'title'     => esc_html__( 'Post Archive Title', 'jumpstart' ),
								'default'   => 'Our Blog',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_subtitle',
								'title'     => esc_html__( 'Post Archive Subtitle', 'jumpstart' ),
								'default'   => 'Read our latest writings.',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_popular_searches',
								'title'     => esc_html__( 'Popular Searches (Comma Separated)', 'jumpstart' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					),	
					array(
						'id'          => 'blog_footer_cta_settings',
						'title'       => 'Blog Footer CTA Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'blog_footer_cta_background_style',
								'title'     => esc_html__( 'Footer CTA Background', 'jumpstart' ),
								'default'   => 'bg-gradient',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $bg_colour_options
							),
							array(
								'id'        => 'blog_footer_cta',
								'title'     => esc_html__( 'Footer CTA (Example - <h3 class="display-4">Never miss a minute</h3><div class="lead">Get great content to your inbox every week. No spam.</div>', 'jumpstart' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'refresh',
							),
							array(
								'id'        => 'blog_footer_cta_form_shortcode',
								'title'     => esc_html__( 'Default Footer: CTA Form Shortcode', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'refresh',
							),			
						)	
					),				
				)
			),
			array(
				'title'        => 'Portfolio Settings',
				'id'           => 'portfolio_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'portfolio_archive',
						'title'       => 'Portfolio Archive Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'portfolio_layout',
								'title'     => esc_html__( 'Portfolio Layout', 'jumpstart' ),
								'default'   => 'featured',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_portfolio_layouts()
							),
							array(
								'id'        => 'portfolio_archive_header_layout',
								'title'     => esc_html__( 'Portfolio Archive Header Layout', 'jumpstart' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'portfolio_archive_logo',
								'title'     => esc_html__( 'Portfolio Archive Logo', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					),
					array(
						'id'          => 'portfolio_cta',
						'title'       => 'Portfolio CTA Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'portfolio_footer_cta_background_style',
								'title'     => esc_html__( 'Footer CTA Background', 'jumpstart' ),
								'default'   => 'bg-white',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $bg_colour_options
							),
							array(
								'id'        => 'portfolio_footer_cta',
								'title'     => esc_html__( 'Footer CTA (Example - <h3 class="display-4">Never miss a minute</h3><div class="lead">Get great content to your inbox every week. No spam.</div>', 'jumpstart' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'portfolio_footer_cta_form_shortcode',
								'title'     => esc_html__( 'Default Footer: CTA Form Shortcode', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),			
						)
					),
				)
			),	
			array(
				'title'        => 'Documentation Settings',
				'id'           => 'documentation_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'documentation_archive',
						'title'       => esc_html__( 'Documentation Archive View', 'jumpstart' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'documentation_archive_title',
								'title'     => esc_html__( 'Documentation Archive Title', 'jumpstart' ),
								'default'   => 'Help Center',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_subtitle',
								'title'     => esc_html__( 'Documentation Archive Subtitle', 'jumpstart' ),
								'default'   => 'Get advice and help from our expert team',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_header_layout',
								'title'     => esc_html__( 'Documentation Archive Header Layout', 'jumpstart' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'documentation_archive_logo',
								'title'     => esc_html__( 'Documentation Archive Logo', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_popular_searches',
								'title'     => esc_html__( 'Popular Searches (Comma Seperated)', 'jumpstart' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_email_us_address',
								'title'     => esc_html__( 'Sidebar "Email Us" Address', 'jumpstart' ),
								'default'   => 'hello@jumpstart.io',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_call_us_number',
								'title'     => esc_html__( 'Sidebar "Call Us" Telephone Number', 'jumpstart' ),
								'default'   => '1800 488 328',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_get_in_touch_url',
								'title'     => esc_html__( 'Sidebar "Email Us" Link', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_sidebar_cta_label',
								'title'     => esc_html__( 'Sidebar CTA Button Label', 'jumpstart' ),
								'default'   => 'Open a Suport Ticket',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_sidebar_cta_url',
								'title'     => esc_html__( 'Sidebar CTA Button URL', 'jumpstart' ),
								'default'   => '#',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => 'Careers Settings',
				'id'           => 'careers_single_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'careers_single',
						'title'       => esc_html__( 'Careers Single View', 'jumpstart' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'careers_form',
								'title'     => esc_html__( 'Apply Form Shortcode', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => 'Footer Settings',
				'id'           => 'footer',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'footer',
						'title'       => 'Footer Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'footer_layout',
								'title'     => esc_html__( 'Footer Layout', 'jumpstart' ),
								'default'   => 'widgets',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_footer_layouts()
							),	
							array(
								'id'        => 'footer_copyright',
								'title'     => esc_html__( 'Footer Copyright Text - Use *copy* for copyright symbol & *current_year* for the current year.', 'jumpstart' ),
								'default'   => '*copy* *current_year* Jumpstart. By <a href="http://www.tommusrhodus.com">TommusRhodus</a>',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),			
						)
					),
					array(
						'id'          => 'footer_social',
						'title'       => 'Footer Social Icons',
						'description' => 'Use this area to control the footer social icons.',
						'options' => array(
							array(
								'id'        => 'footer_social_icon_1',
								'title'     => esc_html__( 'Footer Social Icon 1', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_1',
								'title'     => esc_html__( 'Footer Social URL 1', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_2',
								'title'     => esc_html__( 'Footer Social Icon 2', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_2',
								'title'     => esc_html__( 'Footer Social URL 2', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_3',
								'title'     => esc_html__( 'Footer Social Icon 3', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_3',
								'title'     => esc_html__( 'Footer Social URL 3', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_4',
								'title'     => esc_html__( 'Footer Social Icon 4', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_4',
								'title'     => esc_html__( 'Footer Social URL 4', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_5',
								'title'     => esc_html__( 'Footer Social Icon 5', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_5',
								'title'     => esc_html__( 'Footer Social URL 5', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_6',
								'title'     => esc_html__( 'Footer Social Icon 6', 'jumpstart' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_6',
								'title'     => esc_html__( 'Footer Social URL 6', 'jumpstart' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
						)
					)
				)
			),
			array(
				'title'        => '404 Settings',
				'id'           => 'fourohfour_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'fourohfour_images',
						'title'       => esc_html__( '404 Page Image', 'jumpstart' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => '404_logo',
								'title'     => esc_html__( '404 Logo', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => '404_image',
								'title'     => esc_html__( '404 Image', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							)
						)
					)
				)
			),
			array(
				'title'        => 'WP Login Settings',
				'id'           => 'wp_login_settings1',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'wp_login_images',
						'title'       => esc_html__( 'Login Page Images', 'jumpstart' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'login_logo',
								'title'     => esc_html__( 'WP Login Logo', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'login_background_image',
								'title'     => esc_html__( 'WP Login Background', 'jumpstart' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							)
						)
					)
				)
			)
		);

		add_theme_support( 'tommusrhodus-framework', apply_filters( 'tommusrhodus_framework_theme_args', $framework_args ) );
		
	}
	add_action( 'after_setup_theme', 'tommusrhodus_initialise_framework', 5 );
}