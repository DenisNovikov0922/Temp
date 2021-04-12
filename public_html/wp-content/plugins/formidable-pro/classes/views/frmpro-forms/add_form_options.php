<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<p class="howto">
	<?php esc_html_e( 'Determine who can see, submit, and edit form entries.', 'formidable-pro' ) ?>
</p>

<div class="frm_grid_container">
	<p class="frm4 frm_form_field">
        <label id="for_logged_in_role" for="logged_in">
			<input type="checkbox" name="logged_in" id="logged_in" value="1" <?php checked( $values['logged_in'], 1 ); ?> />
			<?php printf( __( 'Limit form visibility %1$sto%2$s', 'formidable-pro' ), '<span class="hide_logged_in ' . esc_attr( $values['logged_in'] ? '' : 'frm_invisible' ) . '">', '</span>' ) ?>
        </label>
	</p>
    <p class="frm8 frm_form_field frm_select_with_label">
        <select name="options[logged_in_role][]" id="logged_in_role" class="frm_multiselect hide_logged_in <?php echo esc_attr( $values['logged_in'] ? '' : 'frm_invisible' ); ?>" multiple="multiple">
			<option value="" <?php FrmProAppHelper::selected( $values['logged_in_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
            <?php FrmAppHelper::roles_options($values['logged_in_role']); ?>
        </select>
	</p>

    <p class="frm4 frm_form_field">
		<label for="single_entry">
			<input type="checkbox" name="options[single_entry]" id="single_entry" value="1" <?php checked( $values['single_entry'], 1 ); ?> />
			<?php printf( __( 'Limit number of entries %1$sto one per%2$s', 'formidable-pro' ), '<span class="hide_single_entry' . esc_attr( $values['single_entry'] ? '' : ' frm_invisible' ) . '">', '</span>' ) ?>
		</label>
    </p>
    <p class="frm8 frm_form_field frm_select_with_label">
        <select name="options[single_entry_type]" id="frm_single_entry_type" class="hide_single_entry <?php echo esc_attr( $values['single_entry'] ? '' : 'frm_invisible' ); ?>">
			<option value="user" <?php selected( $values['single_entry_type'], 'user' ); ?>>
				<?php esc_html_e( 'Logged-in User', 'formidable-pro' ); ?>
			</option>
			<?php if ( FrmAppHelper::ips_saved() ) { ?>
			<option value="ip" <?php selected( $values['single_entry_type'], 'ip' ); ?>>
				<?php esc_html_e( 'IP Address', 'formidable-pro' ); ?>
			</option>
			<?php } ?>
			<option value="cookie" <?php selected( $values['single_entry_type'], 'cookie' ); ?>>
				<?php esc_html_e( 'Saved Cookie', 'formidable-pro' ); ?>
			</option>
        </select>
    </p>

	<p id="frm_cookie_expiration" class="frm_indent_opt <?php echo ( $values['single_entry'] && $values['single_entry_type'] == 'cookie' ) ? '' : 'frm_hidden'; ?>">
		<label><?php esc_html_e( 'Cookie Expiration', 'formidable-pro' ); ?></label>
		<input type="text" name="options[cookie_expiration]" value="<?php echo esc_attr($values['cookie_expiration']) ?>"/>
		<span class="howto"><?php esc_html_e( 'hours', 'formidable-pro' ); ?></span>
	</p>

    <p>
		<label for="editable">
			<input type="checkbox" name="editable" id="editable" value="1" <?php checked( $values['editable'], 1 ) ?> />
			<?php esc_html_e( 'Allow front-end editing of entries', 'formidable-pro' ); ?>
		</label>
    </p>

	<p class="frm4 frm_form_field hide_editable frm_indent_opt <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<label id="for_editable_role" for="editable_role">
			<?php esc_html_e( 'Role required to edit one\'s own entries', 'formidable-pro' ); ?>
		</label>
	</p>
	<p class="frm8 frm_form_field hide_editable <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
        <select name="options[editable_role][]" id="editable_role" multiple="multiple" class="frm_multiselect">
			<option value="" <?php FrmProAppHelper::selected( $values['editable_role'], '' ); ?>><?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?></option>
            <?php FrmAppHelper::roles_options($values['editable_role']); ?>
        </select>
	</p>

<?php
if ( isset( $values['open_editable'] ) && empty( $values['open_editable'] ) ) {
    $values['open_editable_role'] = '-1';
}
?>
	<p class="frm4 frm_form_field hide_editable frm_indent_opt <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<label id="for_open_editable_role" for="open_editable_role">
			<?php esc_html_e( 'Role required to edit other users\' entries', 'formidable-pro' ); ?>
		</label>
	</p>
	<p class="frm8 frm_form_field hide_editable <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<select name="options[open_editable_role][]" id="open_editable_role" multiple="multiple" class="frm_multiselect">
			<option value="" <?php FrmProAppHelper::selected( $values['open_editable_role'], '' ); ?>>
				<?php esc_html_e( 'Logged-in Users', 'formidable-pro' ); ?>
			</option>
			<?php FrmAppHelper::roles_options( $values['open_editable_role'] ); ?>
		</select>
	</p>
	<p class="frm4 frm_form_field hide_editable frm_indent_opt <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
	        <label for="edit_action">
				<?php esc_html_e( 'On Update', 'formidable-pro' ); ?>
			</label>
	</p>
	<p class="frm8 frm_form_field hide_editable <?php echo esc_attr( $values['editable'] ? '' : 'frm_hidden' ); ?>">
            <select name="options[edit_action]" id="edit_action">
				<option value="message" <?php selected( $values['edit_action'], 'message' ); ?>>
					<?php esc_html_e( 'Show Message', 'formidable-pro' ); ?>
				</option>
				<option value="redirect" <?php selected( $values['edit_action'], 'redirect' ); ?>>
					<?php esc_html_e( 'Redirect to URL', 'formidable-pro' ); ?>
				</option>
				<option value="page" <?php selected( $values['edit_action'], 'page' ); ?>>
					<?php esc_html_e( 'Show Page Content', 'formidable-pro' ); ?>
				</option>
            </select>
	</p>
	<p class="frm4 frm_form_field frm_indent_opt hide_editable edit_action_redirect_box edit_action_box <?php echo esc_attr( $values['edit_action'] === 'redirect' && $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<label for="edit_url">
			<?php esc_html_e( 'Redirect URL', 'formidable-pro' ); ?>
		</label>
	</p>
	<p class="frm_has_shortcodes frm8 frm_form_field hide_editable edit_action_redirect_box edit_action_box <?php echo esc_attr( $values['edit_action'] === 'redirect' && $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<input type="text" name="options[edit_url]" id="edit_url" value="<?php echo esc_attr( isset( $values['edit_url'] ) ? $values['edit_url'] : '' ); ?>" placeholder="http://example.com" />
	</p>

	<p class="frm4 frm_form_field frm_indent_opt hide_editable edit_action_page_box edit_action_box <?php echo esc_attr( $values['edit_action'] == 'page' && $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<label for="options[edit_page_id]">
			<?php esc_html_e( 'Use Content from Page', 'formidable-pro' ); ?>
		</label>
	</p>

	<p class="frm8 frm_form_field hide_editable edit_action_page_box edit_action_box <?php echo esc_attr( $values['edit_action'] == 'page' && $values['editable'] ? '' : 'frm_hidden' ); ?>">
		<?php
		$page_atts = array(
			'field_name'  => 'options[edit_page_id]',
			'page_id'     => isset( $values['edit_page_id'] ) ? $values['edit_page_id'] : ''
		);
		if ( is_callable( 'FrmAppHelper::maybe_autocomplete_pages_options' ) ) {
			FrmAppHelper::maybe_autocomplete_pages_options( $page_atts );
		} else {
			FrmAppHelper::wp_pages_dropdown( $page_atts );
		}
		?>
	</p>

    <p>
		<label for="save_draft">
			<input type="checkbox" name="options[save_draft]" id="save_draft" value="1" <?php checked( $values['save_draft'], 1 ) ?> />
			<?php esc_html_e( 'Allow logged-in users to save drafts', 'formidable-pro' ); ?>
		</label>
    </p>

<?php if ( $has_file_field ) { ?>
	<p>
		<label for="protect_files">
			<input type="checkbox" name="options[protect_files]" id="protect_files" value="1" <?php checked( $values['protect_files'], 1 ) ?> />

			<?php esc_html_e( 'Protect all files uploaded in this form', 'formidable-pro' ); ?>

			<p class="frm4 frm_form_field hide_protect_files frm_indent_opt <?php echo esc_attr( $values['protect_files'] ? '' : 'frm_hidden' ); ?>">
				<label for="protect_files_role">
					<?php esc_html_e( 'Role required to access file', 'formidable-pro' ); ?>
				</label>
			</p>
			<p class="frm8 frm_form_field hide_protect_files <?php echo esc_attr( $values['protect_files'] ? '' : 'frm_hidden' ); ?>">
				<select name="options[protect_files_role][]" id="protect_files_role" multiple="multiple" class="frm_multiselect">
					<?php $roles = isset( $values['protect_files_role'] ) ? $values['protect_files_role'] : array( '' ); ?>
					<option <?php FrmProAppHelper::selected( $roles, '' ); ?> value=""><?php esc_html_e( 'Everyone', 'formidable-pro' ); ?></option>
					<?php FrmAppHelper::roles_options( $roles ); ?>
				</select>
			</p>
		</label>
	</p>
<?php } else { ?>
	<input type="hidden" value="0" name="options[protect_files]" />
<?php } ?>

<?php
if ( is_multisite() ) {
	if ( current_user_can( 'setup_network' ) ) {
	?>
        <p>
			<label for="copy">
				<input type="checkbox" name="options[copy]" id="copy" value="1" <?php echo ( $values['copy'] ) ? ' checked="checked"' : ''; ?> />
				<?php esc_html_e( 'Copy this form to other blogs when Formidable Forms is activated', 'formidable-pro' ); ?>
			</label>
		</p>
		<?php
	} elseif ( $values['copy'] ) {
		?>
        <input type="hidden" name="options[copy]" id="copy" value="1" />
		<?php
    }
}
?>
</div>
