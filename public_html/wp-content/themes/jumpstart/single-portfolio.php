<?php
	get_header();
	the_post();
	
	// Posts Specific Hero Area with Option Switch
	get_template_part( 'inc/content-portfolio-single-hero', 'basic' );
	
	// Standard Post Content
	get_template_part( 'inc/content-portfolio' );

	// Related Posts
	get_template_part( 'inc/content-portfolio', 'related' );

	get_template_part( 'inc/portfolio-footer-cta' ); 
		
	get_footer();