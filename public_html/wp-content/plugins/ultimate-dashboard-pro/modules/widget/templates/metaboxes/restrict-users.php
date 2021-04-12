<?php
/**
 * Priority metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	wp_nonce_field( 'udb_restrict_users', 'udb_restrict_users_nonce' );

	$allowed_user_ids = get_post_meta( $post->ID, 'udb_restrict_users', true );
	$allowed_user_ids = empty( $allowed_user_ids ) ? array( 'all' ) : $allowed_user_ids;
	$allowed_user_ids = is_serialized( $allowed_user_ids ) ? unserialize( $allowed_user_ids ) : $allowed_user_ids;

	$users = get_users();
	?>

	<p>
		<?php _e( 'Show Widget to:', 'ultimatedashboard' ); ?>
	</p>
	<select class="udb-widget-roles-field" name="udb_restrict_users[]" multiple>

		<option value="all"  <?php echo esc_attr( in_array( 'all', $allowed_user_ids, true ) ? 'selected' : '' ); ?>>
			<?php _e( 'All', 'ultimatedashboard' ); ?>
		</option>

		<?php foreach ( $users as $user ) : ?>
			<?php
			$selected_attr = '';
			$selected_attr = in_array( $user->ID, $allowed_user_ids ) ? 'selected' : '';

			$name = $user->first_name . ' ' . $user->last_name;
			$name = empty( trim( $name ) ) ? $user->display_name : $name;
			?>
			<option value="<?php echo esc_attr( $user->ID ); ?>" <?php echo esc_attr( $selected_attr ); ?>>
				<?php echo esc_attr( $name ); ?>
			</option>
		<?php endforeach; ?>

	</select>

	<?php

};
