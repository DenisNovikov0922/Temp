<div class="row justify-content-center">
  	<div class="col">
			<ul class="avatar-group avatar-group-lg flex-wrap justify-content-center">
				
			<?php 
				if ( have_posts() ) : while ( have_posts() ) : the_post();

					get_template_part( 'loop/content-team', 'inline' );
					
				endwhile;	
				else : 
					
					get_template_part( 'loop/content', 'none' );
					
				endif;
			
			?>
			
		</ul>
	</div>
</div>