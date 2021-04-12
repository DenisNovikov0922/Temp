<?php 
	$categories = get_the_category();

	if( is_array( $categories ) ){
		foreach( $categories as $cat ){
			echo '<a href="'. esc_url( get_category_link( $cat->cat_ID ) ) .'" class="mr-2 mb-2 post-category badge badge-pill badge-danger '. $cat->slug . '-custom-color">'. $cat->name . '</a>';		
		}
	}
?>

<div class="text-small text-muted mb-2"><?php the_time( get_option( 'date_format' ) ); ?></div>