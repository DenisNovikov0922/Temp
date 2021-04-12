<?php 

function tommusrhodus_login_header_markup(){
    
    $output = '<section class="min-vh-100 py-2">';
    
    if( $image = get_theme_mod( 'login_background_image' ) ){
    	
    	$output = '
			<section class="row no-gutters p-0 bg-light min-vh-100">
				<div class="col-lg-6 d-none d-lg-block order-lg-2">
		        	<div class="divider divider-side bg-light transform-flip-y d-none d-lg-block"></div>
		        	<img class="flex-fill" src="'. esc_url( $image ) .'" alt="Image">
		      	</div>
				<div class="col-lg-6">
        			<section class="p-0">
          				<div class="container min-vh-lg-100 d-flex flex-column justify-content-between text-center py-4 py-md-5">
	            			<div class="row justify-content-center my-5">
	              				<div class="col-xl-7 col-lg-8">
    	';
    	
    }
    
	echo ( false == $output ) ? false : $output;
	
}
add_action( 'login_header', 'tommusrhodus_login_header_markup', 10 );

function tommusrhodus_login_footer_markup(){

	$output = '</section>';
	
	if( $image = get_theme_mod( 'login_background_image' ) ){		
		$output = '</div></div></div></div></section>';
	}
	
	echo ( false == $output ) ? false : $output;
	
}
add_action( 'login_footer', 'tommusrhodus_login_footer_markup', 10 );