<section class="bg-light">
  	<div class="container">
        <div class="row justify-content-center">
          	<div class="col-xl-8 col-lg-9 col-md-11">
            	<div class="card card-body shadow">
			  		
              		<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start pb-4 mb-4 mb-md-5 border-bottom">
                		<div class="mb-3 mb-md-0">
                 			<h1 class="mb-2"><?php the_title(); ?></h1>
                		</div>
              		</div>
			  		
              		<article class="article">
						<?php
							the_content();
							wp_link_pages();
							the_tags( '', '', '' );
						?>
					</article>

					<?php
						if( $small_print = get_post_meta( $post->ID, '_tommusrhodus_page_small_print', 1 ) ) {
							echo '<hr><h6>'. esc_html__( 'Fine Print', 'jumpstart' ) .'</h6><small>'. wp_kses_post( $small_print ) .'</small>';
						}
					?>
					
				</div>
			</div>
		</div>		
	</div>
</section>