<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<a href="?page=formidable-styles&frm_action=new_style" class="button button-secondary frm-button-secondary frm-with-plus">
	<?php FrmProAppHelper::icon_by_class( 'frmfont frm_plus_icon frm_svg15' ); ?>
	<?php esc_html_e( 'New Style', 'formidable-pro' ); ?>
</a>

<?php if ( ! empty( $style->ID ) ) { ?>
	<a href="?page=formidable-styles&frm_action=duplicate&style_id=<?php echo absint( $style->ID ); ?>" class="button button-secondary frm-button-secondary alignright">
		<?php esc_html_e( 'Duplicate', 'formidable-pro' ); ?>
	</a>
<?php } ?>
