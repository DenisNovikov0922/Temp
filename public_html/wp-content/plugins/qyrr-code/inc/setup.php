<?php

if ( ! function_exists( 'qyrr_fs' ) ) {
	/**
	 * Freemius setup.
	 *
	 * @return array
	 */
	function qyrr_fs() {
		global $qyrr_fs;

		if ( ! isset( $qyrr_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$qyrr_fs = fs_dynamic_init(
				array(
					'id'             => '5292',
					'slug'           => 'qyrr',
					'type'           => 'plugin',
					'public_key'     => 'pk_4ecb1bb8e14d1ef36183b1f5b032f',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'menu'           => array(
						'slug'    => 'edit.php?post_type=qr',
						'contact' => false,
						'support' => false,
					),
				)
			);
		}

		return $qyrr_fs;
	}

	// Init Freemius.
	qyrr_fs();
	// Signal that SDK was initiated.
	do_action( 'qyrr_fs_loaded' );
}
