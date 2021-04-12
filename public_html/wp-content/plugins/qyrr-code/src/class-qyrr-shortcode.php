<?php

namespace qyrr;

/**
 * Shortcode Class
 */
class QYRR_Shortcode {

	/**
	 * Return instance of QYRR_Shortcode
	 *
	 * @return void
	 */
	public static function get_instance() {
		new QYRR_Shortcode();
	}

	/**
	 * Constructor for QYRR_Shortcode.
	 */
	public function __construct() {
		add_shortcode( 'qyrr', array( $this, 'add_shortcode' ) );
	}

	/**
	 * Register a shortcode for qr code display.
	 *
	 * @param  array $atts array of possible attributes.
	 * @return void
	 */
	public function add_shortcode( $atts ) {

		/* check if qr code is set */
		if ( ! isset( $atts['code'] ) ) {
			return;
		}

		/*get post by code */
		$qr      = get_page_by_path( $atts['code'], OBJECT, 'qr' );
		$qr_code = get_post_meta( $qr->ID, 'data-uri', true );

		if ( ! $atts['size'] ) {
			$shortcode = '<div id="qr-code"><img src="' . $qr_code . '"</div>';
		} else {
			$shortcode = '<div id="qr-code"><img width="' . $atts['size'] . '" src="' . $qr_code . '"</div>';
		}
		return $shortcode;
	}
}
