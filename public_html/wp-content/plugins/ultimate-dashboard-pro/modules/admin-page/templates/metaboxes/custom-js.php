<?php
/**
 * Custom JS field inside "Advanced" metabox.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$custom_js = get_post_meta( $post->ID, 'udb_custom_js', true );
	?>

	<h4><?php _e( 'Custom JS', 'ultimatedashboard' ); ?></h4>
	<textarea id="udb_custom_js" class="widefat textarea udb-custom-js" name="udb_custom_js"><?php echo wp_unslash( $custom_js ); ?></textarea>

	<?php

};
