<?php
/**
 * License page template.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap udb-license-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'Enter your license key for Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<h1 style="display: none;"></h1>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<div class="heatbox">
					<?php
					$license        = get_option( 'ultimate_dashboard_license_key' );
					$license_status = get_option( 'ultimate_dashboard_license_status' );

					settings_fields( 'udb_license' );
					?>
					<h2>
						<?php _e( 'Status', 'ultimatedashboard' ); ?>:
						<?php if ( $license_status !== false && $license_status == 'valid' ) { ?>
							<span style="color:#6dbb7a; font-weight: 700; font-style: italic;"><?php _e( 'Active', 'ultimatedashboard' ); ?></span>
						<?php } else { ?>
							<span style="color: tomato; font-weight: 700; font-style: italic;"><?php _e( 'Inactive', 'ultimatedashboard' ); ?></span>
						<?php } ?>
					</h2>
					<table class="form-table">
						<tbody>
							<tr>
								<th>
									<?php _e( 'License Key', 'ultimatedashboard' ); ?>
								</th>
								<td>
									<input id="ultimate_dashboard_license_key" name="ultimate_dashboard_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
									<p class="description" for="wpbf_premium_license_key"><?php _e( 'Enter your Ultimate Dashboard PRO license key.', 'ultimatedashboard' ); ?></p>
								</td>
							</tr>
							<?php if ( false !== $license && '' !== $license ) { ?>
							<tr>
								<th>
									<?php _e( 'Activate License', 'ultimatedashboard' ); ?>
								</th>
								<td>
									<?php if ( false !== $license_status && 'valid' === $license_status ) { ?>
										<?php wp_nonce_field( 'udb_nonce', 'udb_nonce' ); ?>
										<input type="submit" class="button-primary" name="udb_license_activate" value="<?php _e( 'Revalidate', 'ultimatedashboard' ); ?>"/>
										<input type="submit" class="button-secondary" name="udb_license_deactivate" value="<?php _e( 'Deactivate License', 'ultimatedashboard' ); ?>"/>
									<?php } else { ?>
										<?php wp_nonce_field( 'udb_nonce', 'udb_nonce' ); ?>
										<input type="submit" class="button-secondary" name="udb_license_activate" value="<?php _e( 'Activate License', 'ultimatedashboard' ); ?>"/>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php submit_button( '', 'button button-primary button-larger' ); ?>
			</form>

		</div>

	</div>

	<?php
};
