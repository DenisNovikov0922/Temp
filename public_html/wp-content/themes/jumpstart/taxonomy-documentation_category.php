<?php 
	get_header(); 
	
	$term = get_queried_object();
	
	// Documentation Specific Hero Area
	get_template_part( 'inc/content-documentation', 'hero' );
?>

<section class="bg-light">
	<div class="container">
		<div class="row justify-content-between align-items-start">

          	<div class="col-lg-8">

          		<a href="<?php echo esc_url( get_post_type_archive_link( 'documentation' ) ); ?>"><?php esc_html_e( 'Back to Help Center', 'jumpstart' ); ?></a>
				<h2 class="h1 mt-4"><?php echo esc_html( $term->name ); ?></h2>
				<div class="lead"><?php echo esc_html( $term->description ); ?></div>

				<hr class="my-md-4 my-lg-5">
				
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					
					<div class="mb-3 mb-sm-4">
						<a href="<?php the_permalink(); ?>" class="card card-body hover-box-shadow">
							<?php 
								the_title( '<h4>', '</h4>' ); 
								
								if( has_excerpt() ){
									echo '<p>'. get_the_excerpt() .'</p>';
								}
							?>
							<div class="d-flex align-items-center">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar avatar-sm' ) ); ?>
								<div class="text-small ml-3">
									<div><?php esc_html_e( 'Written by', 'jumpstart' ); ?> <strong><?php echo get_the_author_meta( 'display_name' ); ?></strong></div>
									<div><?php echo esc_html__( 'Last updated ', 'jumpstart' ) . human_time_diff( get_the_modified_date( 'U', get_the_id() ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'jumpstart' ); ?></div>
								</div>
							</div>
						</a>
					</div>
						
				<?php
					endwhile;	
					else : 
						
						get_template_part( 'loop/content', 'none' );
						
					endif;
				?>

			</div>

			<?php get_sidebar( 'documentation' ); ?>

		</div>
	</div>
</section>

<?php get_footer();