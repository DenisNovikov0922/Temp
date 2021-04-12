<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div class="frm4 frm_form_field">
	<label class="frm_primary_label"><?php esc_html_e( 'Icons', 'formidable-pro' ); ?></label>
	<?php FrmStylesHelper::bs_icon_select( $style, $frm_style, 'minus' ); ?>
</div>
