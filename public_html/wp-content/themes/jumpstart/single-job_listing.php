<?php
	get_header();
	the_post();
	
	// Standard Post Content
	get_template_part( 'inc/content-job-listing' );
	
	get_footer();