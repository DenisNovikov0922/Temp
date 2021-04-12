<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * @since 3.0
 */
class FrmProFieldStar extends FrmFieldType {

	/**
	 * @var string
	 * @since 3.0
	 */
	protected $type = 'star';

	protected function input_html() {
		return $this->multiple_input_html();
	}

	protected function include_form_builder_file() {
		return FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/front-end/star.php';
	}

	protected function field_settings_for_type() {
		$settings = array(
			'unique'        => true,
		);

		FrmProFieldsHelper::fill_default_field_display( $settings );
		return $settings;
	}

	protected function extra_field_opts() {
		return array(
			'minnum' => 1,
			'maxnum' => 5,
		);
	}

	protected function new_field_settings() {
		return array(
			'options' => range( 1, 5 ),
		);
	}

	/**
	 * @since 4.0
	 * @param array $args - Includes 'field', 'display', and 'values'
	 */
	public function show_primary_options( $args ) {
		$field = $args['field'];
		include( FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/back-end/star-options.php' );

		parent::show_primary_options( $args );
	}

	public function get_container_class() {
		// Add class to inline Scale field
		$class = '';
		if ( $this->field['label'] == 'inline' ) {
			$class = ' frm_scale_container';
		}
		return $class;
	}

	protected function include_front_form_file() {
		return FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/front-end/star.php';
	}

	protected function prepare_display_value( $value, $atts ) {
		if ( ! isset( $atts['html'] ) || ! $atts['html'] ) {
			return $value;
		}

		FrmStylesController::enqueue_style();

		$options = $this->get_field_column('options');
		if ( is_array( $options ) ) {
			$max = max( $options );
		} else {
			$max = 5;
		}

		$numbers = $this->get_rounded_decimal( $value );

		ob_start();
		include( FrmProAppHelper::plugin_path() . '/classes/views/frmpro-fields/star_disabled.php' );
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	private function get_rounded_decimal( $value ) {
		$numbers = array(
			'decimal' => 0,
			'digit'   => $value,
			'value'   => $value,
		);

		if ( $value != floor( $value ) ) {
			$value = round( $value, 2 );
			list( $numbers['digit'], $numbers['decimal'] ) = explode( '.', $value );

			if ( strlen( $numbers['decimal'] ) == 1 ) {
				// make sure there are two digits after the decimal
				$numbers['decimal'] = $numbers['decimal'] * 10;
			}

			if ( $numbers['decimal'] < 25 ) {
				$numbers['decimal'] = 0;
			} else if ( $numbers['decimal'] < 75 ) {
				$numbers['decimal'] = 5;
			} else {
				$numbers['decimal'] = 0;
				$numbers['digit'] ++;
			}

			$numbers['value'] = (float) ( $numbers['digit'] . '.' . $numbers['decimal'] );
		}

		return $numbers;
	}

	/**
	 * @since 4.0.04
	 */
	public function sanitize_value( &$value ) {
		FrmAppHelper::sanitize_value( 'sanitize_text_field', $value );
	}
}
