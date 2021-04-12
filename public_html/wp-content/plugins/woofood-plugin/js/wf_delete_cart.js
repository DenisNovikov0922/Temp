(function($) { 
  "use strict"; 
$(document).ready(function($){


    function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

// Ajax delete product in the cart
$(document).on('click', '.mini_cart_item a.remove', function (e)
{
    e.preventDefault();

    var product_id = $(this).attr("data-product_id"),
        cart_item_key = $(this).attr("data-cart_item_key"),
        product_container = $(this).parents('.mini_cart_item');

      
            var remove_url =  $(this).attr("href");
            cart_item_key = getParameterByName("remove_item", remove_url);

       

    // Add loader
    product_container.block({
        message: null,
        overlayCSS: {
            cursor: 'none'
        }
    });

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: wfdeletecartajax.ajaxurl,
        data: {
            action: "product_remove",
            product_id: product_id,
            cart_item_key: cart_item_key
        },
        success: function(response) {
            console.log(response);
            if ( ! response || response.error )
                return;
                        jQuery( document.body ).trigger( 'wc_fragment_refresh' );

                        Toastify({
  text: wf_product_removed_message,
  duration: 3000,
  newWindow: true,
  close: true,
  gravity: "top", // `top` or `bottom`
  positionLeft: false, // `true` or `false`
  backgroundColor: "#cc0000",
  stopOnFocus: true // Prevents dismissing of toast on hover
}).showToast();

           /* var fragments = response.fragments;
            // Replace fragments
            if ( fragments ) {
                $.each( fragments, function( key, value ) {
                    $( key ).replaceWith( value );
                });
            }*/
            
        }
    });
});

});
})(jQuery);