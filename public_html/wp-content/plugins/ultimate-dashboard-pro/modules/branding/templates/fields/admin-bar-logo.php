<?php
/**
 * Admin bar logo field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding       = get_option( 'udb_branding' );
	$is_checked     = isset( $branding['remove_admin_bar_logo'] ) ? $branding['remove_admin_bar_logo'] : 0;
	$admin_bar_logo = isset( $branding['admin_bar_logo_image'] ) ? $branding['admin_bar_logo_image'] : false;

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} else {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
	}

	?>

	<input class="all-options udb-branding-admin-bar-logo-url" type="text" name="udb_branding[admin_bar_logo_image]" value="<?php echo esc_url( $admin_bar_logo ); ?>">
	<a href="#" class="udb-branding-admin-bar-logo-upload button-secondary"><?php _e( 'Add or Upload File', 'ultimatedashboard' ); ?></a>
	<a href="#" class="udb-branding-image-remove button-secondary">x</a>

	<div class="field setting-field" style="margin-top: 10px;">
		<label for="udb_branding[remove_admin_bar_logo]" class="label checkbox-label">
			<?php _e( 'Remove Admin Bar Logo', 'ultimatedashboard' ); ?>
			<input type="checkbox" name="udb_branding[remove_admin_bar_logo]" id="udb_branding[remove_admin_bar_logo]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
