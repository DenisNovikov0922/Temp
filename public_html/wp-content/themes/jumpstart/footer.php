<?php 
	get_template_part( 'inc/content', 'side-menu' );

	/**
	 * Get footer layout by theme option
	 * Overrides handled by theme_filters by pre-filtering the theme_mod call
	 * 
	 * Elementor Pro Compatibility
	 * 
	 * Checks first that Elementor Pro is functioning, 
	 * then checks if a custom footer has been set.
	 * 
	 * If neither of these are true, we fall back to the default theme footer.
	 */
	if ( !function_exists( 'elementor_theme_do_location' ) || !elementor_theme_do_location( 'footer' ) ) {
		get_template_part( 'inc/layout-footer', get_theme_mod( 'footer_layout', 'widgets' ) ); 	        
	}  

	/**
	 * Include the back to top button markup
	 */
	get_template_part( 'inc/content', 'back-to-top' ); 

	wp_footer(); 
?>
</body>
</html>