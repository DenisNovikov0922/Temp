<div class="row">
	<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'loop/content', 'portfolio-card' );

		endwhile;	
		endif;
		
		rewind_posts();
	?>
</div>

<?php get_template_part( 'inc/content', 'pagination' ); ?>