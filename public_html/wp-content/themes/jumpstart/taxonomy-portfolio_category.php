<?php 
	get_header(); 

	get_template_part( 'loop/loop-portfolio', get_theme_mod( 'portfolio_layout', 'featured' ) ); 

	get_template_part( 'inc/portfolio-footer-cta' ); 

	get_footer();