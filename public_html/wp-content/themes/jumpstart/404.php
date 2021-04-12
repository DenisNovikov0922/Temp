<?php get_header(); ?>

<section class="bg-primary-3 text-white p-0 o-hidden">
	<div class="container min-vh-100 d-flex flex-column justify-content-between text-center py-4 py-md-5">
		
		<?php the_custom_logo(); ?>
		
		<div class="my-5">
			<div class="row justify-content-center">
			
				<div class="col-4 mb-5 px-4">
					<?php 
						if( $url = get_theme_mod( '404_image' ) ) {
							echo '<img src="'. esc_url( $url ) .'" alt="'. esc_html__( '404 - Page Not Found', 'jumpstart' ) .'" class="img-fluid">';
						} 
					?>
				</div>
				
				<div class="col-12">
					<h1><?php esc_html_e( '404 - Page Not Found', 'jumpstart' ); ?></h1>
					<div class="lead"><?php esc_html_e( "Whoops, it looks like the page you request wasn't found.", 'jumpstart' ); ?></div>
				</div>
			
			</div>
		</div>
		
		<div>
		  	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-sm btn-outline-light fade-page">
		  		<?php esc_html_e( 'Back to Home', 'jumpstart' ); ?>
		  	</a>
		</div>
		
	</div>
</section>

<?php get_footer();