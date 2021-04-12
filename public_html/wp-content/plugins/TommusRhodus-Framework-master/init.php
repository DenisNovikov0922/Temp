<?php
/**
 * Plugin Name:  TommusRhodus Framework
 * Plugin URI:   https://www.tommusrhodus.com/framework/
 * Description:  A modern framework designed and built for themes by TommusRhodus. Adds Post Types, Shortcodes, Additional Functionality etc. on a per-theme basis.
 * Author:       Tom Rhodes
 * Author URI:   https://www.tommusrhodus.com
 * Contributors: Tom Rhodes
 *
 * Version:      1.0.2
 *
 * Text Domain:  trframework
 * Domain Path:  languages
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * https://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
 
if( !class_exists( 'TommusRhodus_Framework' ) ){
	class TommusRhodus_Framework {
	
		/**
		 * Instance of TommusRhodus_Framework
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object $instance The instance of TommusRhodus_Framework
		 * @blame Tom Rhodes
		 */
		private static $instance;
		
		/**
		 * Instance of TommusRhodus_Framework
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object $instance The instance of TommusRhodus_Framework
		 * @blame Tom Rhodes
		 */
		public $theme_support;
		
		/**
		 * Instance of TommusRhodus_Framework
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object $instance The instance of TommusRhodus_Framework
		 * @blame Tom Rhodes
		 */
		public $path;
		
		/**
		 * Instance.
		 *
		 * An global instance of the class. Used to retrieve the instance
		 * to use on other files/plugins/themes.
		 *
		 * @since 1.0.0
		 * @return object Instance of the class.
		 * @blame Tom Rhodes
		 */
		public static function instance() {
	
			if ( is_null( self::$instance ) ){
				self::$instance = new self();
			}
	
			return self::$instance;
	
		}
		
		/**
		 * __clone()
		 * 
		 * Cloning is forbidden.
		 * 
		 * @documentation Class structure taken from WooCommerce main Class.
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function __clone(){
			// Do nothing
		}
	
		/**
		 * __wakeup()
		 *  
		 * Unserialising instances of this class is forbidden.
		 * 
		 * @documentation Class structure taken from WooCommerce main Class.
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function __wakeup(){
			// Do nothing
		}
			
		/**
		 * __construct()
		 * 
		 * Standard class construction function, from here we'll setup all the actions that the framework
		 * needs to register. Each action will generally setup a theme feature, CPTs, Shortodes etc.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function __construct(){
			
			// First, grab the theme support for tommusrhodus-framework for us to process afterward
			add_action( 'after_setup_theme', array( $this, 'gather_theme_support' ), 10 );
			
			// Next, let's process the custom post types
			add_action( 'after_setup_theme', array( $this, 'process_gutenberg_blocks' ), 15 );
			
			// Process WPBakery Blocks
			add_action( 'after_setup_theme', array( $this, 'process_wpb_blocks' ), 25 );
			
			// Process Elementor Blocks
			add_action( 'init', array( $this, 'process_elementor_functions' ), 25 );
			
			// Next, let's process the custom post types
			add_action( 'init', array( $this, 'process_custom_post_types' ), 15 );
			
			// Second, process custom widgets
			add_action( 'widgets_init', array( $this, 'process_widgets' ), 10 );
			
			// Third, process the custom taxonomies
			add_action( 'init', array( $this, 'process_custom_post_taxonomies' ), 20 );

			// Process Theme Options
			add_action( 'customize_register', array( $this, 'process_options' ), 15 );

		}
		
		/**
		 * process_options()
		 * 
		 * Takes the options array from theme support and loops over to separate all options into
		 * panels, sections, and options. Then registers all and creates usable options
		 * within the WP Customiser
		 *  
		 * @documentation https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 * @documentation https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
		 * @documentation https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 * @documentation https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_panel
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_options( $wp_customize ){
			
			// Add Custom Toggle Control
			include( $this->path . 'customizer-controls/toggle-control.php' );
			
			// Register Toggle Control
			$wp_customize->register_control_type( 'Tommus_Rhodus_Toggle_Control' );
			
			$panel_priority   = 300;
			$section_priority = 100;
			$option_priority  = 100;
			
			if( isset( $this->theme_support['theme_options'] ) && is_array( $this->theme_support['theme_options'] ) ){
				
				// Loop over each options group
				foreach( $this->theme_support['theme_options'] as $panel ){
					
					// Add each panel that's found
					$wp_customize->add_panel( 
						$panel['id'], array(
							'title'          => $panel['title'],
							'description'    => $panel['description'],
							'priority'       => $panel_priority++
						) 
					);
					
					// Loop over each section that's found
					if( is_array( $panel['sections'] ) ){
						foreach( $panel['sections'] as $section ){
						
							$wp_customize->add_section( 
								$section['id'], array(
									'title'          => $section['title'],
									'description'    => $section['description'],
									'priority'       => $section_priority++,
									'panel'          => $panel['id']
								) 
							);
							
							// Loop over each option that's found
							if( is_array( $section['options'] ) ){
								foreach( $section['options'] as $option ){
									
									$wp_customize->add_setting(
										$option['id'], 
										array(
											'default'   => $option['default'],
											'transport' => $option['transport'],
											'type'      => ( isset( $option['option_type'] ) ) ? $option['option_type'] : 'theme_mod'
										)
									);
									
									if( 'image' == $option['type'] ){
										
										$wp_customize->add_control( 
											new WP_Customize_Image_Control(
												$wp_customize, 
												$option['id'], 
												array(
										    		'label'    => $option['title'],
										    		'section'  => $section['id'],
										    		'priority' => $option_priority++
												)
											)
										);
										   
									} elseif( 'color' == $option['type'] ){
										
										$wp_customize->add_control( 
											new WP_Customize_Color_Control(
												$wp_customize, 
												$option['id'], 
												array(
										    		'label'    => $option['title'],
										    		'section'  => $section['id'],
										    		'priority' => $option_priority++
												)
											)
										);
										   
									} elseif( 'toggle' == $option['type'] ){
										
										$wp_customize->add_control( 
											new Tommus_Rhodus_Toggle_Control( 
												$wp_customize, 
												$option['id'], 
												array(
													'label'    => $option['title'],
													'section'  => $section['id'],
													'type'     => 'toggle',
													'priority' => $option_priority++
												) 
											) 
										);
										  
									} else {
										
										$choices = ( isset( $option['choices'] ) ) ? $option['choices'] : false;
										
										$wp_customize->add_control( 
											$option['id'], 
											array(
											    'type'     => $option['type'],
											    'label'    => $option['title'],
											    'section'  => $section['id'],
											    'priority' => $option_priority++,
											    'choices'  => $choices
											) 
										);
									
									}
									
								}
							}
							
						}
					}

					
				} // End Foreach
				
			} // End If
			
		}
		
		/**
		 * gather_theme_support()
		 * 
		 * Basic function, simply loads the theme support as registered in our theme.
		 * This is hooked to the init event which loads after the after_theme_setup event where theme support is registered.
		 * 
		 * @documentation https://developer.wordpress.org/reference/functions/add_theme_support/
		 * @documentation https://developer.wordpress.org/reference/functions/get_theme_support/
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function gather_theme_support(){
		
			$tr_framework_support = get_theme_support( 'tommusrhodus-framework' );
			
			if( is_array( $tr_framework_support ) ){
				$this->path          = plugin_dir_path( __FILE__ );
				$this->theme_support = $tr_framework_support[0];
			}
			
		}
		
		/**
		 * process_custom_post_types()
		 * 
		 * Loops through our post_types array item (checks if it exists first) and then
		 * sends off any found to register_custom_post_type() to turn that into an actual post type registration.
		 * 
		 * @since v1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_custom_post_types(){
			
			// Check that we're actually registering post types in this theme, and that it's an array.
			if( isset( $this->theme_support['post_types'] ) && is_array( $this->theme_support['post_types'] ) ){
				
				// Loop through all post types and send data off to register_custom_post_type()
				foreach( $this->theme_support['post_types'] as $post_type => $args ){
					$this->register_custom_post_type( $post_type, $args );
				}
				
			}
			
		}
		
		/**
		 * process_custom_post_taxonomies()
		 * 
		 * Loops through our post_types taxonomies array item (checks if it exists first) and then
		 * sends off any found to register_custom_post_taxonomy() to turn that into an actual taxonomy registration.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_custom_post_taxonomies(){
			
			// Check that we're actually registering post types in this theme, and that it's an array.
			if( isset( $this->theme_support['taxonomy_types'] ) && is_array( $this->theme_support['taxonomy_types'] ) ){
				
				// Loop through all post types and send data off to register_custom_post_type()
				foreach( $this->theme_support['taxonomy_types'] as $taxonomy_type => $args ){
					$this->register_custom_post_taxonomy( $taxonomy_type, $args );
				}
				
			}
			
		}
		
		/**
		 * register_custom_post_type()
		 * 
		 * Accepts the arguments from our processed custom post types array, turns those arguments
		 * into living, breathing post types that can be modified directly from the main theme.
		 * 
		 * @documentation https://codex.wordpress.org/Function_Reference/register_post_type
		 * @documentation https://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function register_custom_post_type( $type = false, $args = false ){
			
			// Allow this CPT to use post formats (if needed) 
			register_taxonomy_for_object_type( 'post_format', $type );
			
			// Register the post type, modify args using filter tommusrhodus_framework_cpt_{post-type}_args
		    register_post_type( $type, apply_filters( 'tommusrhodus_framework_cpt_'. $type .'_args', $args ) ); 
			    
		}
		
		/**
		 * register_custom_post_taxonomy()
		 * 
		 * Accepts the arguments from our processed custom taxonomy types array, turns those arguments
		 * into living, breathing post taxonomies that can be modified directly from the main theme.
		 * 
		 * @documentation https://developer.wordpress.org/reference/functions/register_taxonomy/
		 * @param STRING - $type - taxonomy name string
		 * @param ARGS - $array - Array of arguments for registering the taxonomy
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function register_custom_post_taxonomy( $type = false, $args = false ){
			
			// Modify args using filter tommusrhodus_framework_taxonomy_{taxonomy-type}_args
			$filtered_args = apply_filters( 'tommusrhodus_framework_taxonomy_'. $type .'_args', $args );
			
			// Register the taxonomy
			register_taxonomy( $type, $filtered_args['for_post_types'], $filtered_args );
			    
		}
		
		/**
		 * process_gutenberg_blocks()
		 * 
		 * Loops through our registered gutenberg blocks from theme support, functionality of each block
		 * is contained within the included file.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_gutenberg_blocks(){
		
			// Check that this theme actually has gutenberg blocks, and then ensure we have a theme name set
			if( isset( $this->theme_support['gutenberg_blocks'] ) && isset( $this->theme_support['gutenberg_blocks']['theme_name'] ) ){
				
				// Grab blocks and loop over them
				if( is_array( $this->theme_support['gutenberg_blocks']['blocks'] ) ){
					foreach( $this->theme_support['gutenberg_blocks']['blocks'] as $block ){
						include( $this->path . 'gutenberg-blocks/'. trailingslashit( $this->theme_support['gutenberg_blocks']['theme_name'] ) . $block .'.php' );
					}
				}
				
			}
			
		}
		
		/**
		 * process_wpb_blocks()
		 * 
		 * Loops through our registered wpb blocks from theme support, functionality of each block
		 * is contained within the included file.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_wpb_blocks(){

			// Check that this theme actually has wpb blocks, and then ensure we have a theme name set
			if( isset( $this->theme_support['wpb_blocks'] ) && isset( $this->theme_support['wpb_blocks']['theme_name'] ) ){
				
				// Grab blocks and loop over them
				if( is_array( $this->theme_support['wpb_blocks']['blocks'] ) ){
				
					include( $this->path . 'wpb-blocks/'. trailingslashit( $this->theme_support['wpb_blocks']['theme_name'] ) . 'functions.php' );
					
					foreach( $this->theme_support['wpb_blocks']['blocks'] as $block ){
						include( $this->path . 'wpb-blocks/'. trailingslashit( $this->theme_support['wpb_blocks']['theme_name'] ) . $block .'.php' );
					}
					
				}
				
			}
			
		}
		
		public function process_elementor_functions(){
			
			// Check that this theme actually has elementor blocks, and then ensure we have a theme name set
			if( isset( $this->theme_support['elementor_blocks'] ) && isset( $this->theme_support['elementor_blocks']['theme_name'] ) ){
				
				// Check we have blocks registered
				if( is_array( $this->theme_support['elementor_blocks']['blocks'] ) ){
					
					// Include our elementor functions on the init hook
					include( $this->path . 'elementor-blocks/'. trailingslashit( $this->theme_support['elementor_blocks']['theme_name'] ) . 'functions.php' );
					
					// Register our widgets to the correct hook
					add_action( 'elementor/widgets/widgets_registered', array( $this, 'process_elementor_blocks' ), 25 );
					
				}
				
			}

		}
		
		
		/**
		 * process_elementor_blocks()
		 * 
		 * Loops through our registered elementor blocks from theme support, functionality of each block
		 * is contained within the included file.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_elementor_blocks(){

			// Check that this theme actually has elementor blocks, and then ensure we have a theme name set
			if( isset( $this->theme_support['elementor_blocks'] ) && isset( $this->theme_support['elementor_blocks']['theme_name'] ) ){
				
				// Grab blocks and loop over them
				if( is_array( $this->theme_support['elementor_blocks']['blocks'] ) ){
					foreach( $this->theme_support['elementor_blocks']['blocks'] as $block ){
						include( $this->path . 'elementor-blocks/'. trailingslashit( $this->theme_support['elementor_blocks']['theme_name'] ) . $block .'.php' );
					}
				}
				
			}
			
		}
		
		/**
		 * process_widgets()
		 * 
		 * Loops through our registered wpb blocks from theme support, functionality of each block
		 * is contained within the included file.
		 * 
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 */
		public function process_widgets(){

			// Check that this theme actually has wpb blocks, and then ensure we have a theme name set
			if( isset( $this->theme_support['widgets'] ) && isset( $this->theme_support['widgets']['theme_name'] ) ){
				
				// Grab blocks and loop over them
				if( is_array( $this->theme_support['widgets']['widgets'] ) ){
					foreach( $this->theme_support['widgets']['widgets'] as $block ){
						include( $this->path . 'widgets/'. trailingslashit( $this->theme_support['widgets']['theme_name'] ) . $block .'.php' );
					}
				}
				
			}
			
		}
		
		/**
		 * the_terms()
		 * 
		 * A simple to use function that takes a taxonomy and post id, and returns a formatted list
		 * of assigned terms. Has before and after markup functions
		 * 
		 * @param INT - $id - Post ID to check against
		 * @param STRING - $taxonomy - Taxonomy to check against
		 * @param STRING - $display - The display type to return, slug, name or link
		 * @param STRING - $separator - The string to add between items
		 * @since 1.0.0
		 * @blame Tom Rhodes
		 * @todo Tidy this up
		 */
		public function the_terms( $id = false, $taxonomy = false, $display = 'name', $separator = '', $before = false, $after = false, $class = false ){
			
			// Exit if we've not provided correct input
			if( !$id || !$taxonomy ){
				return false;
			}
			
			// Gather terms for this post
			$terms = get_the_terms( $id, $taxonomy );
			
			// If terms are empty, just return false
			if(!( is_array( $terms ) )){
				return false;
			}
			
			$output = $before;
			
			foreach( $terms as $term ){
			
				if( 'slug' == $display ){
				
					$output .= $term->slug;
					
				} elseif( 'name' == $display ){
				
					$output .= $term->name;
					
				} elseif( 'link' == $display ){
					
					$class_attr = ( $class ) ? 'class="'. $class .'"' : false;
					
					$output .= '<a href="'. get_term_link( $term ) .'" data-term-slug="'. $term->slug .'" '. $class_attr .'>'. $term->name .'</a>';
					
				}
				
				$output .= $separator;
			
			}
			
			$length = strlen( $separator );
			
			return substr( $output, 0, -$length ) . $after;
			
		}
		
	}
	add_action( 'plugins_loaded', 'TommusRhodus_Framework', 10 );
}

if( !function_exists( 'TommusRhodus_Framework' ) ){
 	function TommusRhodus_Framework() {
		return TommusRhodus_Framework::instance();
	}
}

/* Disable Elementor's getting started redirect as its interupting merlin setup */
add_action( 'admin_init', function() {
	if ( did_action( 'elementor/loaded' ) ) {
		remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
	}
}, 1 );

/* Add post type options automatically */
function tr_framework_post_type_options( $args ){
	
	/**
	 * Setup theme customiser options
	 */
	if( is_array( $args['post_types'] ) ) :
		
		$sections = array();
		
		foreach( $args['post_types'] as $key => $array ) :
			
			$options = array();
			
			if( $array['has_archive'] ){
			
				$options[] = array(
					'id'          => $key . '_post_type_slug',
					'title'       => ucfirst( $key ) . ' Archive URL Slug',
					'default'     => get_option( $key . '_post_type_slug', $key ),
					'type'        => 'text',
					'transport'   => 'postMessage',
					'choices'     => '',
					'option_type' => 'option'
				);
				
			}
			
			$options[] = array(
				'id'          => $key . '_post_type_active',
				'title'       => ucfirst( $key ) . ' Post Type Active?',
				'default'     => 'yes',
				'type'        => 'select',
				'transport'   => 'postMessage',
				'choices'     => array(
					'yes' => 'Yes',
					'no'  => 'No'
				),
				'option_type' => 'option'
			);
			
			$sections[] = array(
				'id'          => $key . '_post_type_settings',
				'title'       => ucfirst( $key ) . ' Settings',
				'description' => 'When changing these settings, you will need to go to "settings => permalinks" and hit the "Save Changes" button if you receive any unexpected 404 errors.',
				'options'     => $options
			);
			
		endforeach;
		
		$args['theme_options'][] = array(
			'title'        => 'Post Type Settings',
			'id'           => 'post_type_settings',
			'description'  => '',
			'sections'     => $sections
		);
		
	endif;
	
	/**
	 * Enable / Disable post types
	 */
	if( is_array( $args['post_types'] ) ){
		foreach( $args['post_types'] as $key => $array ){
			if( 'no' == get_option( $key . '_post_type_active', 'yes' ) ){
				unset( $args['post_types'][$key] );
			}
		}
	}
	
	return $args;
	
}
add_filter( 'tommusrhodus_framework_theme_args', 'tr_framework_post_type_options', 10, 1 );
