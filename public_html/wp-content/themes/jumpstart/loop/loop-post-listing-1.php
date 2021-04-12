<?php
	$i      = 0;
	$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset = ( 1 == $paged ) ? 4 : 0;
?>

<?php if( 1 == $paged ) : ?>

	<section class="bg-light py-5">
		<div class="container">
			
			<div class="row">
				<div class="col">
					<?php 
						if ( have_posts() ) : while ( have_posts() ) : the_post();

							get_template_part( 'loop/content', 'post-featured' );

						break;
						endwhile;	
						endif;
						
						rewind_posts();
					?>
				</div>
			</div>

			<div class="row mt-3 mt-lg-5">
				<?php 
					if ( have_posts() ) : while ( have_posts() ) : the_post();

						$i++;
	
						if( $i >= 2 && $i <= 4 ) {
							get_template_part( 'loop/content', 'post-featured-small' );
						}								

					endwhile;	
					endif;
					
					rewind_posts();
				?>
			</div>
			
		</div>
	</section>
	
<?php endif; ?>

<section>
  	<div class="container">
		
        <div class="row">
			<?php 
				$i = 0;
				
				if ( have_posts() ) : while ( have_posts() ) : the_post();

				if( $i >= $offset ) {
					get_template_part( 'loop/content', 'post-card' );
				} 
				
				$i++;

				endwhile;	
				endif;
			?>
    	</div>

    	<?php get_template_part( 'inc/content', 'pagination' ); ?>
    	
	</div>
</section>