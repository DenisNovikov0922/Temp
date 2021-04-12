<?php

/**
 * Shortcodes
 *
 * @package RM_WC
 * @subpackage Shortcodes
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'RM_WC_Shortcodes' ) ) :

class RM_WC_Shortcodes {

	/** Vars ******************************************************************/

	/**
	 * @var array Shortcode => function
	 */
	public $codes = array();

	/** Functions *************************************************************/

	/**
	 *
	 * @uses set_globals()
	 * @uses add_shortcodes()
	 */
	public function __construct() {
		$this->set_globals();
		$this->add_shortcodes();
	}

	/**
	 * Shortcode globals
	 *
	 * @access private
	 */
	private function set_globals() {
                /** Fill $this->codes here */
	}

	/**
	 * Register shortcodes
	 *
	 * @uses add_shortcode()
	 */
	private function add_shortcodes() {
		foreach ( (array) $this->codes as $code => $function ) {
			add_shortcode( $code, $function );
		}
	}
}
endif;
