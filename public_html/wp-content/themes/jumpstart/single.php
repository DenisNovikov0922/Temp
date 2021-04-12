<?php
	get_header();
	the_post();
?>

<?php if( has_post_thumbnail() ) : ?>

<section class="p-0 border-top border-bottom bg-light row no-gutters">

	<div class="col-lg-5 col-xl-6 order-lg-2">
		<div class="divider divider-side transform-flip-y bg-light d-none d-lg-block"></div>
		<?php the_post_thumbnail( 'full', array( 'class' => 'flex-fill' ) ); ?>
	</div>

	<div class="col-lg-7 col-xl-6">
		<div class="container min-vh-lg-70 d-flex align-items-center">
			<div class="row justify-content-center">
				<div class="col col-md-10 col-xl-9 py-4 py-sm-5">
					<div class="my-4 my-md-5">

						<div class="single-post-meta d-flex align-items-center mb-3 mb-xl-4">
							<?php get_template_part( 'inc/content-post', 'meta-single' ); ?>
						</div>

						<?php 
							the_title( '<h1 class="display-4">', '</h1>' ); 
							get_template_part( 'inc/content-post', 'author' );
						?>

					</div>
				</div>
			</div>
		</div>
	</div>

</section>

<?php else : ?>

<section class="p-0 border-top border-bottom bg-light row no-gutters">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-7 col-lg-8 col-md-10">
				<div class="my-4 my-md-5">

					<div class="single-post-meta d-flex align-items-center mb-3 mb-xl-4">
						<?php get_template_part( 'inc/content-post', 'meta-single' ); ?>
					</div>

					<?php 
						the_title( '<h1 class="display-4">', '</h1>' ); 
						get_template_part( 'inc/content-post', 'author' );
					?>

				</div>
			</div>
		</div>
	</div>
</section>

<?php endif; ?>
	
<?php
	// Standard Post Content
	get_template_part( 'inc/content' );
	
	// Standard Comments and Sharing
	comments_template();
	
	// Related Posts
	get_template_part( 'inc/content-post', 'related' );

	get_template_part( 'inc/footer-cta' ); 
	
	get_footer();