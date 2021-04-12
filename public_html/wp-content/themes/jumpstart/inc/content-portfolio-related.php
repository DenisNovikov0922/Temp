<?php 
  if(!( 'portfolio' == get_post_type() )){
    return false;
  }
  $terms = get_the_terms( $post->ID , 'portfolio_category', 'string' );
  
  if(!( is_array( $terms ) )){
    return false;
  }
  
  $term_ids = array_values( wp_list_pluck( $terms, 'term_id' ) );
  
  $related_query = new WP_Query( 
    array(
      'post_type' => 'portfolio',
      'tax_query' => array(
        array(
          'taxonomy' => 'portfolio_category',
          'field'    => 'id',
          'terms'    => $term_ids,
          'operator' => 'IN' //Or 'AND' or 'NOT IN'
        )
      ),
      'posts_per_page'      => 3,
      'ignore_sticky_posts' => 1,
      'orderby'             => 'rand',
      'post__not_in'        => array( $post->ID )
    ) 
  );
  
  if( $related_query->found_posts == 0 ){
    return false;
  }
?> 

<section class="bg-light">
	<div class="container">
	
		<div class="row section-title justify-content-center text-center">
			<div class="col-md-9 col-lg-8 col-xl-7">
				<h3 class="h1"><?php esc_html_e( 'They did it with Jumpstart', 'jumpstart' ); ?></h3>
			</div>
		</div>
		
		<div class="row">
			<?php 
				if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post();
				
					get_template_part( 'loop/content-portfolio-card' );
				
				endwhile; 
				else : 
				
					/**
					* Display no posts message if none are found.
					*/
					get_template_part( 'loop/content', 'none' );
				
				endif;
				wp_reset_postdata();
			?>
		</div>
	
	</div>
</section>