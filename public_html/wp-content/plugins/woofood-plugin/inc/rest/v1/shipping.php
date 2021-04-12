<?php
add_action( 'rest_api_init', 'register_rest_woofood_shippping', 10, 99);
function register_rest_woofood_shippping() {
  register_rest_route( 'woofood/v1', 'shipping/zones', 

    array(
      'methods' => "GET",
      'callback' => 'woofood_api_shipping_zones',
      )
    );

}



//get all orders//
function woofood_api_shipping_zones( $request ) {
  global $woocommerce;
  $creds = array();
  $headers = getRequestHeaders();
  $shipping_zones= (array)WC_Shipping_Zones::get_zones();
  $shipping_zones_export = array();
  foreach($shipping_zones as $key=>$shipping_zone)
  {
    $shipping_methods = array();
    foreach($shipping_zone["shipping_methods"] as $key=>$current_method)
    {
      if($current_method->enabled =="yes")
      {
        $method = array();
        $method["id"] =  $current_method->id;

        $method["title"] =  $current_method->title;
        $method["cost"] =  (!$current_method->cost) ? 0 : $current_method->cost;

        if($current_method->id =="free_shipping")
        {

          $method["requires"] =  (!$current_method->requires) ? 0 : $current_method->requires;
          $method["min_amount"] =  (!$current_method->min_amount) ? 0 : $current_method->min_amount;

        }


        $shipping_methods[] = $method;


      }
    }
    $shipping_zone["shipping_methods"] =  $shipping_methods;
    $shipping_zones_export[] = $shipping_zone;

  }

  return $shipping_zones_export;

}

?>