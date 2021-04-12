<?php
/**
 * Widget columns field.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings          = get_option( 'udb_settings' );
	$dashboard_columns = isset( $settings['dashboard_columns'] ) ? absint( $settings['dashboard_columns'] ) : 4;

	?>

	<select name="udb_settings[dashboard_columns]">
		<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
			<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $dashboard_columns, $i ); ?>><?php echo esc_attr( $i ); ?></option>
		<?php endfor; ?>
	</select>

	<?php

};
