<section class="bg-primary-3 text-white">
  	<div class="container">

        <div class="row section-title justify-content-center text-center">
      		<div class="col-md-9 col-lg-8 col-xl-7">
            	<h1 class="display-3"><?php echo get_theme_mod( 'blog_layout_listing_3title', 'Newsroom' ); ?></h1>
            	<div class="lead"><?php echo get_theme_mod( 'blog_layout_listing_3_subtitle', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.' ); ?></div>
         	 </div>
        </div>

        <div class="row justify-content-center">
          	<?php 
				if ( have_posts() ) : while ( have_posts() ) : the_post();

					get_template_part( 'loop/content-post-listing-3' );
					
				endwhile;	
				else : 
					
					get_template_part( 'loop/content', 'none' );
					
				endif;
			?>         
        </div>

        <?php get_template_part( 'inc/content', 'pagination' ); ?>

	</div>
</section>	