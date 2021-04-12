<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * @since 3.0
 */
class FrmProFieldFile extends FrmFieldType {

	/**
	 * @var string
	 * @since 3.0
	 */
	protected $type = 'file';

	private $entry_id;

	protected function include_form_builder_file() {
		return FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/back-end/field-' . $this->type . '.php';
	}

	protected function field_settings_for_type() {
		$settings = array(
			'invalid'       => true,
			'read_only'     => true,
		);

		FrmProFieldsHelper::fill_default_field_display( $settings );
		return $settings;
	}

	protected function extra_field_opts() {
		return array(
			'ftypes' => array(),
			'attach' => false,
			'delete' => false,
			'restrict' => 0,
			'resize'     => false,
			'new_size'   => '600',
			'resize_dir' => 'width',
			'drop_msg'   => __( 'Drop a file here or click to upload', 'formidable-pro' ),
			'choose_msg' => __( 'Choose File', 'formidable-pro' ),
		);
	}

	/**
	 * @since 4.0
	 */
	public function show_primary_options( $args ) {
		$field = $args['field'];
		$mimes = $this->get_mime_options( $field );
		include( FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/back-end/file-options.php' );

		parent::show_primary_options( $args );
	}

	/**
	 * @since 3.06.01
	 */
	public function translatable_strings() {
		$strings   = parent::translatable_strings();
		$strings[] = 'drop_msg';
		$strings[] = 'choose_msg';
		return $strings;
	}

	/**
	 * @since 3.01.01
	 */
	private function get_mime_options( $field ) {
		$mimes = get_allowed_mime_types();
		$selected_mimes = $field['ftypes'];

		$ordered = array();
		foreach ( (array) $selected_mimes as $mime ) {
			$key = array_search( $mime, $mimes );
			if ( $key !== false ) {
				$ordered[ $key ] = $mimes[ $key ];
				unset( $mimes[ $key ] );
			}
		}

		$mimes = $ordered + $mimes;
		return $mimes;
	}

	public function validate( $args ) {
		return FrmProFileField::no_js_validate( array(), $this->field, $args['value'], $args );
	}

	/**
	 * Upload new files, delete removed files
	 *
	 * @since 3.0
	 * @param array|string $value (the posted value)
	 * @param array $atts
	 *
	 * @return array|string $value
	 */
	public function get_value_to_save( $value, $atts ) {
		// Upload files and get new meta value for file upload fields
		$value = FrmProFileField::prepare_file_upload_meta( $value, $this->field, $atts['entry_id'] );

		if ( is_array( $value ) ) {
			$value = array_map( 'intval', array_filter( $value ) );
		}

		return $value;
	}

	protected function prepare_display_value( $value, $atts ) {
		if ( ! is_numeric( $value ) && ! is_array( $value ) ) {
			return $value;
		}

		$showing_image = ( isset( $atts['html'] ) && $atts['html'] ) || ( isset( $atts['show_image'] ) && $atts['show_image'] );
		$default_sep = $showing_image ? ' ' : ', ';
		$atts['sep'] = isset( $atts['sep'] ) ? $atts['sep'] : $default_sep;

		$this->get_file_html_from_atts( $atts, $value );

		$return_array = isset( $atts['return_array'] ) && $atts['return_array'];
		if ( is_array( $value ) && ! $return_array ) {
			$value = implode( $atts['sep'], $value );

			$show_text_only = isset( $atts['show'] ) && 'id' === $atts['show'];
			if ( $showing_image && ! $show_text_only ) {
				$value = '<div class="frm_file_container">' . $value . '</div>';
			}
		}

		return $value;
	}

	protected function fill_default_atts( &$atts ) {
		// don't add default separator
	}

	/**
	 * Get the HTML for a file upload field depending on the $atts
	 *
	 * @since 3.0
	 *
	 * @param array $atts
	 * @param string|array|int $replace_with
	 */
	private function get_file_html_from_atts( $atts, &$replace_with ) {
		$show_id = isset( $atts['show'] ) && $atts['show'] == 'id';
		if ( ! $show_id && ! empty( $replace_with ) ) {
			//size options are thumbnail, medium, large, or full
			$size = $this->set_size( $atts );

			$new_atts = $this->set_file_atts( $atts );

			$this->modify_atts_for_reverse_compatibility( $atts, $new_atts );

			$ids = (array) $replace_with;
			$replace_with = $this->get_displayed_file_html( $ids, $size, $new_atts );
		}

		if ( is_array( $replace_with ) ) {
			$replace_with = array_filter( $replace_with );
		}
	}

	public function set_file_atts( $atts ) {
		$new_atts = array(
			'show_filename' => ( isset( $atts['show_filename'] ) && $atts['show_filename'] ),
			'show_image'    => ( isset( $atts['show_image'] ) && $atts['show_image'] ),
			'add_link'      => ( isset( $atts['add_link'] ) && $atts['add_link'] ),
			'new_tab'       => ( isset( $atts['new_tab'] ) && $atts['new_tab'] ),
		);
		return array_merge( $atts, $new_atts );
	}

	/**
	 * Check the 'size' first, and fallback to 'show' for reverse compatibility
	 * Set the default size for showing images
	 *
	 * @since 3.0
	 */
	public function set_size( $atts ) {
		if ( isset( $atts['size'] ) ) {
			$size = $atts['size'];
		} elseif ( isset( $atts['show'] ) ) {
			$size = $atts['show'];
		} elseif ( isset( $atts['source'] ) && $atts['source'] == 'entry_formatter' ) {
			$size = 'full';
		} else {
			$size = 'thumbnail';
		}
		return $size;
	}

	/**
	 * Maintain reverse compatibility for html=1, links=1, and show=label
	 *
	 * @since 3.0
	 *
	 * @param array $atts
	 * @param array $new_atts
	 */
	private function modify_atts_for_reverse_compatibility( $atts, &$new_atts ) {
		// For show=label
		if ( ! $new_atts['show_filename'] && isset( $atts['show'] ) && $atts['show'] == 'label' ) {
			$new_atts['show_filename'] = true;
		}

		// For html=1
		$inc_html = ( isset( $atts['html'] ) && $atts['html'] );
		if ( $inc_html && ! $new_atts['show_image'] ) {

			if ( $new_atts['show_filename'] ) {
				// For show_filename with html=1
				$new_atts['show_image'] = false;
				$new_atts['add_link'] = true;
			} else {
				// html=1 without show_filename=1
				$new_atts['show_image'] = true;
				$new_atts['add_link_for_non_image'] = true;
			}
		}

		// For links=1
		$show_links = ( isset( $atts['links'] ) && $atts['links'] );
		if ( $show_links && ! $new_atts['add_link'] ) {
			$new_atts['add_link'] = true;
		}
	}

	/**
	 * Get HTML for a file upload field depending on atts and file type
	 *
	 * @since 3.0
	 *
	 * @param array $ids
	 * @param string $size
	 * @param array $atts
	 * @return array|string
	 */
	public function get_displayed_file_html( $ids, $size = 'thumbnail', $atts = array() ) {
		$defaults = array(
			'class'         => '',
			'show_filename' => false,
			'show_image' => false,
			'add_link' => false,
			'add_link_for_non_image' => false,
		);
		$atts = wp_parse_args( $atts, $defaults );
		$atts['size'] = $size;

		$img_html = array();
		foreach ( (array) $ids as $id ) {
			if ( ! is_numeric( $id ) ) {
				if ( ! empty( $id ) ) {
					// If a custom value was set with a hook, don't remove it
					$img_html[] = $id;
				}
				continue;
			}

			$img = $this->get_file_display( $id, $atts );

			if ( isset( $img ) ) {
				$img_html[] = $img;
			}
		}
		unset( $img, $id );

		if ( count( $img_html ) == 1 ) {
			$img_html = reset( $img_html );
		}

		return $img_html;
	}

	/**
	 * Get the HTML to display an upload in a File Upload field
	 *
	 * @since 3.0
	 *
	 * @param int $id
	 * @param array $atts
	 * @return string $html
	 */
	public function get_file_display( $id, $atts ) {
		if ( ! $id || ! $this->file_exists_by_id( $id ) ) {
			return '';
		}

		$is_image = wp_attachment_is_image( $id );
		$url      = FrmProFileField::get_file_url( $id, $is_image ? $atts['size'] : false );

		if ( ! FrmProFileField::user_has_permission( $id ) ) {
			$frm_settings = FrmAppHelper::get_settings();
			$html         = $frm_settings->admin_permission;
		} else {
			$html = $atts['show_image'] ? wp_get_attachment_image( $id, $atts['size'], ! $is_image ) : '';

			// If show_filename=1 is included
			if ( $atts['show_filename'] ) {
				$label = $this->get_single_file_name( $id );
				if ( $atts['show_image'] ) {
					$html .= ' <span id="frm_media_' . absint( $id ) . '" class="frm_upload_label">' . $label . '</span>';
				} else {
					$html .= $label;
				}
			}

			// If neither show_image or show_filename are included, get file URL
			if ( ! $html ) {
				$html = $url;
			}

			// If add_link=1 is included
			if ( $atts['add_link'] || ( ! $is_image && $atts['add_link_for_non_image'] ) ) {
				$href   = $is_image ? FrmProFileField::get_file_url( $id ) : $url;
				$target = ! empty( $atts['new_tab'] ) ? ' target="_blank"' : '';
				$html   = '<a href="' . esc_url( $href ) . '" class="frm_file_link"' . $target . '>' . $html . '</a>';
			}

			if ( ! empty( $atts['class'] ) ) {
				$html = str_replace( ' class="', ' class="' . esc_attr( $atts['class'] . ' ' ), $html );
			}
		}

		$atts['media_id'] = $id;
		return apply_filters( 'frm_image_html_array', $html, $atts );
	}

	/**
	 * Check if a file exists on the site
	 *
	 * @since 3.0
	 * @param $id
	 * @return bool true if a post exists with the specified id that has the 'attachment' post_type
	 */
	private function file_exists_by_id( $id ) {
		global $wpdb;
		return 'attachment' === FrmDb::get_var( $wpdb->posts, array( 'ID' => $id ), 'post_type' );
	}

	/**
	 * Get the file name for a single media ID
	 *
	 * @since 3.0
	 *
	 * @param int $id
	 * @return boolean|string $filename
	 */
	private function get_single_file_name( $id ) {
		$attachment = get_post( $id );
		if ( ! $attachment ) {
			$filename = false;
		} else {
			$filename = basename( $attachment->guid );
		}
		return $filename;
	}

	public function front_field_input( $args, $shortcode_atts ) {
		$field = $this->field;
		$html_id = $args['html_id'];
		$field_name = $args['field_name'];

		$file_name = str_replace( 'item_meta[' . $field['id'] . ']', 'file' . $field['id'], $field_name );
		if ( $file_name == $field_name ) {
			// this is a repeating field
			$repeat_meta = explode( '-', $html_id );
			$repeat_meta = end( $repeat_meta );
			$file_name = 'file' . $field['id'] . '-' . $repeat_meta;
			unset( $repeat_meta );
		}

		$aria = '';
		$this->add_aria_description( $args, $aria );

		ob_start();
		include( FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/front-end/file.php' );
		$input_html = ob_get_contents();
		ob_end_clean();

		return $input_html;
	}

	/**
	 * Add extra classes on front-end input
	 *
	 * @since 3.01.04
	 */
	protected function get_input_class() {
		$class = '';
		if ( FrmField::is_option_true( $this->field, 'multiple' ) ) {
			$class = 'frm_multiple_file';
		}

		// Hide the "No files selected" text if files are selected
		if ( ! FrmField::is_option_empty( $this->field, 'value' ) ) {
			$class .= ' frm_transparent';
		}

		return $class;
	}

	protected function prepare_import_value( $value, $atts ) {
		$value = $this->get_file_id( $value );
		// If single file upload field, reset array
		if ( ! FrmField::is_option_true( $this->field, 'multiple' ) ) {
			$value = reset( $value );
		}
		return $value;
	}

	public function get_file_id( $value ) {
		global $wpdb;

		if ( ! is_array($value ) ) {
			$value = explode(',', $value);
		}

		foreach ( (array) $value as $pos => $m ) {
			$m = trim( $m );
			if ( empty( $m ) ) {
				continue;
			}

			if ( ! is_numeric( $m ) ) {
				//get the ID from the URL if on this site
				$m = FrmDb::get_var( $wpdb->posts, array( 'guid' => $m ), 'ID' );
			}

			if ( ! is_numeric( $m ) ) {
				unset( $value[ $pos ] );
			} else {
				$value[ $pos ] = $m;
			}

			unset( $pos, $m );
		}

		return $value;
	}

	/**
	 * After sanitizing our data, ensure it comes back in the same format it went in as
	 *
	 * @param mixed $value
	 * @param array $new_value
	 * @param bool $was_array
	 */
	private function adjust_value( &$value, $new_value, $was_array ) {
		$value = $new_value;
		if ( ! $was_array ) {
			if ( $value ) {
				$value = reset( $value );
			} else {
				$value = '';
			}
		}
	}

	/**
	 * Filter out attachments to only include temporary formidable files
	 *
	 * @since 4.0.04
	 * @param mixed $value
	 */
	public function sanitize_value( &$value ) {
		$form              = FrmForm::getOne( $this->field->form_id );
		$form_is_protected = FrmProFileField::get_option( $form->parent_form_id ? $form->parent_form_id : $form->id, 'protect_files', 0 );

		if ( ! $form_is_protected ) {
			return;
		}

		$stop_sanitizing = apply_filters( 'frm_stop_file_switching', false, array( 'form_id' => $this->field->form_id ) );

		if ( $stop_sanitizing ) {
			return;
		}

		$this->entry_id  = FrmAppHelper::get_param( 'id', '', 'post', 'absint' );
		$entry_id        = $this->entry_id;
		$was_array       = is_array( $value );
		$assigned_ids    = array();
		$unsafe_file_ids = array_filter( array_map( 'absint', (array) $value ) );

		if ( ! $unsafe_file_ids ) {
			$this->adjust_value( $value, array(), $was_array );
			return;
		}

		if ( $entry_id ) {
			$assigned_ids              = self::get_assigned_file_ids( $unsafe_file_ids );
			$all_ids_have_been_matched = count( $assigned_ids ) === count( $unsafe_file_ids );
			if ( $all_ids_have_been_matched ) {
				$this->adjust_value( $value, $assigned_ids, $was_array );
				return;
			}
		}

		$default_ids   = $this->get_default_file_ids( $unsafe_file_ids );
		$temporary_ids = $this->get_temporary_ids( $unsafe_file_ids );

		$this->adjust_value( $value, array_merge( $assigned_ids, $default_ids, $temporary_ids ), $was_array );
	}

	/**
	 * Given a set of file ids, check field default_valies for ids that actually match this field
	 *
	 * @param array $unsafe_file_ids
	 * @return array
	 */
	private function get_default_file_ids( $unsafe_file_ids ) {
		$default_value = maybe_unserialize( $this->field->default_value );
		if ( is_string( $default_value ) ) {
			$default_value = do_shortcode( $default_value );
		}
		$default_ids = array_filter( array_map( 'absint', (array) $default_value ) );
		if ( $default_ids ) {
			$default_ids = array_intersect( $default_ids, $unsafe_file_ids );
		}
		return $default_ids;
	}

	/**
	 * Given a set of file ids, check frm_item_metas for ids that actually match this field
	 *
	 * @param array $unsafe_file_ids
	 * @return array
	 */
	private function get_assigned_file_ids( $unsafe_file_ids ) {
		$assigned_ids = array();
		$conditions   = array( 'or' => 1 );
		foreach ( $unsafe_file_ids as $file_id ) {
			$conditions[] = array(
				'or'              => 1,
				'meta_value'      => $file_id,
				'meta_value LIKE' => array( 'i:' . $file_id . ';', ':"' . $file_id . '"' ),
			);
		}

		$item_ids   = FrmDb::get_col( 'frm_items', array( 'parent_item_id' => $this->entry_id ) );
		$item_ids[] = $this->entry_id;

		$where = array(
			'field_id' => $this->field->id,
			'item_id'  => $item_ids,
			$conditions
		);
		$metas = FrmDb::get_col( 'frm_item_metas', $where, 'meta_value' );

		foreach ( $metas as $meta ) {
			FrmProAppHelper::unserialize_or_decode( $meta );
			$meta_file_ids = array_map( 'intval', (array) $meta );
			$assigned_ids  = array_merge( array_intersect( $meta_file_ids, $unsafe_file_ids ), $assigned_ids );
		}

		return $assigned_ids;
	}

	/**
	 * Given a set of file ids, check wp_postmeta for ids that actually match this field
	 *
	 * @param array $unsafe_file_ids
	 * @return array
	 */
	private function get_temporary_ids( $unsafe_file_ids ) {
		global $wpdb;

		// only include posts with a user id match
		// if a user is not logged in, user id is 0 (and will only match with guest files)
		$where         = array(
			'ID'          => $unsafe_file_ids,
			'post_author' => get_current_user_id()
		);
		$temporary_ids = FrmDb::get_col( $wpdb->posts, $where, 'ID' );

		if ( ! $temporary_ids ) {
			return array();
		}

		return FrmDb::get_col(
			$wpdb->postmeta,
			array(
				'post_id'    => $temporary_ids,
				'meta_key'   => '_frm_temporary',
				'meta_value' => $this->field->id,
			),
			'post_id'
		);
	}
}
