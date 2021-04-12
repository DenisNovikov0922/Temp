<?php
	get_header();
	the_post();
	
	// Standard Post Content
	get_template_part( 'inc/content-page' );
	
	// Standard Comments
	comments_template();
	
	get_footer();