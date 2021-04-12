jQuery(function ($) {
	
	$(window).on('load', function() {
	
		$(document).ready(function() {
		
			/*****************************************************************/
			/* RESIZE + ON LOAD WRAPPER */
			/*****************************************************************/

			// run test on initial page load
			$(window).on('load', function() {
				check();
			});

			// run test on resize an scroll of the window
			$(window).resize(check);
			$(window).scroll(check);

			check();


			/*****************************************************************/
			/* WP ADMIN MENU CHECK */
			/*****************************************************************/

			var wpStickyMenu;

			function check() {
				if( $('body.sticky-menu').length ) {
					// Default Menu
					wpStickyMenu = true;
				} else {
					// Mobile Menu
					wpStickyMenu = false;
				}
			}


			/*****************************************************************/
			/* BODY SPACER */
			/*****************************************************************/

			//$( 'body.wp-admin' ).wrapInner( '<div class="body-spacer">' );			
			
			var leftMenuHeight = $( '#adminmenu' ).outerHeight();
			$(leftMenuHeight).on("click", function(e) {
				leftMenuHeight = $(this).outerHeight();
			});
			
			if( wpStickyMenu === true ) {
				$( '.wpat.wpat-spacing-on .body-spacer' ).css( 'min-height', leftMenuHeight + 40 );
			}

			/*****************************************************************/
			/* ADD SUBSUBSUB REPLACE */
			/*****************************************************************/

			$('.subsubsub a .count').each(function() {
				var newValue = $(this).text().replace('(', '').replace(')', '');
				$(this).text( newValue );
			});

			$('.subsubsub a .count').fadeOut();
			$('.subsubsub a .count').fadeIn();


			/*****************************************************************/
			/* REORDER FIRST MENU ITEM */
			/*****************************************************************/

			// Avoiding flickering to reorder the first menu item (User Box) for left toolbar
			if( $("#adminmenu li:first-child").hasClass('adminmenu-container') ) {
				// nothing	
			} else {
				$("li.adminmenu-container").prependTo("#adminmenu");
				$("#adminmenu li.menu-top-first:first-child").show();
			}


			/*****************************************************************/
			/* WRAP LEFT WP MENU IMAGES */
			/*****************************************************************/

			$('#adminmenu .wp-menu-image img').wrap( '<span class="wp-menu-img-wrap"></span>' );	


			/*****************************************************************/
			/* GET RANGE INPUT FIELD VALUE */
			/*****************************************************************/

			// Send the range field value to the next element content
			$('.wpat-range-value').change( function() {

				var linkText = $(this).val();
				$(this).next().find('span').html(linkText);
				return false;                    

			}).change();
			

			/*****************************************************************/
			/* WP LEFT ADMIN MENU - EXPANDAPLE */
			/*****************************************************************/	
			
			$('.wpat-menu-left-expand #adminmenu > li.wp-first-item').addClass('wp-has-submenu');

			$('.wpat-menu-left-expand #adminmenu > li.wp-has-submenu.wp-not-current-submenu').each(function() {

				var SubMenuStartHeight = $(this).find('.wp-submenu').height();
				
				$('.wpat-menu-left-expand #collapse-button').on("click", function() {				
					$('.wpat-menu-left-expand #adminmenu > li.wp-has-submenu.wp-not-current-submenu').each(function() {
						$(this).find('.wp-submenu').css('height', 'auto');
						SubMenuStartHeight = $(this).find('.wp-submenu').height();
					});
				});

				// Set sub menu height to auto
				$(this).find('.wp-submenu').css('height', 'auto');

				// Expand sub menu on click
				$(this).find('a.menu-top, a.menu-top-frist').on("click", function(e) {

					if( $(this).hasClass('expanded') ) {

						// Close the active sub menu on click				
						$(this).next('.wp-submenu').hide(100);

						$(this).next('.wp-submenu').css('height', 'auto');

					} else {				

						// Close all other sub menus
						$('.wp-has-submenu.wp-not-current-submenu .wp-submenu').hide(100);

						$('.wp-has-submenu.wp-not-current-submenu .wp-submenu').css('height', 'auto');

						$('.wp-has-submenu.wp-not-current-submenu').each(function() {
							if( $(this).hasClass('expanded') ) {
								$(this).toggleClass('expanded');
							}
						});				

						// Expand the active sub menu on click
						$(this).next('.wp-submenu').show(100);

						$(this).next('.wp-submenu').css('height', 'auto');

					}

					$(this).toggleClass('expanded');

					e.stopPropagation();
					e.preventDefault();

				});

			});

		});

	});
	
});