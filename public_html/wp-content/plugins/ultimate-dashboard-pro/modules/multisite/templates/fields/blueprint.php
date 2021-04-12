<?php
/**
 * Blueprint field.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$blueprint = get_site_option( 'udb_multisite_blueprint' );
	$subsites  = get_sites();

	if ( ! empty( $subsites ) ) {

		echo '<select name="udb_multisite_blueprint">';

		?>

			<option value="0" <?php selected( $blueprint, 0 ); ?>><?php _e( 'Select a Site', 'ultimatedashboard' ); ?></option>

			<?php
			foreach ( $subsites as $subsite ) {
				$subsite_id   = get_object_vars( $subsite )['blog_id'];
				$subsite_name = get_blog_details( $subsite_id )->blogname;
				?>
				<option value="<?php echo esc_attr( $subsite_id ); ?>" <?php selected( $blueprint, $subsite_id ); ?>><?php echo esc_html( $subsite_name ); ?></option>
				<?php
			}

		echo '</select>';

		?>

		<p>
			<?php _e( 'The settings and dashboard widgets of the selected site will apply to all other sites on the network.', 'ultimatedashboard' ); ?>
		</p>

		<?php

	}

};
