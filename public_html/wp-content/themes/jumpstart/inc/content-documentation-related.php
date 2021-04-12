<?php 
  if(!( 'documentation' == get_post_type() )){
    return false;
  }
  $terms = get_the_terms( $post->ID , 'documentation_category', 'string' );
  
  if(!( is_array( $terms ) )){
    return false;
  }
  
  $term_ids = array_values( wp_list_pluck( $terms, 'term_id' ) );
  
  $related_query = new WP_Query( 
    array(
      'post_type' => 'documentation',
      'tax_query' => array(
        array(
          'taxonomy' => 'documentation_category',
          'field'    => 'id',
          'terms'    => $term_ids,
          'operator' => 'IN' //Or 'AND' or 'NOT IN'
        )
      ),
      'posts_per_page'      => 2,
      'ignore_sticky_posts' => 1,
      'orderby'             => 'rand',
      'post__not_in'        => array( $post->ID )
    ) 
  );
  
  if( $related_query->found_posts == 0 ){
    return false;
  }
?> 

<div class="list-group mb-3 mb-md-4">
  <div class="list-group-item">
    <h6 class="mb-0"><?php esc_html_e( 'Similar Articles', 'jumpstart' ); ?></h6>
  </div>
  <?php 

    if ( $related_query->have_posts() ) : while ( $related_query->have_posts() ) : $related_query->the_post(); ?>

      <div class="list-group-item">
        <?php the_title( '<a href="'. get_permalink() .'">', '</a>' ); ?>
        <div class="text-small"><?php the_excerpt(); ?></div>
      </div>

    <?php
    endwhile; 
    endif;
    wp_reset_postdata();

  ?>

</div>