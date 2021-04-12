<?php 
	$term = false;
	
	if( $yoast_cat = get_post_meta( get_the_id(), '_yoast_wpseo_primary_category', true ) ){
		
		$term = get_term( $yoast_cat, 'category' );
		
	} else {
	
		$categories = get_the_category();
	
		if( is_array( $categories ) ){
			$term = $categories[0];
		}
	
	}
	
	if( is_object( $term ) ){
		echo '<a href="'. esc_url( get_category_link( $term->cat_ID ) ) .'" class="mr-2 post-category badge badge-pill badge-danger '. $term->slug . '-custom-color">'. $term->name . '</a>';
	}
?>

<div class="text-small text-muted"><?php the_time( get_option( 'date_format' ) ); ?></div>