( function( $ ) {
	
	// Blog BG Image
	wp.customize( 'post_archive_background_image', function( value ) {
		value.bind( function( newval ) {
			jQuery( '[data-theme-mod="post_archive_background_image"]' ).attr( 'src', newval ).attr( 'srcset', '' );
		} );
	} );
	
	// Blog Header Text Colour
	wp.customize( 'post_archive_text_colour', function( value ) {
		value.bind( function( newval ) {
			
			if( 'text-white' == newval ){
				jQuery( '[data-theme-mod="post_archive_text_colour"], .blog .breadcrumb' ).addClass( newval );
			} else {
				jQuery( '[data-theme-mod="post_archive_text_colour"], .blog .breadcrumb' ).removeClass( 'text-white' );
			}
			
		} );
	} );

	// Team BG Image
	wp.customize( 'team_archive_background_image', function( value ) {
		value.bind( function( newval ) {
			jQuery( '[data-theme-mod="team_archive_background_image"]' ).attr( 'src', newval ).attr( 'srcset', '' );
		} );
	} );
	
	// Team Header Text Colour
	wp.customize( 'team_archive_text_colour', function( value ) {
		value.bind( function( newval ) {
			
			if( 'text-white' == newval ){
				jQuery( '[data-theme-mod="team_archive_text_colour"], .post-type-archive-team .breadcrumb' ).addClass( newval );
			} else {
				jQuery( '[data-theme-mod="team_archive_text_colour"], .post-type-archive-team .breadcrumb' ).removeClass( 'text-white' );
			}
			
		} );
	} );

	/**
	 * Quick update text options
	 * 
	 * Everything in this array is text updates that happen instantaneously.
	 * 
	 * @key Theme mod ID
	 * @value Target CSS
	 */
	var $textUpdateArray = {
		'post_archive_title'         : '[data-theme-mod="post_archive_title"]',
		'post_archive_subtitle'      : '[data-theme-mod="post_archive_subtitle"]',
		'portfolio_archive_title'    : '[data-theme-mod="portfolio_archive_title"]',
		'portfolio_archive_subtitle' : '[data-theme-mod="portfolio_archive_subtitle"]',
		'team_archive_title'    	 : '[data-theme-mod="team_archive_title"]',
		'team_archive_subtitle' 	 : '[data-theme-mod="team_archive_subtitle"]',
	}
	
	jQuery.each( $textUpdateArray, function( theme_mod, css_class ) {
		wp.customize( theme_mod, function( value ) {
			value.bind( function( newval ) {
				jQuery( css_class ).html( newval );
			} );
		} );
	} );
	
	/**
	 * Quick show / hide items
	 * 
	 * Everything in this array needs to be able to easily be shown / hidden by adding a display none class.
	 * 
	 * @key Theme mod ID
	 * @value Target CSS
	 */
	var $showHideArray = {
		'show_documentation_single_breadcrumbs' : '.single-documentation .breadcrumb',
		'show_documentation_single_meta'        : '[data-theme-mod="show_documentation_single_meta"]',
		'show_documentation_single_cats'        : '[data-theme-mod="show_documentation_single_cats"]',
		'show_documentation_single_voting'      : '[data-theme-mod="show_documentation_single_voting"]',
		'show_post_archive_breadcrumbs'         : '.blog .breadcrumb',
		'show_post_archive_author'              : '[data-theme-mod="show_post_archive_author"]',
		'show_post_single_breadcrumbs'          : '[data-theme-mod="show_post_single_breadcrumbs"]',
		'show_post_single_sharing'              : '[data-theme-mod="show_post_single_sharing"]',
		'show_post_single_author'               : '[data-theme-mod="show_post_single_author"]',
		'show_page_breadcrumbs'                 : '[data-theme-mod="show_page_breadcrumbs"]',
		'show_page_sharing'                     : '[data-theme-mod="show_page_sharing"]',
		'show_portfolio_archive_breadcrumbs'    : '.post-type-archive-portfolio .breadcrumb',		
		'show_header_search'    				: '[data-target="#searchCollapse"], .top-bar-searchform',		
		'show_team_archive_breadcrumbs'    		: '.post-type-archive-team .breadcrumb',
	};
	
	jQuery.each( $showHideArray, function( theme_mod, css_class ) {
		wp.customize( theme_mod, function( value ) {
			value.bind( function( newval ) {
				( 'yes' == newval || true == newval ) ? jQuery( css_class ).removeClass( 'd-none' ) : jQuery( css_class ).addClass( 'd-none' );
			} );
		} );
	} );
	
} )( jQuery );