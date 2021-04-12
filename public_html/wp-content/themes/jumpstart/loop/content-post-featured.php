<div id="post-<?php the_ID(); ?>" <?php post_class( 'd-flex flex-column flex-lg-row no-gutters border rounded bg-white o-hidden' ); ?>>

	<?php if( has_post_thumbnail() ) : ?>
	
		<a href="<?php the_permalink(); ?>" class="d-block position-relative bg-gradient col-xl-5">
			<?php the_post_thumbnail( 'large', array( 'class' => 'flex-fill hover-fade-out' ) ); ?>
			<div class="divider divider-side bg-white d-none d-lg-block"></div>
		</a>
	
	<?php endif; ?>
	
	<div class="p-4 p-md-5 col-xl-7 d-flex align-items-center">
		<div class="p-lg-4 p-xl-5">
			
			<div class="d-flex justify-content-between align-items-center mb-3 mb-xl-4">
				<?php get_template_part( 'inc/content-post', 'meta' ); ?>
			</div>
			
			<?php the_title( '<a href="'. get_permalink() .'"><h1>', '</h1></a>' ); ?>
			
			<p class="lead">
				<?php echo tommusrhodus_get_excerpt( 20 ); ?>
			</p>
			
			<a href="<?php echo get_permalink(); ?>" class="lead"><?php esc_html_e( 'Read Story', 'jumpstart' ); ?></a>
			
		</div>
	</div>

</div>