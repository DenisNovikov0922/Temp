<?php $sticky = ( 'yes' == get_theme_mod( 'use_sticky_header' , 'yes' ) ) ? 'top' : 'none'; ?>

<div class="navbar-container bg-light">
	<nav class="navbar navbar-expand-lg navbar-light" data-sticky="<?php echo esc_attr( $sticky ); ?>">
		<?php get_template_part( 'inc/content-header', 'inner' ); ?>
	</nav>
</div>