<?php
/**
 * Widgets order field.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings          = get_option( 'udb_settings' );
	$widget_order_user = isset( $settings['widget_order'] ) ? absint( $settings['widget_order'] ) : 0;

	$blogusers = get_users(
		array(
			'blog_id' => '1',
			'role'    => 'administrator',
		)
	);

	echo '<select name="udb_settings[widget_order]">';

	?>

	<option value="0" <?php selected( $widget_order_user, 0 ); ?>><?php _e( 'Custom Order', 'ultimatedashboard' ); ?></option>

	<?php

	foreach ( $blogusers as $user ) {

		$user_id   = $user->ID;
		$user_name = ucfirst( $user->display_name );

		?>

		<option value="<?php echo esc_attr( $user_id ); ?>" <?php selected( $widget_order_user, $user_id ); ?>>
			<?php echo esc_html( $user_name ); ?>'s <?php _e( 'Order', 'ultimatedashboard' ); ?>
		</option>

		<?php

	}

	echo '</select>';

	?>

	<p class="description">
		<?php _e( 'The order of the dashboard widgets is saved on a per user basis. Select a user whose widget order you want to apply to all users.', 'ultimatedashboard' ); ?>
		<br>
		<strong>
			<?php _e( 'Note:', 'ultimatedashboard' ); ?>
		</strong>
		<?php _e( 'The user must be an admin to show up in the list.', 'ultimatedashboard' ); ?>
	</p>

	<?php

};
