/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

 var style, el, menu_bar_background_color, cart_icon, menu_text_color, menu_text_hover_color,menu_text_active_color,menu_background_color,menu_background_hover_color,menu_background_active_color, footer_text_color,footer_background_color, widget_text_color, widget_background_color, accordion_text_color, accordion_background_color, button_background_color, button_text_color;



	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	//Site Logo 
	wp.customize( 'woofood_logo', function( value ) {
		value.bind( function( to ) {
			$( '.brand-name img' ).text( to );
		} );
	} );

    wp.customize( 'woofood_header_cart_icon_selected', function( value ) {
        value.bind( function( to ) {

                         cart_icon  = wp.customize( 'woofood_header_cart_icon_selected' )();
            $( '.header-cart .cart-icon i' ).removeClass();
            $( '.header-cart-center .cart-icon i' ).removeClass();

            $( '.header-cart .cart-icon i' ).addClass( cart_icon );
                        $( '.header-cart-center .cart-icon i' ).addClass( cart_icon );
                            jQuery( document.body ).trigger( 'wc_fragment_refresh' );



        } );
    } );




	//Menu Bar Background Color
	wp.customize( 'woofood_menu_bar_background_color', function( value ) {
		value.bind( function( to ) {
		
			 menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


               

                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);



                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
		} );
	} );

	//Menu Text Color
	wp.customize( 'woofood_menu_text_color', function( value ) {
		value.bind( function( to ) {



		
			  menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
		} );
	} );


	//Menu Text Hover Color
	wp.customize( 'woofood_menu_text_hover_color', function( value ) {
            value.bind( function( to ) {
 
               menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();




                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
            } );
        } );
    

	//Menu Text Active Color
	wp.customize( 'woofood_menu_text_active_color', function( value ) {
		value.bind( function( to ) {
		
menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
		} );
	} );

	//Menu Background Color
	wp.customize( 'woofood_menu_background_color', function( value ) {
		value.bind( function( to ) {
		
			 menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }

		} );
	} );


	//Menu Background Hover Color
	wp.customize( 'woofood_menu_background_hover_color', function( value ) {
            value.bind( function( to ) {
            	
             menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();



                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
            } );
        } );


	//Menu Background Active Color
	wp.customize( 'woofood_menu_background_active_color', function( value ) {
		value.bind( function( to ) {
		

menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
		} );
	} );



        //Footer Text Color
    wp.customize( 'woofood_footer_text_color', function( value ) {
        value.bind( function( to ) {
        

menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
        } );
    } );

     //Footer Background Color
    wp.customize( 'woofood_footer_background_color', function( value ) {
        value.bind( function( to ) {
        

menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();


                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();



                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
        } );
    } );


     //Widget Text Color
    wp.customize( 'woofood_widget_text_color', function( value ) {
        value.bind( function( to ) {
        

menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();



                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
        } );
    } );



     //Widget Background Color
    wp.customize( 'woofood_widget_background_color', function( value ) {
        value.bind( function( to ) {
        

menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();


                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
        } );
    } );


//Footer Left
    wp.customize( 'woofood_footer_left', function( value ) {
        value.bind( function( to ) {

            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           
        } );
    } );


    //Footer Right
    wp.customize( 'woofood_footer_right', function( value ) {
        value.bind( function( to ) {
            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           

        } );
    } );



//Accordion Text Color//
    wp.customize( 'woofood_accordion_text_color', function( value ) {
        value.bind( function( to ) {
            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           

        } );
    } );

//Accordion Text Color//



//Accordion Background Color//
    wp.customize( 'woofood_accordion_background_color', function( value ) {
        value.bind( function( to ) {
            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           

        } );
    } );

//Accordion Background Color//


//Button Background Color//
    wp.customize( 'woofood_button_background_color', function( value ) {
        value.bind( function( to ) {
            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           

        } );
    } );

//Button Background Color//



//Button Text Color//
    wp.customize( 'woofood_button_text_color', function( value ) {
        value.bind( function( to ) {
            menu_bar_background_color  = wp.customize( 'woofood_menu_bar_background_color' )();

                menu_text_color  = wp.customize( 'woofood_menu_text_color' )();
                menu_text_hover_color  = wp.customize( 'woofood_menu_text_hover_color' )();
                menu_text_active_color  = wp.customize( 'woofood_menu_text_active_color' )();

                menu_background_color  = wp.customize( 'woofood_menu_background_color' )();
                menu_background_hover_color  = wp.customize( 'woofood_menu_background_hover_color' )();
                menu_background_active_color  = wp.customize( 'woofood_menu_background_active_color' )();
                footer_text_color = wp.customize('woofood_footer_text_color')();
                footer_background_color = wp.customize('woofood_footer_background_color')();
                widget_text_color = wp.customize('woofood_widget_text_color')();
                widget_background_color = wp.customize('woofood_widget_background_color')();

                 accordion_text_color = wp.customize('woofood_accordion_text_color')();
                accordion_background_color = wp.customize('woofood_accordion_background_color')();

                 button_text_color = wp.customize('woofood_button_text_color')();
                button_background_color = wp.customize('woofood_button_background_color')();

                
                footer_left = wp.customize('woofood_footer_left')();
                footer_right = wp.customize('woofood_footer_right')();
                $( '.footer-bottom-left' ).html( footer_left);
                $( '.footer-bottom-right' ).html( footer_right);


                style='<style class="hover-styles" type="text/css">.header{background: '  +menu_bar_background_color + '!important; border-color: '+ menu_bar_background_color + '!important; }.header .navbar-nav>li>a {color: ' + menu_text_color +'!important; background: ' + menu_background_color +'!important; }navbar-toggler .navbar-toggler-icon, .navbar-toggler .navbar-toggler-icon::after, .navbar-toggler .navbar-toggler-icon::before{ background:' + menu_text_color +'!important;}.header .navbar-nav>li>a:focus, .navbar-default .navbar-nav>li>a:hover{ color: ' + menu_text_hover_color +'!important; background: ' + menu_background_hover_color +'!important; }.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover{color: ' + menu_text_active_color +'!important; background: ' + menu_background_active_color +'!important; } .footer{color: ' + footer_text_color +'!important; background: ' + footer_background_color +'!important; } .widget-title{color: ' + widget_text_color +'!important; background: ' + widget_background_color +'!important; }.add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.single_add_to_cart_button{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.checkout-button.button.alt.wc-forward{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woocommerce #payment #place_order, .woocommerce-page #payment #place_order{color: ' + button_text_color +'!important; background-color: ' + button_background_color +'!important;}.woofood-accordion .panel-heading{background: ' + accordion_background_color +'!important; color: ' + accordion_text_color +'!important;}</style>';
                 el =  $( '.hover-styles' ); // look for a matching style element that might already be there
 
                // add the style element into the DOM or replace the matching style element that is already there
                if ( el.length ) {
                    el.replaceWith( style ); // style element already exists, so replace it
                } else {
                    $( 'head' ).append( style ); // style element doesn't exist so add it
                }
           

        } );
    } );

//Button Text Color//



	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );



  

} )( jQuery );









