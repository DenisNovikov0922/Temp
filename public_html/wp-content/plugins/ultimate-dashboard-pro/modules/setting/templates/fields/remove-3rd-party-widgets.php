<?php
/**
 * Remove 3rd party widgets field.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Widget_Helper;

return function () {

	$widget_helper = new Widget_Helper();
	$widgets       = $widget_helper->get_3rd_party();
	$settings      = get_option( 'udb_settings' );

	if ( empty( $widgets ) ) {
		_e( 'No 3rd Party Widgets available.', 'ultimatedashboard' );
	}
	?>

	<div class="setting-fields is-gapless">

		<?php
		foreach ( $widgets as $id => $widget ) {

			$is_checked = isset( $settings[ $id ] ) ? 1 : 0;
			?>

			<div class="field setting-field">
				<label for="udb_settings[<?php echo esc_attr( $id ); ?>]" class="label checkbox-label">
					<?php echo esc_attr( $widget['title_stripped'] ); ?> (<code><?php echo esc_attr( $id ); ?></code>)
					<input type="checkbox" name="udb_settings[<?php echo esc_attr( $id ); ?>]" id="udb_settings[<?php echo esc_attr( $id ); ?>]" value="1" <?php checked( $is_checked, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
		}
		?>

	</div>

	<?php

};
