<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-md-6 col-lg-4 mb-3 mb-md-4' ); ?>>
	<div class="card h-100 hover-box-shadow">

		<?php if( has_post_thumbnail() ) : ?>
		
			<a href="<?php the_permalink(); ?>" class="d-block bg-gradient rounded-top">
				<?php the_post_thumbnail( 'jumpstart-card-top', array( 'class' => 'card-img-top hover-fade-out rounded-top' ) ); ?>
			</a>
		
		<?php endif; ?>

		<div class="card-body">
			
			<?php the_title( '<a href="'. get_permalink() .'"><h3>', '</h3></a>' ); ?>
				
			<p><?php echo tommusrhodus_get_excerpt( 20 ); ?></p>

      		<a href="<?php echo get_permalink(); ?>"><?php echo esc_html__( 'Read Story', 'jumpstart' ); ?></a>
	  		
		</div>

		<div class="card-footer d-flex justify-content-between align-items-center">
			<?php get_template_part( 'inc/content-post', 'meta' ); ?>
		</div>

	</div>
</div>
