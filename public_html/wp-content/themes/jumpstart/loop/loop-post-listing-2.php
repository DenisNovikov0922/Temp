<section class="bg-light">
	<div class="container">
		<div class="row">
		 	<div class="col">
				<?php 
					if ( have_posts() ) : while ( have_posts() ) : the_post();

						get_template_part( 'loop/content-post-listing-2' );
						
					endwhile;	
					else : 
						
						get_template_part( 'loop/content', 'none' );
						
					endif;
					
					get_template_part( 'inc/content', 'pagination' );
				?>
			</div>
		</div>
	</div>
</section>