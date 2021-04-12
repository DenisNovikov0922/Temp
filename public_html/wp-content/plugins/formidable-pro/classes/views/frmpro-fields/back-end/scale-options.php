<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<p class="frm8 frm_first frm_form_field frm-number-range">
	<label><?php esc_html_e( 'Range', 'formidable-pro' ); ?></label>

	<span class="frm_grid_container">
		<span class="frm5 frm_form_field frm-range-min">
			<select name="field_options[minnum_<?php echo absint( $field['id'] ); ?>]" class="scale_minnum" id="scale_minnum_<?php echo absint( $field['id'] ); ?>">
				<?php for ( $i = 0; $i < 10; $i++ ) { ?>
					<option value="<?php echo absint( $i ); ?>" <?php selected( $field['minnum'], $i ); ?>>
						<?php echo absint( $i ); ?>
					</option>
				<?php } ?>
			</select>
		</span>
		<span class="frm5 frm_last frm_form_field">
			<select name="field_options[maxnum_<?php echo absint( $field['id'] ); ?>]" class="scale_maxnum" id="scale_maxnum_<?php echo absint( $field['id'] ); ?>">
				<?php for ( $i = 1; $i <= 20; $i++ ) { ?>
					<option value="<?php echo absint( $i ); ?>" <?php selected( $field['maxnum'], $i ); ?>>
						<?php echo absint( $i ); ?>
					</option>
				<?php } ?>
			</select>
		</span>
	</span>
</p>
