<?php
/**
 * Add submenu button template.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<button type="button" class="udb-admin-menu--add-item udb-admin-menu--add-new-submenu">
		<i class="dashicons dashicons-plus"></i>
		<?php _e( 'Add Item', 'ultimatedashboard' ); ?>
	</button>

	<?php
};
