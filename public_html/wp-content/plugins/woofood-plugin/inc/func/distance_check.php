<?php


function wf_google_api_scripts() {

  wf_google_maps_script_loader();

  wf_google_maps_script_loader_auto_complete();
}


function wf_google_maps_script_loader() {
  $options_woofood = get_option('woofood_options');

  $woofood_google_api_key = $options_woofood['woofood_google_api_key'];

  global $wp_scripts; $gmapsenqueued = false;
  foreach ($wp_scripts->registered as $key => $script) {
    if (preg_match('#maps\.google(?:\w+)?\.com/maps/api/js#', $script->src)) {
      $gmapsenqueued = true;
    }
  }

  if (!$gmapsenqueued) {

    wp_enqueue_script('google-autocomplete', 'https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key='.$woofood_google_api_key.'&language='.substr(get_bloginfo ( 'language' ), null, 2));
  }
}
function wf_google_maps_script_loader_auto_complete() {

  wp_enqueue_script('rp-autocomplete', WOOFOOD_PLUGIN_URL. 'js/autocomplete.js', array('jquery', 'google-autocomplete'), WOOFOOD_PLUGIN_VERSION);
}
$woofood_google_api_key = isset($options_woofood['woofood_google_api_key']) ? $options_woofood['woofood_google_api_key'] : null ;

if( $woofood_google_api_key ) {
  add_action('wp_enqueue_scripts', 'wf_google_api_scripts');
}




//check distance on checkout//

$options_woofood = get_option('woofood_options');
$woofood_google_distance_matrix_api_key = isset($options_woofood['woofood_google_distance_matrix_api_key']) ? $options_woofood['woofood_google_distance_matrix_api_key'] : null ;
$woofood_max_delivery_distance = isset($options_woofood['woofood_max_delivery_distance']) ? $options_woofood['woofood_max_delivery_distance'] : null ;
$woofood_store_address = isset($options_woofood['woofood_store_address']) ? $options_woofood['woofood_store_address'] : null ;
$woofood_distance_type = isset($options_woofood['woofood_distance_type']) ? $options_woofood['woofood_distance_type'] : "default" ;
$woofood_polygon_area = isset($options_woofood['woofood_polygon_area']) ? $options_woofood['woofood_polygon_area'] : null;
$woofood_postalcodes = isset($options_woofood['woofood_postalcodes']) ? $options_woofood['woofood_postalcodes'] : null;

if(!empty($woofood_google_distance_matrix_api_key) && !empty($woofood_max_delivery_distance )  && !empty($woofood_store_address ) && ($woofood_distance_type  ==="default")  )
{

  add_action( 'woocommerce_checkout_process', 'wf_check_distance' );

function wf_check_distance() {
  if(isset($_POST['woofood_order_type']) && $_POST['woofood_order_type'] =="pickup" )
  {

  }
  else
  {
        $options_woofood = get_option('woofood_options');

 


  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
  $woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
  $woofood_store_address = $options_woofood['woofood_store_address'];

  $woofood_current_address = $_POST['billing_address_1'];
  $woofood_current_city = $_POST['billing_city'];
  $woofood_current_postcode = $_POST['billing_postcode'];

  $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode;

  $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($woofood_store_address)."&destinations=".urlencode($woofood_total_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
  $details = htmlspecialchars_decode($details);
  $details = str_replace("&amp;", "&", $details );
  $json = woofood_get_contents($details);


   /*wc_add_notice( 
      sprintf(  $json , 
        $woofood_delivery_hour_start, 
        $woofood_delivery_hour_end
        ), 'error' 
      );*/

  $details = json_decode($json, TRUE);


  if(!empty($details["error_message"]))
  {
     wc_add_notice( 
      sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for Distance Matrix API Key on your Google Console Account", 
        $woofood_delivery_hour_start, 
        $woofood_delivery_hour_end
        ), 'error' 
      );
  }



 elseif (($details['rows'][0]['elements'][0]['status'] =="OK") && ($details['rows'][0]['elements'][0]['distance']['value'] <= $woofood_max_delivery_distance *1000))
  {
//We can deliver /// 


  }


  else{

//we cannot deliver// show message//
    wc_add_notice( 
      sprintf( esc_html__('Delivery Service is Not Available in your Area..', 'woofood-plugin') , 
        $woofood_delivery_hour_start, 
        $woofood_delivery_hour_end
        ), 'error' 
      );


}//end else




  } //end else if order type is delivery
  



}//end function
//check distance on checkout//


}








if(!empty($woofood_google_distance_matrix_api_key)  && !empty($woofood_polygon_area)  && !empty($woofood_store_address ) && ($woofood_distance_type  ==="polygon")  )
{

  add_action( 'woocommerce_checkout_process', 'wf_check_polygon' );

function wf_check_polygon() {
  if(isset($_POST['woofood_order_type']) && $_POST['woofood_order_type'] =="pickup" )
  {

  }
  else
  {
        $options_woofood = get_option('woofood_options');

  



              $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
              $woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
              $woofood_store_address = $options_woofood['woofood_store_address'];

              $woofood_current_address = $_POST['billing_address_1'];
              $woofood_current_city = $_POST['billing_city'];
              $woofood_current_postcode = $_POST['billing_postcode'];

              $woofood_total_address = $woofood_current_address.",".$woofood_current_city.",".$woofood_current_postcode;

              $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($woofood_total_address)."&key=".$woofood_google_distance_matrix_api_key."";
              $details = htmlspecialchars_decode($details);
              $details = str_replace("&amp;", "&", $details );
              $json = woofood_get_contents($details);
              $woofood_polygon_area = $options_woofood['woofood_polygon_area'];


               
               /*    wc_add_notice( 
                  json_encode($woofood_polygon_area), 'error' 
                  );
              */
               

              $details = json_decode($json, TRUE);


              if(!empty($details["error_message"]))
              {
                 wc_add_notice( 
                  sprintf( "Google API Error:".$details["error_message"].".Please Correct the configuration for  API Key on your Google Console Account", 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );
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

         


                if (!woofood_check_if_is_in_polygon($points_polygon, $points_x, $points_y, $longitude_x, $latitude_y)){
          wc_add_notice( 
                  sprintf( esc_html__('Delivery Service is Not Available in your Area..', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );
              }




              }


              else{

            //we cannot deliver// show message//
                wc_add_notice( 
                  sprintf( esc_html__('Your Address seems to be invalid. Please check your address', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );


            }//end else












  } //end else if order type is delivery
  



}//end function
//check distance on checkout//


}


if(!empty($woofood_postalcodes)  && ($woofood_distance_type  ==="postalcode")  )
{

  add_action( 'woocommerce_checkout_process', 'wf_check_postalcode' );

function wf_check_postalcode() {
  if(isset($_POST['woofood_order_type']) && $_POST['woofood_order_type'] =="pickup" )
  {

  }
  else
  {


     

        $options_woofood = get_option('woofood_options');
        $woofood_postalcodes = isset($options_woofood['woofood_postalcodes']) ? $options_woofood['woofood_postalcodes'] : null;
        $woofood_postalcodes = str_replace(" ", "", $woofood_postalcodes);
        $woofood_postalcodes = strtoupper($woofood_postalcodes);
       
        $woofood_current_postcode = isset($_POST['shipping_postcode']) ? $_POST['shipping_postcode'] : $_POST['billing_postcode'];
        $woofood_current_postcode = str_replace(" ", "",  $woofood_current_postcode);
        $woofood_current_postcode = strtoupper($woofood_current_postcode);

        $postal_codes_array = explode(",",  trim($woofood_postalcodes));
        $woofood_check_postal_prefixes = apply_filters( 'woofood_check_postal_prefixes', false);



        if(!$woofood_check_postal_prefixes)
        {
        if(!in_array(trim($woofood_current_postcode), $postal_codes_array))
        {

             wc_add_notice( 
                  sprintf( esc_html__('Delivery Service is Not Available in your Area..', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );

           

        }
      }
      else
      {

               if(is_array($postal_codes_array))
        {
          $matching_postal_code = false;

          foreach($postal_codes_array as $current_postal_code)
          {
            if (strpos($woofood_current_postcode, $current_postal_code) === 0) {


              $matching_postal_code =  true;
              
              }             


          }
          if(!$matching_postal_code)
          {
             wc_add_notice( 
                  sprintf( esc_html__('Delivery Service is Not Available in your Area..', 'woofood-plugin') , 
                    $woofood_delivery_hour_start, 
                    $woofood_delivery_hour_end
                    ), 'error' 
                  );
          }


           


        }

      }





               

          
            



           


           











  } //end else if order type is delivery
  



}//end function
//check distance on checkout//


}
function woofood_check_if_is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = 0;
  for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
    if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
     ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
       $c = !$c;
  }
  return $c;
}

function woofood_check_if_is_in_polygon_tes_2($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
{
  $i = $j = $c = $point = 0;
  for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
    $point = $i;
    if( $point == $points_polygon )
      $point = 0;
    if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
     ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
       $c = !$c;
  }
  return $c;
}
 function woofood_check_if_is_in_polygon_test_2($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
    $i = $j = $c = $point = 0;
    for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        $point = $i;
        if( $point == $points_polygon )
            $point = 0;
        if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) && ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
            $c = !$c;
    }
    return $c;
}

?>