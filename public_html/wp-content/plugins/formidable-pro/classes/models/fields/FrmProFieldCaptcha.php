<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

/**
 * @since 3.0
 */
class FrmProFieldCaptcha extends FrmFieldCaptcha {

	private static $checked;

	protected function field_settings_for_type() {
		$settings = parent::field_settings_for_type();

		FrmProFieldsHelper::fill_default_field_display( $settings );
		return $settings;
	}

	public function front_field_input( $args, $shortcode_atts ) {
		if ( self::checked() ) {
			return '';
		}

		return parent::front_field_input( $args, $shortcode_atts );
	}

	/**
	 * @since 4.07
	 * @param array $args
	 * @return array
	 */
	public function validate( $args ) {
		if ( ! $this->should_validate() ) {
			return array();
		}

		if ( ! is_callable( array( $this, 'validate_against_api' ) ) ) {
			return parent::validate( $args );
		}

		if ( isset( $_POST['g-recaptcha-response'] ) ) {
			$errors = $this->validate_against_api( $args );

			if ( $errors ) {
				return $errors;
			}

			self::$checked = wp_create_nonce( 'frm_captcha' );
			return array();
		}

		if ( self::validate_checked() ) {
			return array();
		}

		return array( 'field' . $args['id'] => __( 'The captcha is missing from this form', 'formidable-pro' ) );
	}

	/**
	 * @since 4.07
	 * @return mixed
	 */
	private static function validate_checked() {
		if ( isset( $_POST['recaptcha_checked'] ) ) {
			$nonce = FrmAppHelper::get_param( 'recaptcha_checked', '', 'post', 'sanitize_text_field' );
			if ( wp_verify_nonce( $nonce, 'frm_captcha' ) ) {
				self::$checked = wp_create_nonce( 'frm_captcha' );
				return self::$checked;
			}
		}

		return false;
	}

	/**
	 * @since 4.07
	 * @return mixed
	 */
	public static function checked() {
		if ( isset( self::$checked ) ) {
			return self::$checked;
		}

		// pass along recaptcha_checked even if there is no captcha being validated
		// (which would happen if we're going to a previous page without a captcha)
		return self::validate_checked();
	}

	/**
	 * @since 4.07
	 * @return bool
	 */
	public static function posting_captcha_data() {
		return isset( $_POST['g-recaptcha-response'] ) || isset( $_POST['recaptcha_checked'] );
	}

	/**
	 * @since 4.07
	 */
	public static function render_checked_response() {
		if ( self::posting_captcha_data() ) {
			$checked = self::checked();
			if ( $checked ) {
				?>
				<input type="hidden" name="recaptcha_checked" value="<?php echo esc_attr( $checked ); ?>" />
				<?php
			}
		}
	}
}
