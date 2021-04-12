<?php
function woofood_availability_form_checker()
{
    $woofood_options = get_option('woofood_options');
    $woofood_availability_checker_keep_opened = isset($woofood_options['woofood_availability_checker_keep_opened']) ? $woofood_options['woofood_availability_checker_hide_address_pickup']: false  ;
    $woofood_availability_checker_hide_address_pickup = isset($woofood_options['woofood_availability_checker_hide_address_pickup']) ? $woofood_options['woofood_availability_checker_hide_address_pickup']: false  ;
  if(!is_admin())
  {


  global $woocommerce;
    ob_start();



?>
<?php if($woofood_availability_checker_keep_opened): ?>
  <script>
    jQuery(document).keydown(function (event) {
if (event.keyCode === 27) {
event.stopImmediatePropagation();
}
});
  </script>
  <?php endif; ?>


  <?php if($woofood_availability_checker_hide_address_pickup): ?>
  <script>
    function woofood_multi_hide_address_on_pickup()
    {
      var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
            jQuery('#woofood_address_checker_address').css('display', 'none');
            


                
    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")

   {                jQuery('#woofood_address_checker_address').css('display', 'flex');
                    jQuery('.woofood-address-check-btn').css('display', 'flex');
                jQuery('.woofood-address-results').html("");

         

   // jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  

    

        return false;

    }
 jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

  woofood_multi_hide_address_on_pickup();

        
    });
  jQuery( document ).ready(function() {
  woofood_multi_hide_address_on_pickup();

  
});
  </script>
  <?php endif; ?>



  <script>
    function woofood_availability_store_check_store_selected()
    {
      if(jQuery('input[name=woofood_order_type]').length)
      {

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
   
                jQuery('.availability_checker_extra_store_pickup').addClass("show");
                jQuery('.availability_checker_extra_store_delivery').removeClass("show");


                
    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")

   {             

                jQuery('.availability_checker_extra_store_delivery').addClass("show");
                jQuery('.availability_checker_extra_store_pickup').removeClass("show");

   // jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  
        return false;

      }



    }
    jQuery( document ).ready(function() {
             woofood_availability_store_check_store_selected();

  
});
 jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

       woofood_availability_store_check_store_selected();
    

    });
  </script>


     

<div class="modal micromodal-slide wf_availability_popup" id="wf_availability_popup" aria-hidden="true" >
    <div class="modal__overlay" tabindex="-1" <?php if(!$woofood_availability_checker_keep_opened) { echo 'data-micromodal-close'; } ?>>
      
          <div class="content">
<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
          <?php esc_html_e('Availability Checker', 'woofood-plugin'); ?>
          </h2>
          <?php if(!$woofood_availability_checker_keep_opened): ?>

          <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
            <?php endif; ?>

        </header>

        <main class="modal__content" id="modal-1-content">

        <div class="woofood-address-wrapper" style="
    /* display: flex; */
 
    /* flex: 1; */
">
<form id="wf_availability_form_checker" type="POST">

    <div class="woofood-address-title" style="
   
    /* flex-grow: 1; */
"><?php esc_html_e('Availability Checker', 'woofood-plugin');?></div>
<?php
$woofood_options = get_option('woofood_options');
$woofood_options = get_option('woofood_options');

  $woofood_enable_pickup_option = $woofood_options['woofood_enable_pickup_option'];

  $woofood_distance_type = $woofood_options['woofood_distance_type'];
  if ($woofood_enable_pickup_option){
  ?>

          <div class="woofood_order_type">
<?php 
  $default_order_type=woofood_get_default_order_type();

wf_form_field_radio( 'woofood_order_type', array(

'type'         => 'radio',

'class'         => array('wf_order_type_radio_50'),

'required'     => true,
'options'  => woofood_get_order_types(),

), $default_order_type); 
?>
</div>
<?php

}

else
{?>
<input type="hidden" name="order_type" value="<?php echo $default_order_type; ?>"/>
<?php 
}

 if (class_exists("WooFood_Multistore_Settings"))
        {
            $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = isset($woofood_options_multistore['woofood_auto_store_select']) ? $woofood_options_multistore['woofood_auto_store_select'] : false ;

            if(! $woofood_auto_store_select )
            {
          echo '<div class="availability_checker_extra_store_delivery">';

                $delivery_stores = woofood_get_delivery_stores();
        echo '<span class="wooofood_select_store_availability_label">'.esc_html__('Select a Store', 'woofood-plugin').'</span>';
woocommerce_form_field( 'extra_store_name_delivery', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('extra_store_name'),

'required'     => true,
//'options'  => $time_to_delivery_options,
'options'  =>  $delivery_stores,

), '');




echo '</div>';


            }
        
 echo '<div class="availability_checker_extra_store_pickup">';

          $pickup_stores = woofood_get_pickup_stores();
        echo '<span class="wooofood_select_store_availability_label">'.esc_html__('Select a Store', 'woofood-plugin').'</span>';
woocommerce_form_field( 'extra_store_name_pickup', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('extra_store_name'),

'required'     => true,
//'options'  => $time_to_delivery_options,
'options'  =>  $pickup_stores,

), '');




echo '</div>';
          ?>

          <?php



        }


?>


<div class="woofood-address-input" style="
   
">
  <?php if($woofood_distance_type == "postalcode"): ?>
        <input type="text" id="woofood_address_checker_address"  name="billing_postcode" placeholder="<?php _e('Type your postal code...', 'woofood-plugin'); ?>" value="<?php if(WC()->session){ echo WC()->session->get( 'woofood_form_customer_address'); }  ?>" />
  <?php else: ?>

    <input type="text" id="woofood_address_checker_address" onfocus="geolocate()"  onclick="geolocate()" name="address" placeholder="<?php _e('Type your address...', 'woofood-plugin'); ?>" value="<?php if(WC()->session){ echo WC()->session->get( 'woofood_form_customer_address'); }  ?>" />
      <?php endif; ?>

           <input type="hidden" name="action" value="wf_availiability_cheker_ajax"/>
                      <input type="hidden" name="billing_address_number" id="billing_address_number" />

                      <input type="hidden" name="billing_address_1" id="billing_address_1" />
                      <input type="hidden" name="billing_city"  id="billing_city"/>
                        <?php if($woofood_distance_type !== "postalcode"): ?>

                      <input type="hidden" name="billing_postcode" id="billing_postcode" />
                            <?php endif; ?>

                      <input type="hidden" name="billing_country" id="billing_country" />
                      <input type="hidden" name="billing_state" id="billing_state" />


<button class="woofood-address-check-btn" type="submit" style="
    
"><?php _e('Check', 'woofood-plugin'); ?></button>
    
    </div>
    </form>

    <div class="woofood-address-results">

    <?php 
    if(WC()->session){ 
      if(WC()->session->get( 'wofood_redirect_script_session'))           
      {
        $redirect_url  = WC()->session->get( 'wofood_redirect_script_session');
        WC()->session->set( 'wofood_redirect_script_session', null);
        echo $redirect_url;
      }

    echo WC()->session->get( 'woofood_form_customer_address_response');
    }
     ?>
   
    </div>
</div>

</main>


</div>
</div>
        <footer class="modal__footer">
         
        </footer>
      
  </div>
  </div>

<script>


      var placeSearch, autocomplete;
      var componentForm = {
        street_number: ['billing_address_number','short_name'],
        route: ['billing_address_1', 'long_name'],
        locality: ['billing_city','long_name'],
        administrative_area_level_1: ['billing_state','short_name'],
        country: ['billing_country','long_name'],
        postal_code: ['billing_postcode','short_name']
      };

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
         autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('woofood_address_checker_address')),
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        if(place)
        {

          for (var component in componentForm) {
          
          document.getElementById(componentForm[component][0]).value = '';
          document.getElementById(componentForm[component][0]).disabled = false;

        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          console.log(place.address_components);

          var addressType = place.address_components[i].types[0];

          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType][1]];
            document.getElementById(componentForm[addressType][0]).value = val;

          }
        }

        }

        
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        initAutocomplete();
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }


</script>
<?php
$current_customer_postcode = $woocommerce->customer->get_billing_postcode();
$current_customer_address = $woocommerce->customer->get_billing_address_1();
if(empty($current_customer_address))
{
  //$current_customer_address = $current_customer_postcode;
}
if(empty($current_customer_address))
{
  ?>
  <script>
  jQuery( document ).ready(function() {

  MicroModal.show('wf_availability_popup');
  });
  </script>

  <?php

}

$html_export = ob_get_clean();
return $html_export;
}
}

add_shortcode('woofood_availability_popup', 'woofood_availability_form_checker');



function wf_availiability_cheker_ajax(){
  global $woocommerce;
$delivery_available  = false; 
$options_woofood = get_option('woofood_options');
$woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
$woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
$woofood_distance_type = isset($options_woofood['woofood_distance_type']) ? $options_woofood['woofood_distance_type'] : "default";
$woofood_postalcodes = isset($options_woofood['woofood_postalcodes']) ? $options_woofood['woofood_postalcodes'] : null;
$woofood_polygon_area = isset($options_woofood['woofood_polygon_area']) ? $options_woofood['woofood_polygon_area'] : null;

$woofood_store_address = $options_woofood['woofood_store_address'];
$customer_address    = $_POST['address'];
$order_type    = $_POST['woofood_order_type'];
$billing_address_number    = $_POST['billing_address_number'];
$billing_address_1   = $billing_address_number." ".$_POST['billing_address_1'];
$billing_city    = $_POST['billing_city'];
$billing_country   = $_POST['billing_country'];
$billing_postcode   = isset($_POST['billing_postcode']) ? str_replace(" ", "", $_POST['billing_postcode'] ) : "";
$billing_state   = $_POST['billing_postcode'];
$additional_error = "";
 
                 

   if(!empty($woofood_google_distance_matrix_api_key) && !empty($woofood_max_delivery_distance ) && !empty($woofood_store_address ) && $order_type!="pickup" && $woofood_distance_type =="default"  )
{

    $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($woofood_store_address)."&destinations=".urlencode($customer_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
  $details = htmlspecialchars_decode($details);
  $details = str_replace("&amp;", "&", $details );
  $json = woofood_get_contents($details);

  $details = json_decode($json, TRUE);

  if(empty($details["error_message"]))
  {
    if($details['rows'][0]['elements'][0]['distance']['value'])
    {
          if ($details['rows'][0]['elements'][0]['distance']['value'] < $woofood_max_delivery_distance *1000)
  {
//We can deliver /// 

  $delivery_available  = true;  


  }
      
    }



  else{

  $delivery_available  = false; 



}//end else
  }
     else{
        $delivery_available  = false; 

       $additional_error = "<br/>".$details["error_message"];

     }
  

}
   elseif(!empty($woofood_google_distance_matrix_api_key) && !empty($woofood_polygon_area ) && !empty($woofood_store_address ) && $order_type!="pickup" && $woofood_distance_type =="polygon"  )
{

               $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($customer_address)."&key=".$woofood_google_distance_matrix_api_key."";
              $details = htmlspecialchars_decode($details);
              $details = str_replace("&amp;", "&", $details );
              $json = woofood_get_contents($details);
              $details = json_decode($json, TRUE);

               if(!empty($details["error_message"]))
              {
                 //error here from google api must be shown to admin//
              }
              elseif ( !empty($details['results'][0]['geometry']['location']["lat"] ) && !empty($details['results'][0]['geometry']['location']["lng"] ))
              {
                $polygon_area_points  = json_decode($woofood_polygon_area , true);
                $points_x =array();
                $points_y =array();

                foreach($polygon_area_points as $current_point)
                {
                   $points_x[] = $current_point["lng"];
                  $points_y[] = $current_point["lat"];

                }

                //we got customers lat/lng//
                $latitude_y =  $details['results'][0]['geometry']['location']["lat"];

                $longitude_x =  $details['results'][0]['geometry']['location']["lng"];
               $points_polygon = count($points_x);  // number vertices - zero-based array

         


                if (woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){
          $delivery_available = true;
              }
              else
              {
                          $delivery_available = false;

              }




              }


              else{
                          $delivery_available = false;

            //we cannot deliver// show message//
             


            }//end else



   


}



   elseif(!empty($woofood_postalcodes ) && !empty($woofood_store_address ) && $order_type!="pickup" && $woofood_distance_type =="postalcode"  )
{
        $woofood_postalcodes = str_replace(" ", "", $woofood_postalcodes);
                $woofood_postalcodes = strtoupper($woofood_postalcodes);
                $billing_postcode =  strtoupper($billing_postcode);

        $postal_codes_array = explode(",",  trim($woofood_postalcodes));

               if(in_array(trim($billing_postcode), $postal_codes_array))
        {

         
            $delivery_available  = true;  

           

        }
        else
        {
            $delivery_available  = false;  

        }



   


}

else
{
  $delivery_available  = true;  

}
$response = '';
if( $delivery_available)  
{
  $woocommerce->customer->set_billing_address_1( $billing_address_1 );
    $woocommerce->customer->set_billing_city( $billing_city );
    $woocommerce->customer->set_billing_postcode( $billing_postcode );
    $woocommerce->customer->set_billing_country( $billing_country );

    if($woofood_distance_type =="postalcode")
    {
   WC()->session->set( 'woofood_form_customer_address', $billing_postcode );

    }
    else
    {
         WC()->session->set( 'woofood_form_customer_address', $customer_address );

    }

     WC()->session->set( 'woofood_order_type', $order_type );


$response = '<div class="availability-result"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="50" height="50" viewBox="0 0 24 24" style="fill: #cc0000;text-align: center;margin: 20px;border: 1px solid black;padding: 9px;border-radius: 99999px;"><g id="surface1"><path style=" fill-rule:evenodd;" d="M 22.59375 3.5 L 8.0625 18.1875 L 1.40625 11.5625 L 0 13 L 8.0625 21 L 24 4.9375 Z "></path></g></svg>'.'<div class="availability-result-message">'.sprintf(esc_html__(" %s is available", "woofood-plugin").'</div>', woofood_get_order_type_by_key($order_type)).'<div class="wf_availability_actions"><a class="wf_start_order_btn" data-micromodal-close>'.esc_html__('Start Order','woofood-plugin').'</a></div></div>';
 WC()->session->set( 'woofood_form_customer_address_response', $response );

} 
else
{
  $svg_sad = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0px" width="50" height="50" viewBox="0 0 512 512" style="fill: #cc0000; text-align: center; margin: 20px; /* border: 1px solid black; */ /* padding: 9px; */ border-radius: 99999px; " xml:space="preserve"> <g> <g> <path d="M375.71,356.744c-1.79-2.27-44.687-55.622-119.71-55.622s-117.92,53.351-119.71,55.622l31.42,24.756 c0.318-0.404,32.458-40.378,88.29-40.378c55.147,0,87.024,38.807,88.354,40.458l-0.064-0.08L375.71,356.744z"></path> </g> </g> <g> <g> <path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"></path> </g> </g> <g> <g> <circle cx="168" cy="180.12" r="32"></circle> </g> </g> <g> <g> <circle cx="344" cy="180.12" r="32"></circle> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>';
  $response = '<div class="availability-result">'.$svg_sad.'<div class="availability-result-message">'.sprintf(esc_html__(" %s is not available", "woofood-plugin").$additional_error.'</div>', woofood_get_order_type_by_key($order_type)).'</div>';
 WC()->session->set( 'woofood_form_customer_address_response', "" );


}
 


    echo json_encode($response);





    wp_die();

  }
  add_action('wp_ajax_nopriv_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax');
  add_action('wp_ajax_wf_availiability_cheker_ajax', 'wf_availiability_cheker_ajax');

?>