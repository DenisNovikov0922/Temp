function rm_woocommerce_form_layer(){ 
	jQuery('.woocommerce form').attr('enctype','multipart/form-data');
	jQuery('.woocommerce form').attr('encoding', 'multipart/form-data');
	if(jQuery('.woocommerce form').hasClass('checkout')){ 
                setTimeout(function(){
                    jQuery( ".woocommerce form.checkout").unbind( "submit" );
                },1000);

	}
        if(jQuery('.woocommerce form').hasClass('register')){ 
                setTimeout(function(){
                    jQuery( ".woocommerce form.register").unbind( "submit" );
                },1000);

	}
}

var rmColor;
jQuery(document).ready(function(){
    rmColor = jQuery(".rm-anchor-control").css("color");
});