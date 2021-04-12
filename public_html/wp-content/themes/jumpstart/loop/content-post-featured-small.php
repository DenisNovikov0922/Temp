<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-lg-4 my-2 my-md-3 my-lg-0' ); ?>>
	<div class="row">
	
		<?php if( has_post_thumbnail() ) : ?>
		
			<a href="<?php the_permalink(); ?>" class="col-5">
				<?php the_post_thumbnail( 'large', array( 'class' => 'rounded img-fluid hover-fade-out' ) ); ?>
				<div class="divider divider-side bg-white d-none d-lg-block"></div>
			</a>
		
		<?php endif; ?>
		
		<div class="col">
			<?php the_title( '<a class="h6" href="'. get_permalink() .'">', '</a>' ); ?>
			<div class="text-small text-muted mt-2"><?php the_time( get_option( 'date_format' ) ); ?></div>
		</div>
	
	</div>
</div>