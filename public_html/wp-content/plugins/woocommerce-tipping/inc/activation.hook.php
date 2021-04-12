<?php
register_activation_hook( WPSTIP_FILE, 'wpslash_tipping_activation' );
 
/**
 * Runs only when the plugin is activated.
 *
 * @since 0.1.0
 */
function wpslash_tipping_activation() {
	 
	 set_transient( 'wpslash-tipping-notice', true, 5 );

}


function wpslash_tipping_activation_notice() {
 
	if ( get_transient( 'wpslash-tipping-notice' ) ) {
		?>
		<div class="updated notice is-dismissible">
			<p><?php esc_html_e('Thanks for installing Tipping for WooCommerce', 'wpslash-tipping'); ?>. <strong><a href="admin.php?page=wc-settings&amp;tab=wpslash_tipping"><?php esc_html_e('Click Here', 'wpslash-tipping'); ?></a></strong> <?php esc_html_e('to get started with the configuration'); ?>.</p>
		</div>
		<?php
		delete_transient( 'wpslash-tipping-notice' );
	}
}

add_action( 'admin_notices', 'wpslash_tipping_activation_notice' );

?>
