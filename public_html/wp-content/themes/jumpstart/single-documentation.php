<?php
	get_header();
	the_post();
	
	// Posts Specific Hero Area with Option Switch
	get_template_part( 'inc/content-documentation', 'hero' );
	
	// Post Content
	get_template_part( 'inc/content-single-documentation' );
	
	get_footer();