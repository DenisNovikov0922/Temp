<?php 
	get_header(); 
	
	if( 'yes' == get_theme_mod( 'blog_hero', 'yes' ) ){
		get_template_part( 'inc/content-documentation', 'hero' );
	}

	get_template_part( 'loop/loop-post', get_theme_mod( 'blog_layout', 'listing-2' ) ); 

	get_template_part( 'inc/footer-cta' ); 

	get_footer();