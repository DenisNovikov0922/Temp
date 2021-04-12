<?php $logo = get_post_meta( $post->ID, '_tommusrhodus_portfolio_item_logo_id', 1 ); ?>

<div class="col-md-6 col-lg-4 mb-3 mb-md-4">
	<div class="card h-100 hover-box-shadow">

		<?php if( has_post_thumbnail() ) : ?>
		
			<div class="d-block bg-gradient rounded-top">
				<?php 
					the_post_thumbnail( 'jumpstart-card-top', array( 'class' => 'card-img-top hover-fade-out' ) );
					
		        	if( $logo ) {
			         	 echo '<div class="badge badge-light badge-pill">'. wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-sm m-lg-1' ) ) .'</div>';
			        }
		      	?>
			</div>			
		
		<?php endif; ?>

		<div class="card-body">
			
			<?php 
				the_title( '<h3>', '</h3>' );
				echo wpautop( tommusrhodus_get_excerpt( 20 ) ); 
			?>
			
      		<a href="<?php echo get_permalink(); ?>" class="stretched-link"><?php echo esc_html__( 'Read Story', 'jumpstart' ); ?></a>
	  		
		</div>

	</div>
</div>
