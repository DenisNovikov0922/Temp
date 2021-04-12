<?php
	$i      = 0;
	$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset = ( 1 == $paged ) ? 5 : 0;
?>

<section class="bg-light py-5">
	<div class="container">
		<div class="row">
			<div class="col">

				<?php if( 1 == $paged ) : ?>

					<?php 
						if ( have_posts() ) : while ( have_posts() ) : the_post();

							get_template_part( 'loop/content', 'portfolio-featured' );
	
							break;
						endwhile;	
						endif;
						
						rewind_posts();
					?>

				<?php endif; ?>

			</div>
		</div>

		<div class="row mt-3 mt-lg-5">
			<?php 
				if ( have_posts() ) : while ( have_posts() ) : the_post();

					$i++;
	
					if( $i >= 2 && $i <= 3 ) {
						get_template_part( 'loop/content', 'portfolio-featured-small' );
					}								

				endwhile;	
				endif;
				
				rewind_posts();
			?>
		</div>
		
	</div>
</section>

<section class="pb-0">
  	<div class="container">
		
        <div class="row">
			<?php 
				$i = 1;
				if ( have_posts() ) : while ( have_posts() ) : the_post();

				if( $i >= 4 ) {
					get_template_part( 'loop/content', 'portfolio-card' );
				}

				$i++;

				endwhile;	
				endif;
				
				rewind_posts();
			?>
    	</div>

    	<?php get_template_part( 'inc/content', 'pagination' ); ?>
    	
	</div>
</section>