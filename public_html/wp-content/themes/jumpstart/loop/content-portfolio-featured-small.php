<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'col-lg-6 my-3 my-lg-0' ); ?>>
	<div class="row flex-column flex-sm-row align-items-center">
	
		<?php if( has_post_thumbnail() ) : ?>
		
			<a href="<?php the_permalink(); ?>" class="col-sm-5 mb-4 mb-sm-0">
				<?php the_post_thumbnail( 'large', array( 'class' => 'rounded img-fluid hover-fade-out' ) ); ?>
			</a>
		
		<?php endif; ?>
		
		<div class="col">
			
			<?php 
				if( $logo = get_post_meta( $post->ID, '_tommusrhodus_portfolio_item_logo_id', 1 ) ){
				  echo '<div class="mb-4">'. wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-sm' ) ) .'</div>';
				}
			?>
			
			<div>
				<?php the_title( '<a class="h4" href="'. get_permalink() .'">', '</a>' ); ?>
			</div>
			
		</div>
	
	</div>
</div>
