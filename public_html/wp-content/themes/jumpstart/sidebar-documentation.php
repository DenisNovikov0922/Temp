<div class="col-lg-4 sticky-lg-top">
	<div class="pl-xl-4">
		
		<?php 
			if ( is_single() ) {
				get_template_part( 'inc/content-documentation-related' ); 
			}
		?>

		<ul class="list-group">

			<?php if( get_theme_mod( 'documentation_archive_email_us_address' ) ) : ?>
			
				<li class="list-group-item">
					<h6 class="mb-2"><?php esc_html_e( 'Email Us', 'jumpstart' ); ?></h6>
					<a href="mailto:<?php echo get_theme_mod( 'documentation_archive_email_us_address' ); ?>"><?php echo get_theme_mod( 'documentation_archive_email_us_address' ); ?></a>
				</li>
				
			<?php endif; ?>

			<?php if( get_theme_mod( 'documentation_archive_call_us_number' ) ) : ?>
			
				<li class="list-group-item">
					<h6 class="mb-2"><?php esc_html_e( 'Call any time', 'jumpstart' ); ?></h6>
					<a href="tel:<?php echo get_theme_mod( 'documentation_archive_call_us_number' ); ?>"><?php echo get_theme_mod( 'documentation_archive_call_us_number' ); ?></a>
				</li>
				
			<?php endif; ?>

			<?php if( $url = get_theme_mod( 'documentation_archive_get_in_touch_url' ) ) : ?>
			
				<li class="list-group-item">
					<h6 class="mb-2"><?php esc_html_e( 'Get in touch', 'jumpstart' ); ?></h6>
					<a href="<?php echo esc_url( $url ); ?>">
						<?php esc_html_e( 'Leave a message', 'jumpstart' ); ?>
					</a>
				</li>
				
			<?php endif; ?>

		</ul>
		
		<?php if( get_theme_mod( 'documentation_archive_sidebar_cta_label' ) && get_theme_mod( 'documentation_archive_sidebar_cta_url' ) ) : ?>
		
			<a href="<?php echo esc_url( get_theme_mod( 'documentation_archive_sidebar_cta_url' ) ); ?>" class="btn btn-primary btn-block mt-3 mt-md-4">
				<?php echo get_theme_mod( 'documentation_archive_sidebar_cta_label' ); ?>
			</a>
			
		<?php endif; ?>
		
		<?php 
			if( is_active_sidebar( 'documentation' ) ){
				dynamic_sidebar( 'documentation' );
			}
		?>
		
	</div>
</div>