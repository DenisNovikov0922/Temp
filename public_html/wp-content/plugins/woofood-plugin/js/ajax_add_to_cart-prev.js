jQuery(document).ready(function($) {
	"use strict";

	$(document).on("click", ".single_add_to_cart_button", function() {


		var woocommerce_product_addons_compatibility_check_required = false;

		jQuery('form.cart .wc-pao-addon-field').each(function(){
        if( jQuery(this).attr('required') && jQuery(this).val() =="" ){
           woocommerce_product_addons_compatibility_check_required = true;
			return false;
        }
    });
    
		if(woocommerce_product_addons_compatibility_check_required == false)
			{
		var add_to_cart_original = $( ".single_add_to_cart_button" ).html();

					var ajax_loading_text = $('#ajax_loading_text').val();

           $('.single_add_to_cart_button').prop('disabled', true);
$( ".single_add_to_cart_button" ).html(ajax_loading_text);
		if($(this).hasClass('product_type_variable')) return true;
		
		if(parseInt(jQuery.data(document.body, "processing")) == 1) return false;
		
		jQuery.data(document.body, "processing", 1);
		jQuery.data(document.body, "processed_once", 0);
		
		var context = this;
		
		var form = $(this).closest('form');
		form = $('form.cart');
		console.log(form);
		var button_default_cursor = $("button").css('cursor');
		
		$("html, body").css("cursor", "wait");
		$("button").css("cursor", "wait");
		
		function isElementInViewport (el) {
			if (typeof jQuery === "function" && el instanceof jQuery) {
				el = el[0];
			}

			var rect = el.getBoundingClientRect();

			return (
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
				rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
			);
		}
		console.log(form.serialize());
		$.ajax( {
			type: "POST",
			url: form.attr( 'action' ),
			data: form.serialize(),
			success: function( response ) 
			{
				console.log(response);
				$("html, body").css("cursor", "default");
				$("button").css("cursor", "pointer");
				           $('.single_add_to_cart_button').prop('disabled', false);
$( ".single_add_to_cart_button" ).html(add_to_cart_original);

				//updateCartButtons(response);
    					jQuery( document.body ).trigger( 'wc_fragment_refresh' );


				/*$('.wf_cart_notice').css('display', 'block');
				$('.wf_cart_notice').css('opacity', '1');*/
			Toastify({
  text: wf_product_added_message,
  duration: 3000,
  newWindow: true,
  close: true,
  gravity: "top", // `top` or `bottom`
  positionLeft: false, // `true` or `false`
  backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
  stopOnFocus: true // Prevents dismissing of toast on hover
}).showToast();

			   			//jQuery("#product_view").fadeOut(250);
			   			MicroModal.close('product_view'); // [2]

    					jQuery( document.body ).trigger( 'wc_fragment_refresh' );

				
				
									jQuery.data(document.body, "processing", 0);

				jQuery.data(document.body, "processed_once", 1);
			}

		} );

		}
		else
			{
				          alert(wf_required_fields_not_completed_message);
							return false;

			}
		
		return false;
	});
	
	function getCartUrl() 
	{
		return wfquickajax.cart_url;
	}
	
	function getCartButtons() 
	{
		return $("a[href='"+getCartUrl()+"']:visible");
	}
	
	function getMessageParentDiv(response, woocommerce_msg) 
	{
		var default_dom = $(".product.type-product:eq(0)");
		
		if(default_dom.length > 0) 
		{
			return default_dom;
		}
		else
		{
			var scheck_parent_div = $(response).find("."+woocommerce_msg).parent();
			var id = $(response).find("."+woocommerce_msg).parent().attr('id');
			
			if(id)
			{
				return $("#"+id).children().eq($("#"+id).children().length-1);
			}
			else
			{
				var classes = $(response).find("."+woocommerce_msg).parent().attr('class');
				return $(document).find("div[class='"+classes+"']").children().eq($(document).find("div[class='"+classes+"']").children().length-1);
			}
		}
	}
	
	function updateCartButtons(new_source) 
	{
		//$(new_source).find('.woocommerce-error').remove();
		//$(new_source).find('.woocommerce-message').remove();
		
		var cart_buttons_length = getCartButtons().length;

		if(cart_buttons_length > 0)
		{
			getCartButtons().each(function(index) {
				if($(new_source).find("a[href='"+getCartUrl()+"']:visible").eq(index).length > 0)
				{
					$(this).replaceWith($(new_source).find("a[href='"+getCartUrl()+"']:visible").eq(index));
				}
			});
		}
		
		var $supports_html5_storage = ( 'sessionStorage' in window && window['sessionStorage'] !== null );
		var $fragment_refresh = {
			url: woocommerce_params.ajax_url,
			type: 'POST',
			data: { action: 'woocommerce_get_refreshed_fragments' },
			success: function( data ) {
				if ( data && data.fragments ) {

					$.each( data.fragments, function( key, value ) {
						$(key).replaceWith(value);
					});

					if ( $supports_html5_storage ) {
						sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
						sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
					}

					$('body').trigger( 'wc_fragments_refreshed' );
				}



			}
		};


		$.ajax($fragment_refresh);
	}
});