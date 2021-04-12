<?php $one_item_only = ( isset( $wp_query->is_single_post_item ) && ( 'yes' == $wp_query->is_single_post_item ) ) ? 'one-post-only' : false; ?>

<section class="bg-light py-5">
	<div class="container">
		<div class="row justify-content-center <?php echo esc_attr( $one_item_only ); ?>">
		  	<?php 
				if ( have_posts() ) : while ( have_posts() ) : the_post();
		
					get_template_part( 'loop/content', 'post-card' );
					
				endwhile;	
				else : 
					
					get_template_part( 'loop/content', 'none' );
					
				endif;
			?>         
		</div>
	</div>
</section>

<?php get_template_part( 'inc/content', 'pagination' );