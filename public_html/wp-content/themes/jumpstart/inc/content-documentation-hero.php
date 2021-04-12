<?php 
	global $post;
	
	if( isset( $post->post_type ) ){
		$post_type = $post->post_type;
	} else {
		$post_type = 'post';
	}
	
	if( 'documentation' == $post_type ){
		$title 		= get_theme_mod( 'documentation_archive_title', 'Help Center' ); 
		$subtitle 	= get_theme_mod( 'documentation_archive_subtitle', 'Get advice and help from our expert team' ); 
		$searches 	= get_theme_mod( 'documentation_archive_popular_searches' ); 
	} else {
		$title 		= get_theme_mod( 'post_archive_title', 'Our Blog' ); 
		$subtitle 	= get_theme_mod( 'post_archive_subtitle', 'Read our latest writings.' ); 
		$searches 	= get_theme_mod( 'post_archive_popular_searches' ); 
	}
?>

<section class="bg-primary-3 text-white o-hidden">
	<div class="container">
		<div class="row">
			<div class="col-xl-8 col-lg-9">
				
				<h1 class="display-3"><?php echo wp_kses_post( $title ); ?></h1>
				<div class="lead"><?php echo wp_kses_post( $subtitle ); ?></div>
				
				<form class="mt-5 d-flex flex-column flex-md-row form-group search-form" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="hidden" name="post_type" value="<?php echo $post_type; ?>" />
					<input class="form-control form-control-lg h-100 mb-2 mb-md-0 mr-md-3" name="s" placeholder="<?php esc_attr_e( 'Search for articles', 'jumpstart' ); ?>" type="search">
					<button class="btn btn-lg btn-primary" type="submit"><?php esc_attr_e( 'Search', 'jumpstart' ); ?></button>
				</form>

				<?php if( $searches ) : ?>
				
					<?php $popular_searches = explode( ',', $searches ); ?>	
				
					<div class="d-flex flex-wrap align-items-center">
						
						<div class="text-small mb-2 mb-sm-0 mr-2"><?php esc_html_e( 'Popular keywords:', 'jumpstart' ); ?></div>
						
						<ul class="d-flex flex-wrap list-unstyled mb-0">
							<?php 
								foreach( $popular_searches as $popular_search ) {
								
									echo '<li class="m-1"><a href="' . esc_url( home_url( '/?s=' ) ) . str_replace( ' ', '+', trim( $popular_search ) ) . '" class="badge badge-pill badge-white">'. trim( $popular_search ) .'</a></li>';
									
								} 
							?>
						</ul>
						
					</div>
					
				<?php endif; ?>

			</div>
		</div>
	</div>

	<div class="position-absolute w-50 h-75 bottom right" data-jarallax-element="100">
		<div class="blob blob-4 bg-gradient w-100 h-100 bottom right"></div>
	</div>

</section>