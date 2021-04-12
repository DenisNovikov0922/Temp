<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$e_args = array( 'textarea_name' => $field_name );
if ( $field['default_value'] !== '' ) {
	$e_args['editor_class'] = 'frm_has_default';
}

if ( FrmAppHelper::is_admin() ) { ?>
	<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea frm_full_rte">
		<?php
		$e_args['dfw'] = true;
		wp_editor( str_replace( '&quot;', '"', $field['value'] ), $html_id, $e_args );
		?>
	</div>
<?php
// Rich text for front-end, including Preview page
} elseif ( $field['type'] === 'rte' ) {
	$e_args['media_buttons'] = false;
	if ( $field['max'] ) {
		$e_args['textarea_rows'] = $field['max'];
	}
	$e_args['tinymce'] = array(
		'init_instance_callback' => 'frmProForm.changeRte'
	);

	$e_args = apply_filters( 'frm_rte_options', $e_args, $field );

	if ( $field['size'] ) {
	?>
		<style type="text/css">#wp-field_<?php echo esc_attr( $field['field_key'] ) ?>-wrap{width:<?php echo esc_attr( $field['size'] ) . ( is_numeric( $field['size'] ) ? 'px' : '' ); ?>;}</style><?php
	}

	wp_editor( FrmAppHelper::esc_textarea( $field['value'], true ), $html_id, $e_args );

	// If submitting with Ajax or on preview page and tinymce is not loaded yet, load it now

	unset( $e_args );
}

if ( $field['default_value'] !== '' ) {
	?>
	<input type="hidden" id="<?php echo esc_attr( $html_id ); ?>-frmval" value="<?php echo esc_attr( $field['default_value'] ); ?>"/>
	<?php
}
