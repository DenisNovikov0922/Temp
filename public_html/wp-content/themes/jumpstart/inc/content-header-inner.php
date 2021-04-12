<?php
	$label = get_theme_mod( 'header_button_label', 'Purchase Now' );
	$url   = get_theme_mod( 'header_button_url' ) ;
?>

<div class="container">

	<?php the_custom_logo(); ?>

	<div class="d-flex align-items-center order-lg-3">
		
		<?php if( $label && $url ) : ?>
			<a href="<?php echo esc_url( $url ); ?>" class="btn btn-primary ml-lg-4 mr-3 mr-md-4 mr-lg-0 d-none d-sm-block order-lg-3">
				<?php echo wp_kses_post( $label ); ?>
			</a>
		<?php endif; ?>
		
		<button aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'jumpstart' ); ?>" class="navbar-toggler" data-target="#navigation-menu" data-toggle="collapse" type="button">
			<img src="<?php echo get_theme_file_uri( '/style/img/icon-menu.svg' ); ?>" alt="<?php esc_attr_e( 'Navbar Toggler Open Icon', 'jumpstart' ); ?>" class="navbar-toggler-open icon icon-sm" data-inject-svg>
			<img src="<?php echo get_theme_file_uri( '/style/img/icon-x.svg' ); ?>" alt="<?php esc_attr_e( 'Navbar Toggler Close Icon', 'jumpstart' ); ?>" class="navbar-toggler-close icon icon-sm" data-inject-svg>
	    </button>	 
		
  	</div>
			
	<div class="collapse navbar-collapse order-lg-2 justify-content-lg-end" id="navigation-menu">
		<?php
			if ( has_nav_menu( 'primary' ) ){
				
				wp_nav_menu( 
					array(
					    'theme_location'    => 'primary',
					    'depth'             => 4,
					    'container'         => false,
					    'container_class'   => false,
					    'menu_class'        => 'navbar-nav my-3 my-lg-0',
					    'menu_id'           => false,
					    'items_wrap'        => '<ul class="%2$s">%3$s</ul>',
					    'walker'            => new WP_Bootstrap_Navwalker()
					)
				);
				
			}
		?>
	</div>
	
</div>