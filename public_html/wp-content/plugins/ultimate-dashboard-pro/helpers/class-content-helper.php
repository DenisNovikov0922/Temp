<?php
/**
 * Content helper.
 *
 * @package Ultimate_Dashboard_Pro
 */

namespace UdbPro\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper as Free_Content_Helper;

/**
 * Class to setup content helper.
 */
class Content_Helper extends Free_Content_Helper {

	/**
	 * Check whether or not current post is built with Elementor.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return bool
	 */
	public function is_built_with_elementor( $post_id ) {
		return ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ? true : false );
	}

	/**
	 * Check whether or not current post is built with Beaver Builder.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return bool
	 */
	public function is_built_with_beaver( $post_id ) {
		return ( class_exists( '\FLBuilderModel' ) && \FLBuilderModel::is_builder_enabled( $post_id ) ? true : false );
	}

	/**
	 * Check whether or not current post is built with Brizy Builder.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return bool
	 */
	public function is_built_with_brizy( $post_id ) {
		return false;
	}

	/**
	 * Check whether or not current post is built with Divi Builder.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return bool
	 */
	public function is_built_with_divi( $post_id ) {
		return ( function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $post_id ) ? true : false );
	}

	/**
	 * Check whether or not current post is built with WordPress block editor.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return bool
	 */
	public function is_built_with_blocks( $post_id ) {
		if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
			return false;
		}

		if ( ! function_exists( 'has_blocks' ) || ! has_blocks( $post_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the editor/ builder of the given post.
	 *
	 * @param int $post_id ID of the post being checked.
	 * @return string The content editor name.
	 */
	public function get_content_editor( $post_id ) {

		if ( $this->is_built_with_elementor( $post_id ) ) {
			return 'elementor';
		} elseif ( $this->is_built_with_beaver( $post_id ) ) {
			return 'beaver';
		} elseif ( $this->is_built_with_brizy( $post_id ) ) {
			return 'brizy';
		} elseif ( $this->is_built_with_divi( $post_id ) ) {
			return 'divi';
		} elseif ( $this->is_built_with_blocks( $post_id ) ) {
			return 'block';
		}

		return 'default';

	}

	/**
	 * Get active page builders.
	 *
	 * @return array The list of builder names.
	 */
	public function get_active_page_builders() {

		$names = array();

		if ( defined( 'ELEMENTOR_VERSION' ) || defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			array_push( $names, 'elementor' );
		}

		if ( defined( 'ET_BUILDER_VERSION' ) ) {
			array_push( $names, 'divi' );
		}

		if ( defined( 'FL_BUILDER_VERSION' ) ) {
			array_push( $names, 'beaver' );
		}

		if ( defined( 'BRIZY_VERSION' ) ) {
			// array_push( $names, 'brizy' );
		}

		return $names;

	}

	/**
	 * Parse content with the specified builder in admin area.
	 *
	 * @param WP_Post|int $post Either the admin page's post object or post ID.
	 * @param string      $builder_name The content builder name.
	 */
	public function output_content_using_builder( $post, $builder_name ) {

		if ( is_int( $post ) ) {
			$post_id = $post;
			$post    = get_post( $post_id );
		} elseif ( is_object( $post ) && property_exists( $post, 'ID' ) ) {
			$post_id = $post->ID;
		} else {
			return;
		}

		if ( 'elementor' === $builder_name ) {

			$elementor = \Elementor\Plugin::$instance;

			$elementor->frontend->register_styles();
			$elementor->frontend->enqueue_styles();

			echo $elementor->frontend->get_builder_content( $post_id, true );

			$elementor->frontend->register_scripts();
			$elementor->frontend->enqueue_scripts();

		} elseif ( 'beaver' === $builder_name ) {

			echo do_shortcode( '[fl_builder_insert_layout id="' . $post_id . '"]' );

		} elseif ( 'divi' === $builder_name ) {

			$style_suffix = et_load_unminified_styles() ? '' : '.min';

			wp_enqueue_style( 'et-builder-modules-style', ET_BUILDER_URI . '/styles/frontend-builder-plugin-style' . $style_suffix . '.css', array(), ET_BUILDER_VERSION );

			$post_content = $post->post_content;
			$post_content = et_builder_get_layout_opening_wrapper() . $post_content . et_builder_get_layout_closing_wrapper();
			$post_content = et_builder_get_builder_content_opening_wrapper() . $post_content . et_builder_get_builder_content_closing_wrapper();

			echo apply_filters( 'the_content', $post_content );

		} elseif ( 'brizy' === $builder_name ) {

			$post_content = do_shortcode( $post->post_content );

			echo apply_filters( 'the_content', $post_content );

		} else {

			echo apply_filters( 'the_content', $post->post_content );

		}

	}

	/**
	 * Get saved templates for specified page builder.
	 *
	 * @param string $builder The page builder name.
	 * @return array The saved templates.
	 */
	public function get_page_builder_templates( $builder ) {

		$templates = array();

		if ( 'elementor' === $builder ) {
			$builder_posts = get_posts(
				array(
					'post_type'   => 'elementor_library',
					'post_status' => 'publish',
					'numberposts' => -1,
				)
			);

			foreach ( $builder_posts as $builder_post ) {
				array_push(
					$templates,
					array(
						'id'      => $builder_post->ID,
						'title'   => $builder_post->post_title,
						'builder' => 'elementor',
					)
				);
			}
		} elseif ( 'divi' === $builder ) {
			$builder_posts = get_posts(
				array(
					'post_type'   => 'et_pb_layout',
					'post_status' => 'publish',
					'numberposts' => -1,
				)
			);

			foreach ( $builder_posts as $builder_post ) {
				array_push(
					$templates,
					array(
						'id'      => $builder_post->ID,
						'title'   => $builder_post->post_title,
						'builder' => 'divi',
					)
				);
			}
		} elseif ( 'beaver' === $builder ) {
			if ( class_exists( '\FLBuilderModel' ) ) {
				$builder_posts = get_posts(
					array(
						'post_type'   => 'fl-builder-template',
						'post_status' => 'publish',
						'numberposts' => -1,
					)
				);

				foreach ( $builder_posts as $builder_post ) {
					array_push(
						$templates,
						array(
							'id'      => $builder_post->ID,
							'title'   => $builder_post->post_title,
							'builder' => 'beaver',
						)
					);
				}
			}
		}

		return $templates;

	}

}
