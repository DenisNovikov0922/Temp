<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<p class="howto">
	<?php esc_html_e( 'Select the messages you would like to see in your Formidable inbox. Not all types of messages can be disabled.', 'formidable-pro' ); ?>
</p>
<input type="hidden" name="frm_inbox[set]" value="<?php echo esc_attr( FrmProDb::$plug_version ); ?>" />
<?php foreach ( $message_types as $type => $label ) { ?>
<p>
	<label <?php echo $has_access ? '' : 'class="frm_show_upgrade frm_noallow" data-medium="inbox-settings" data-upgrade="' . esc_attr__( 'Inbox options', 'formidable' ) . '" data-requires="' . esc_attr__( 'a current plan or Renew', 'formidable-pro' ) . '" data-message="' . esc_attr__( 'Your account has expired. Please renew to get access to inbox settings.', 'formidable-pro' ) . '"'; ?>>
		<span class="frm_toggle">
			<input type="checkbox" name="frm_inbox[<?php echo esc_attr( $type ); ?>]" id="frm_menu_<?php echo esc_attr( $type ); ?>" value="1"
			<?php
			if ( $has_access ) {
				checked( isset( $settings->inbox[ $type ] ) ? $settings->inbox[ $type ] : 0, 1 );
			} else {
				echo 'checked="checked" disabled="disabled"';
			}
			?> />
			<span class="frm_toggle_slider"></span>
		</span>
		<?php echo esc_html( $label ); ?>
	</label>
</p>
<?php } ?>
