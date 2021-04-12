<section class="pb-0">
  	<div class="container">
        <div class="row justify-content-center">
          	<div class="col-xl-7 col-lg-8 col-md-10">
            	<article class="article">
					<?php
						the_content();
						wp_link_pages();
						the_tags( '', '', '' );
					?>
				</article>
				
				<div class="mt-4 mt-sm-5 border-top pt-5 d-none d-sm-block">
					
					<?php 
						echo get_tommusrhodus_breadcrumbs();
					?>

				</div>
			</div>
		</div>		
	</div>
</section>