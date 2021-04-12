<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
?>
<div id="frm_bs_dropdown" class="dropdown <?php echo esc_attr( is_rtl() ? 'pull-right' : 'pull-left' ); ?>">
	<a href="#" id="frm-navbarDrop" class="frm-dropdown-toggle" data-toggle="dropdown">
		<h1>
			<?php echo esc_html( $style->post_title ); ?>
			<b class="frm_icon_font frm_arrowdown4_icon"></b>
		</h1>
	</a>
	<ul class="frm-dropdown-menu frm-on-top" role="menu" aria-labelledby="frm-navbarDrop">
		<?php
		foreach ( $styles as $s ) {
			$url         = add_query_arg( array( 'id' => $s->ID ), $base_url );
			$style_name  = FrmAppHelper::truncate( $s->post_title, 30 );
			$style_name .= empty( $s->menu_order ) ? '' : ' (' . __( 'default', 'formidable-pro' ) . ')';
			?>
			<li>
				<a href="<?php echo esc_url( $url ); ?>" tabindex="-1"><?php echo esc_html( $style_name ); ?></a>
			</li>
			<?php
			unset( $s );
		}
		?>
	</ul>
</div>
