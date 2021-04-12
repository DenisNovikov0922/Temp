<?php $logo = get_post_meta( $post->ID, '_tommusrhodus_portfolio_item_logo_id', 1 ); ?>

<section class="p-0 border-top border-bottom bg-light row no-gutters">

	<div class="col-lg-5 col-xl-6 order-lg-2">
		<div class="divider divider-side transform-flip-y bg-light d-none d-lg-block"></div>
		<?php the_post_thumbnail( 'full', array( 'class' => 'flex-fill' ) ); ?>
	</div>

	<div class="col-lg-7 col-xl-6">
		<div class="container min-vh-lg-70 d-flex align-items-center">
			<div class="row justify-content-center">
				<div class="col col-md-10 col-xl-9 py-4 py-sm-5">
					<div class="my-4 my-md-5 my-lg-0 my-xl-5">
						
						<?php 
					        if( $logo ){
					          	echo wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon' ) );
					        }
					        
				      		the_title( '<h1 class="display-4 mt-4 mt-lg-5">', '</h1>' ); 
				      	?>
					  	
						<p class="lead">
							<?php echo tommusrhodus_get_excerpt( 20 ); ?>
						</p>
						
					</div>
				</div>
			</div>
		</div>
	</div>

</section>