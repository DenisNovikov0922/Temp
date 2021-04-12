<?php
/**
 * Enable branding field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding   = get_option( 'udb_branding' );
	$is_checked = isset( $branding['enabled'] ) ? 1 : 0;
	?>

	<div class="field setting-field">
		<label for="udb_branding[enabled]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_branding[enabled]" id="udb_branding[enabled]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

};
