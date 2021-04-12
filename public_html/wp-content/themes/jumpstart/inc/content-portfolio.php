
<section>
	<div class="container">
		<div class="row align-items-start justify-content-around">
			
			<?php get_template_part( 'inc/content-portfolio', 'meta' ); ?>

			<div class="col-xl-7 col-lg-8 col-md-9">
            	<article class="article">
		
					<?php
						the_content();
						wp_link_pages();
						the_tags( '', '', '' );
					?>

				</article>
			</div>
		</div>		
	</div>
</section>