<?php

$woofood_options = get_option('woofood_options');
$woofood_delivery_fee = isset($woofood_options['woofood_delivery_fee']) ?  floatval($woofood_options['woofood_delivery_fee']) : null;
$woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
if($woofood_delivery_fee>0 || $woofood_enable_pickup_option )
{
	add_action( 'woocommerce_after_checkout_form', 'woofood_add_dynamic_shipping_calculation');
 
function woofood_add_dynamic_shipping_calculation() {
?>
<script>
jQuery(document).ready(function () {


  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
        var data = {
            action: 'woofood_calculate_shipping_fee',
            security: wc_checkout_params.apply_district_nonce,
            type: woofood_order_type
        };

        jQuery.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: data,
             beforeSend: function(){
                                       jQuery('.woofood_order_type').addClass('disabled');


   },
            success: function (code) {
                            console.log(code);
                            

                            jQuery('body').trigger('update_checkout');

             
            },
             complete: function(){


     // Handle the complete event
   }
            /*dataType: 'html'*/
        });
jQuery( document.body ).on( 'updated_checkout', function(){
  // Code stuffs
                                       jQuery('.woofood_order_type').removeClass('disabled');

});

jQuery( document.body ).on( 'update_checkout', function(){
  // Code stuffs
                         jQuery('.woofood_order_type').addClass('disabled');

  // has the function initialized after the event trigger?
  console.log('on updated_shipping_method: function fired'); 
});


    jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
        var data = {
            action: 'woofood_calculate_shipping_fee',
            //security: wc_checkout_params.apply_district_nonce,
            type: woofood_order_type
        };

        jQuery.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: data,
              beforeSend: function(){
                         jQuery('.woofood_order_type').addClass('disabled');

   },
            success: function (code) {
              console.log(code);
                                                  jQuery('body').trigger('update_checkout');

             
            },
              complete: function(){


     // Handle the complete event
   }
           /* dataType: 'html'*/
        });

       
    });


/*    jQuery( document ).on( 'updated_checkout', function() {
 var data = {
            action: 'woofood_distane_based_delivery_fee',
            data: "test"
        };

       jQuery.ajax({
            type: 'POST',
            url: wc_checkout_params.ajax_url,
            data: data,
              beforeSend: function(){

   },
            success: function (code) {
                        jQuery( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();

              console.log(code);
          show_woofood_notice( code );

             
            },
              complete: function(){


    
   }
           


});


});*/


    var show_woofood_notice = function( html_element, $target ) {
    if ( ! $target ) {
      $target = jQuery( '.woocommerce-notices-wrapper:first' ) || jQuery( '.cart-empty' ).closest( '.woocommerce' ) || $( '.woocommerce-cart-form' );
    }
    $target.prepend( html_element );
  };

});

</script>

<?php

}




add_action('wp_ajax_woofood_calculate_shipping_fee', 'woofood_calculate_shipping_fee', 10);
add_action('wp_ajax_nopriv_woofood_calculate_shipping_fee', 'woofood_calculate_shipping_fee', 10);






 function woofood_calculate_shipping_fee() {


          global $woocommerce;


      if (isset($_POST['type'])) {
          $type = $_POST['type'];
         
                 WC()->session->set( 'woofood_order_type', $type );


        }
        else
        {

        WC()->session->set( 'woofood_order_type',  woofood_get_default_order_type() );


        }
        echo WC()->session->get( 'woofood_order_type');
        wp_die();
    }






/* function woofood_distane_based_delivery_fee() {


          global $woocommerce;
      


      if(WC()->session->get( 'woofood_delivery_available' ) == false && (WC()->session->get( 'woofood_order_type' ) == "delivery") )
      {
        wc_add_notice(esc_html__("Delivery is not available to your address", "woofood-plugin"), "error");
        wc_print_notices();

      }
      else
      {
        wc_clear_notices();
      }



        wp_die();
    }


add_action('wp_ajax_woofood_distane_based_delivery_fee', 'woofood_distane_based_delivery_fee', 10);
add_action('wp_ajax_nopriv_woofood_distane_based_delivery_fee', 'woofood_distane_based_delivery_fee', 10);*/




add_action('woocommerce_cart_calculate_fees', 'woofood_add_delivery_fee');
 
function woofood_add_delivery_fee() {
  global $woocommerce;
     // $woofood_add_delivery_fee = 0;
     
     // $woofood_add_delivery_fee =  WC()->session->get( 'woofood_delivery_fee' );
     $order_type =  WC()->session->get( 'woofood_order_type' );
    $woofood_options = get_option('woofood_options');
  $woofood_delivery_fee = isset($woofood_options['woofood_delivery_fee']) ? floatval($woofood_options['woofood_delivery_fee']) : null;
    $woofood_delivery_fee_type = $woofood_options['woofood_delivery_fee_type'];
    $woofood_google_distance_matrix_api_key = isset($woofood_options['woofood_google_distance_matrix_api_key']) ? $woofood_options['woofood_google_distance_matrix_api_key'] : null ;

  $woofood_store_address = $woofood_options['woofood_store_address'];
  $woofood_delivery_fee_distance_based = isset( $woofood_options['woofood_delivery_fee_distance_based'] ) ? $woofood_options['woofood_delivery_fee_distance_based']: null; 

      if($woofood_delivery_fee_type =="distance" && !empty($woofood_google_distance_matrix_api_key) && !empty($woofood_store_address) && $woofood_delivery_fee_distance_based && $order_type =="delivery")
      {

          $delivery_fees = json_decode($woofood_delivery_fee_distance_based);

        $customer_address =  WC()->customer->get_shipping_address_1().", ".WC()->customer->get_shipping_city().", ".WC()->customer->get_shipping_postcode().", ".WC()->customer->get_shipping_country();

         $distance =  woofood_get_delivery_distance($woofood_store_address,$customer_address, $woofood_google_distance_matrix_api_key );

         $delivery_fee = null;
         $delivery_fee_calculated = null;
         foreach($delivery_fees as $delivery_fee_list)
         {
          if((($delivery_fee_list->km_from *1000) <= $distance ) && ($distance  <= ($delivery_fee_list->km_to *1000)) )
          {
              $delivery_fee = $delivery_fee_list->fee;
              $delivery_fee_calculated = true;

          }
         

         }
         if($delivery_fee > 0)
         {        
                     WC()->cart->add_fee(esc_html__('Delivery Fee', 'woofood-plugin'), $delivery_fee);


         }
          
         if(!$delivery_fee_calculated)
         {
                               WC()->cart->add_fee(esc_html__('Delivery Fee', 'woofood-plugin'), $woofood_delivery_fee);

         } 




      }
      else
      {
                 if($woofood_delivery_fee  > 0 && ($order_type == "delivery"))
             {
             
                  WC()->cart->add_fee(esc_html__('Delivery Fee', 'woofood-plugin'), $woofood_delivery_fee);
             }  
             else
             {
                   

             }



      }
    

    }

}








function woofood_get_delivery_distance($store_address, $customer_address, $api_key)
{
    $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($store_address)."&destinations=".urlencode($customer_address)."&mode=driving&sensor=false&key=".$api_key;
  $details = htmlspecialchars_decode($details);
  $details = str_replace("&amp;", "&", $details );
  $json = woofood_get_contents($details);
  $details = json_decode($json, TRUE);

    if(!empty($details["error_message"]))
  {
    return $details["error_message"];

  }
  elseif (($details['rows'][0]['elements'][0]['status'] =="OK") )
  {
    return $details['rows'][0]['elements'][0]['distance']['value'];

  }

}




// Conditionally changing the shipping methods costs
add_filter( 'woocommerce_package_rates','woofood_order_type_pickup_remove_all_shipping', 90, 2 );
function woofood_order_type_pickup_remove_all_shipping( $rates, $package ) {

    if ( WC()->session->get('woofood_order_type' ) == 'pickup' ){
        foreach ( $rates as $rate_key => $rate_values ) {

            if('local_pickup' !== $rate_values->method_id ) {

              $rates[$rate_key]->cost = 0;
                $rates[$rate_key]->label = '' . __("Pickup", "woocommerce");
                $taxes = array();
                foreach ($rates[$rate_key]->taxes as $key => $tax)
                    if( $rates[$rate_key]->taxes[$key] > 0 ) // set the new tax cost
                        $taxes[$key] = 0;
                $rates[$rate_key]->taxes = $taxes;

                      unset($rates[$rate_key]);

             }

        }
    }


   else {
        foreach ( $rates as $rate_key => $rate_values ) {

            if('local_pickup' === $rate_values->method_id ) {

        

                      unset($rates[$rate_key]);

             }

        }
    }

    return $rates;
}

// Enabling, disabling and refreshing session shipping methods data
add_action( 'woocommerce_checkout_update_order_review', 'woofood_refresh_shipping_methods', 10, 1 );
function woofood_refresh_shipping_methods( $post_data ){
    $bool = true;
    if ( WC()->session->get('woofood_order_type' ) == 'pickup' ) $bool = false;

    // Mandatory to make it work with shipping methods
    foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ){
        WC()->session->set( 'shipping_for_package_' . $package_key, $bool );
    }
    WC()->cart->calculate_shipping();
}



?>