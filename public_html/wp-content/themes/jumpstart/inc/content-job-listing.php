<section class="bg-light">
  	<div class="container">
        <div class="row justify-content-center">
          	<div class="col-xl-8 col-lg-9 col-md-11">

          		<a href="<?php echo esc_url( get_permalink( get_option( 'job_manager_jobs_page_id' ) ) ); ?>"><?php esc_html_e( 'Back to Careers', 'jumpstart' ); ?></a>

            	<div class="card card-body shadow mt-4">
			  		
              		<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start pb-4 mb-4 mb-md-5 border-bottom">
						
                		<div class="mb-3 mb-md-0">
                 			<h1 class="mb-2"><?php the_title(); ?></h1>
                  			<div class="lead"><?php the_time( get_option( 'date_format' ) ); ?></div>
                		</div>
						
                		<a href="#apply-form" class="btn btn-primary" data-smooth-scroll>
							<?php esc_html_e( 'Apply Now', 'jumpstart' ); ?>
						</a>
						
              		</div>
			  		
              		<article class="article">
						<?php
							the_content();
							wp_link_pages();
							the_tags( '', '', '' );
						?>
					</article>
					
				</div>
			</div>
		</div>		
	</div>
</section>

<?php if( get_theme_mod( 'careers_form' ) ) : ?>

	<section class="pt-0 bg-light">
	  	<div class="container">
	        <div class="row justify-content-center">
	          	<div class="col-xl-8 col-lg-9 col-md-11">
					
	            	<h2><?php esc_html_e( 'Ready to apply?', 'jumpstart' ); ?></h2>
					
	            	<div class="card card-body shadow mt-md-5" id="apply-form">
	            		<?php echo do_shortcode( get_theme_mod( 'careers_form' ) ); ?>
            		</div>
					
	        	</div>
	    	</div>
		</div>
	</section>
	
<?php endif; ?>