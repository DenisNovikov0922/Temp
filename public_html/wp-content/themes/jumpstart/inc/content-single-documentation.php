<section class="bg-light">
	<div class="container">
		<div class="row justify-content-between align-items-start">

          	<div class="col-lg-8">

          		<div class="d-flex">
          			<?php echo get_tommusrhodus_breadcrumbs(); ?>
      			</div>
		
				<?php the_title( '<h2 class="h1 mt-4">', '</h2>' ); ?>

				<div class="lead"><?php the_excerpt(); ?></div>

				<div class="d-flex align-items-center mt-4 mt-md-5">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 48, false, false, array( 'class' => 'avatar avatar-sm' ) ); ?>
	              	<div class="text-small ml-3">
	               	 	<div><?php esc_html_e( 'Written by', 'jumpstart' ); ?> <?php echo get_the_author(); ?> </div>
	                	<div class="text-muted"><?php echo esc_html__( 'Last updated ', 'jumpstart' ) . human_time_diff( get_the_modified_date( 'U', get_the_id() ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'jumpstart' ); ?></div>
	              	</div>
	            </div>

				<hr class="my-md-4 my-lg-5">
				
				<div class="row">
					<div class="col-xl-10">
						
						<article class="article">
							<?php
								the_content();
								wp_link_pages();							
								the_tags( '', '', '' );
							?>
						</article>

						<?php comments_template( '/comments-inline.php' ); ?> 

					</div>
				</div>

			</div>

			<?php get_sidebar( 'documentation' ); ?>

		</div>		
	</div>
</section>