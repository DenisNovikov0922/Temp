<div class="row">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( 'loop/content-team-2-columns' );
				
			endwhile;	
			else : 
				
				get_template_part( 'loop/content', 'none' );
				
			endif;
			
		?>
</div>