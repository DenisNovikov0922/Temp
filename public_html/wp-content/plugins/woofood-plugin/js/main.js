(function($) { 
  "use strict"; 
  $(document).ready(function($){




    $(document).on('change', 'input[type=radio][name=woofood_order_type]', function (e)
    {
      e.preventDefault();

      if ($('input[name=woofood_order_type]:checked').val() == "delivery") {
        $('.woocommerce-shipping-fields').show();

      } else if ($('input[name=woofood_order_type]:checked').val() == "pickup") {
        $('.woocommerce-shipping-fields').hide();


      }
    });

  });



  function woofood_check_order_status(order_id)
  {

    $.ajax({
      type: "POST",
      url: woofoodmain.ajaxurl,
      data: {
        action: 'woofood_check_order_status', 
        order_id: order_id
      },
      success: function(response) {
// alert(response);
if(response =="accepting")
{
  setTimeout(function () {
    woofood_check_order_status(order_id);

  }, 5000);

}
else if(response !=null)
{
  if(response.status=="processing")
  {


    jQuery('#order_completed').html(response.message);
    jQuery('#woofood-complete-next').css('display', 'none');
    jQuery('#woofood-complete-prev').css('display', 'none');
    jQuery('#woofood-completed-step').addClass('is-active is-completed');
    jQuery('#woofood_confirm_order').css('display', 'none');


    jQuery('#order_completed').toggleClass('is-active').siblings().removeClass('is-active');

    jQuery(".pageloader").css('-webkit-transform', 'translateY(-100%)');
    jQuery(".pageloader").css('transform', 'translateY(-100%)');




  }
}


}
});





  }



/*  $(document).on('click', '#woofood_confirm_order', function (e)
  {
    jQuery(".pageloader").css('-webkit-transform', 'translateY(0%)');
    jQuery(".pageloader").css('transform', 'translateY(0%)');


    e.preventDefault();
    $.ajax({
      type: "POST",
      url: woofoodmain.ajaxurl,
      data: {
        action: 'woofood_complete_order_allinone', 
        billing_first_name: jQuery('#billing_first_name').val(), 
        billing_last_name: jQuery('#billing_last_name').val(), 

        billing_address_1: jQuery('#billing_address_1').val(),
        billing_city: jQuery('#billing_city').val(), 
        billing_postcode: jQuery('#billing_postcode').val(), 
        billing_state: jQuery('#billing_state').val()


//'cart_item_key': String($(this).attr('data-cart-item-key'))
},
success: function(response) {
  woofood_check_order_status(response);
}
});

  });*/





/*  $(document).on('change', '.woocommerce-checkout #billing_address_1,.woocommerce-checkout #billing_postcode, .woocommerce-checkout #billing_city, .woocommerce-checkout #billing_country', function (e)
  {
    e.preventDefault();



    $.ajax({
      type: "POST",
      url: woofoodmain.ajaxurl,
      data: {
        action: 'woofood_get_active_shipping_methods', 
        billing_first_name: jQuery('#billing_first_name').val(), 
        billing_last_name: jQuery('#billing_last_name').val(), 

        billing_address_1: jQuery('#billing_address_1').val(),
        billing_city: jQuery('#billing_city').val(), 
        billing_postcode: jQuery('#billing_postcode').val(), 
        billing_state: jQuery('#billing_state').val()


//'cart_item_key': String($(this).attr('data-cart-item-key'))
},
success: function(response) {

  jQuery('#woofood_delivery_method').html(response);
}
});

  });*/




  $(document).on('change', '.shipping_method', function (e)
  {
    e.preventDefault();



    $.ajax({
      type: "POST",
      url: woofoodmain.ajaxurl,
      data: {
        action: 'woofood_get_available_payment_gateways', 
        shipping_method: jQuery('.shipping_method:checked').val() 



//'cart_item_key': String($(this).attr('data-cart-item-key'))
},
success: function(response) {

  jQuery('#woofood_payment_method').html(response);
}
});

  });


  $(document).on('click', '#woofood-complete-next', function (e)
  {
    if (jQuery("#woofood-step-address").hasClass("is-active")) 
    {
      jQuery("#woofood_confirm_order").css('display', 'block');
      jQuery("#woofood-complete-next").css('display', 'none');

    }
    else
    {
      jQuery("#woofood_confirm_order").css('display', 'none');
      jQuery("#woofood-complete-next").css('display', 'block');


    }
  });


  $(document).on('click', '#woofood-complete-prev', function (e)
  {
    if (jQuery("#woofood-step-address").hasClass("is-active")) 
    {
      jQuery("#woofood_confirm_order").css('display', 'block');
      jQuery("#woofood-complete-next").css('display', 'none');

    }
    else
    {
      jQuery("#woofood_confirm_order").css('display', 'none');
      jQuery("#woofood-complete-next").css('display', 'block');

    }

  });

  $(document).on('click', '#woofood-cart-remove-item', function (e)
  {
    var product_container = $(this).parents('.woofood-cart-item');

    product_container.block({
      message: null,
      overlayCSS: {
        cursor: 'none'
      }
    });


    e.preventDefault();
    $.ajax({
      type: "POST",
      url: woofoodmain.ajaxurl,
      data: {
        action: 'woofood_cart_remove_item', 
        'cart_item_key': String($(this).attr('data-cart-item-key'))
      },
      success: function(response) {
        if ( ! response || response.error )
          return;

        var fragments = response.fragments;
// Replace fragments
if ( fragments ) {
  $.each( fragments, function( key, value ) {
    $( key ).replaceWith( value );
  });
}
}
});
  });   




})(jQuery);
