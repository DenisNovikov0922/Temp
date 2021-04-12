<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<tr>
    <td>
		<label><?php esc_html_e( 'Update Button Text', 'formidable-pro' ); ?></label>
    </td>
    <td>
        <input type="text" name="options[edit_value]" value="<?php echo esc_attr($values['edit_value']); ?>" />
    </td>
</tr>

<?php if ( $page_field ) { ?>
<tr>
    <td>
		<label><?php esc_html_e( 'Previous Button Text', 'formidable-pro' ); ?></label>
    </td>
    <td>
        <input type="text" name="options[prev_value]" value="<?php echo esc_attr($values['prev_value']); ?>" />
    </td>
</tr>
<?php } ?>

<tr>
    <td>
		<label><?php esc_html_e( 'Submit Button Alignment', 'formidable-pro' ); ?></label>
    </td>
    <td>
        <select name="options[submit_align]">
			<option value=""><?php esc_html_e( 'Default', 'formidable-pro' ); ?></option>
			<option value="center" <?php selected( $values['submit_align'], 'center' ); ?>>
				<?php esc_html_e( 'Center', 'formidable-pro' ); ?>
			</option>
			<option value="inline" <?php selected( $values['submit_align'], 'inline' ); ?>>
				<?php esc_html_e( 'Inline', 'formidable-pro' ); ?>
			</option>
        </select>
    </td>
</tr>

<tr>
	<td colspan="2">
		<label>
			<input type="checkbox" id="logic_link_submit" <?php
			echo ( ! empty( $submit_conditions['hide_field'] ) && ( count( $submit_conditions['hide_field'] ) > 1 || reset( $submit_conditions['hide_field'] ) != '' ) ) ? ' checked="checked"' : '';
			?> />
			<?php esc_html_e( 'Add conditional logic to submit button', 'formidable-pro' ); ?>
		</label>
		<?php include( FrmProAppHelper::plugin_path() . '/classes/views/frmpro-forms/_submit_conditional.php' ); ?>
	</td>
</tr>
