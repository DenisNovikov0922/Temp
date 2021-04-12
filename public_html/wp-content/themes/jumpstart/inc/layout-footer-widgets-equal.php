<?php $class = ( is_active_sidebar( 'widgets_footer_1' ) ) ? '' : 'pt-4'; ?>

<footer class="bg-primary-3 text-white links-white pb-4 footer-1 <?php echo esc_attr( $class ); ?>">
  	<div class="container">
  		
  		<?php if( is_active_sidebar( 'widgets_footer_1' ) ) : ?>
  		
	  		<div class="row">
				<?php
					if( is_active_sidebar('widgets_footer_1') && !( is_active_sidebar('widgets_footer_2') ) && !( is_active_sidebar('widgets_footer_3') ) && !( is_active_sidebar('widgets_footer_4') ) ){
						echo '<div class="col-12">';
							dynamic_sidebar('widgets_footer_1');
						echo '</div>';
					}
						
					if( is_active_sidebar('widgets_footer_2') && !( is_active_sidebar('widgets_footer_3') ) && !( is_active_sidebar('widgets_footer_4') ) ){
						echo '<div class="col-sm-6">';
							dynamic_sidebar('widgets_footer_1');
						echo '</div><div class="col-sm-6">';
							dynamic_sidebar('widgets_footer_2');
						echo '</div><div class="clear"></div>';
					}
						
					if( is_active_sidebar('widgets_footer_3') && !( is_active_sidebar('widgets_footer_4') ) ){
						echo '<div class="col-md-4 col-sm-6">';
							dynamic_sidebar('widgets_footer_1');
						echo '</div><div class="col-md-4 col-sm-6">';
							dynamic_sidebar('widgets_footer_2');
						echo '</div><div class="col-md-4 col-sm-6">';
							dynamic_sidebar('widgets_footer_3');
						echo '</div><div class="clear"></div>';
					}
					
					if( ( is_active_sidebar('widgets_footer_4') ) ){
						echo '<div class="col-md-3 col-sm-6">';
							dynamic_sidebar('widgets_footer_1');
						echo '</div><div class="col-md-3 col-sm-6">';
							dynamic_sidebar('widgets_footer_2');
						echo '</div><div class="col-md-3 col-sm-6">';
							dynamic_sidebar('widgets_footer_3');
						echo '</div><div class="col-md-3 col-sm-6">';
							dynamic_sidebar('widgets_footer_4');
						echo '</div><div class="clear"></div>';
					}
				?>
			</div>
	
		    <div class="row">
		      	<div class="col">
		        	<hr>
		      	</div>
		    </div>
		
		<?php endif; ?>

	    <div class="row flex-column flex-lg-row align-items-center justify-content-center justify-content-lg-between text-center text-lg-left">
		  	
	      	<div class="col-auto">
	        	<div class="d-flex flex-column flex-sm-row align-items-center text-small">
	          		<?php get_template_part( 'inc/content', 'footer-copyright' );  ?>
	          	</div>
	        </div>

			<div class="col-auto mt-3 mt-lg-0">
				<?php get_template_part( 'inc/content', 'footer-social' );  ?>
			</div>	
			
		</div>

    </div>
</footer>