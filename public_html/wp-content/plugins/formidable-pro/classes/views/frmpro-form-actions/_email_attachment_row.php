<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<!-- Add email attachment row -->
<div class="frm_email_row">

	<h3>
		<?php esc_html_e( 'Attachment', 'formidable-pro' ); ?>
	</h3>

	<!-- Attachment control container -->
	<div class="frm_email_add_attachment_container frm_image_styling_frame" style="margin-left:0">
		<?php
		/**
		 * Anchor link to add an attachment.
		 */
		printf(
			'<p><a style="%s" class="frm_email_add_attachment button frm-button-secondary" href="#">+ %s</a></p>',
			( $has_attachment ? 'display: none;' : 'display: block;' ),
			esc_html__( 'Add attachment', 'formidable-pro' )
		);
		?>

		<span class="frm_email_attachment_icon">
			<?php
			if ( $has_attachment ) {
				echo wp_get_attachment_image( $form_action->post_content['email_attachment_id'], array( '20', '20' ), true, array( 'class' => 'frm_image_preview' ) );
			}
			?>
		</span>
		<div class="frm_image_data">
			<div class="frm_email_attachment_name">
				<?php
				if ( $has_attachment ) {
					echo esc_html( basename( get_attached_file( $form_action->post_content['email_attachment_id'] ) ) );
				}
				?>
			</div>

			<?php
			/**
			 * Anchor link to remove the attachment.
			 */
			printf(
				'<a style="%s" class="frm_email_remove_attachment frm_remove_image_option" href="#" title="%s">%s %s</a>',
				( $has_attachment ? 'display: block;' : 'display: none;' ),
				esc_attr__( 'Remove file', 'formidable-pro' ),
				FrmAppHelper::icon_by_class( 'frm_icon_font frm_delete_icon', array( 'echo' => false ) ),
				esc_html__( 'Delete', 'formidable' )
			);
			?>
		</div>
		<input class="frm_email_attachment" type="hidden" name="<?php echo esc_attr( $pass_args['action_control']->get_field_name( 'email_attachment_id' ) ) ?>" value="<?php echo esc_attr( isset( $form_action->post_content['email_attachment_id'] ) ? $form_action->post_content['email_attachment_id'] : '' ); ?>" />
	</div>
	<!-- Attachment control container end. -->
</div>
<!-- Add email attachment row end. -->
