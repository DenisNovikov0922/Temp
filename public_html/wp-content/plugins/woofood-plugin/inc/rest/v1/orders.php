<?php

function getRequestHeaders() {
  $headers = array();
  foreach($_SERVER as $key => $value) {
    if (substr($key, 0, 5) <> 'HTTP_') {
      continue;
    }
    $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
    $headers[$header] = $value;
  }
  return $headers;
}

add_action( 'rest_api_init', 'register_rest_woofood_orders');
function register_rest_woofood_orders() {



  register_rest_route( 'woofood/v1', 'orders', 


    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'woofood_rest_get_orders',


      )      
    );






   register_rest_route( 'woofood/v1', 'orders/statuses', 


    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'woofood_rest_get_order_statuses',


      )      
    );


    register_rest_route( 'woofood/v1', 'orders/force_disable_status', 


    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'force_disable_status',


      )      
    );


  register_rest_route( 'woofood/v1', 'orders/update', 





    array(
      'methods' => "POST",
      'callback' => 'update_order',

      )
    );



    register_rest_route( 'woofood/v1', 'orders/reports', 





    array(
      'methods' => "POST",
      'callback' => 'fetch_reports',

      )
    );

  register_rest_route( 'woofood/v1', 'orders/delete', 





    array(
      'methods' => "POST",
      'callback' => 'delete_order',

      )










    );





   register_rest_route( 'woofood/v1', 'orders/force_disable', 





    array(
      'methods' => "POST",
      'callback' => 'force_disable',

      )










    );



}




//get all orders//
function force_disable_status( $request ) {
    ob_clean();
  global $woocommerce;
  $creds = array();
//$headers = getrequestheaders();
  $headers = getRequestHeaders();
  $woofood_options = get_option('woofood_options');
  $woofood_force_disable_pickup_option = isset($woofood_options["woofood_force_disable_pickup_option"]) ? $woofood_options["woofood_force_disable_pickup_option"] :0 ;
  $woofood_force_disable_delivery_option = isset($woofood_options["woofood_force_disable_delivery_option"]) ? $woofood_options["woofood_force_disable_delivery_option"] :0 ;


// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.

// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  return $user->get_error_message();
//return $user;
}

//wp_set_current_user( $user->ID, $user->user_login );
$user_roles = $user->roles;
if ( !in_array( 'administrator', $user_roles, true ) && !in_array( 'shop_manager', $user_roles, true ) &&  !in_array( 'multistore_user', $user_roles, true ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
//return  $user_roles;
}

}
  $response = array();
    $response["delivery"] = $woofood_force_disable_delivery_option;
    $response["pickup"] = $woofood_force_disable_pickup_option;

return rest_ensure_response( $response );

}


function woofood_rest_get_order_statuses( $request ) {
    ob_clean();
  global $woocommerce;
  $creds = array();
//$headers = getrequestheaders();
  $headers = getRequestHeaders();


// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.

// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  return $user->get_error_message();
//return $user;
}

//wp_set_current_user( $user->ID, $user->user_login );
$user_roles = $user->roles;
if ( !in_array( 'administrator', $user_roles, true ) && !in_array( 'shop_manager', $user_roles, true ) &&  !in_array( 'multistore_user', $user_roles, true ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
//return  $user_roles;
}

}
  $response = wc_get_order_statuses();
unset($response["wc-pending"]);
unset($response["wc-on-hold"]);
unset($response["wc-cancelled"]);
unset($response["wc-refunded"]);
unset($response["wc-accepting"]);
unset($response["wc-processing"]);
unset($response["wc-failed"]);
unset($response["wc-completed"]);

return rest_ensure_response( $response );

}












//get all orders//
function woofood_rest_get_orders( $request ) {
    ob_clean();
  global $woocommerce;
  $creds = array();
//$headers = getrequestheaders();
  $headers = getRequestHeaders();
  $woofood_options = get_option('woofood_options');
  $woofood_woocommerce_product_addons_compatibility_enabled = isset($woofood_options["woofood_woocommerce_product_addons_compatibility_enabled"]) ? $woofood_options["woofood_woocommerce_product_addons_compatibility_enabled"] :null ;


// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.

// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  return $user->get_error_message();
//return $user;
}
$search_orders = false;
$query = "";
  if($request->get_param( 's' ))
  {

    $search_orders = true;
    $query =  $request->get_param( 's' );
  }


//wp_set_current_user( $user->ID, $user->user_login );
$user_roles = $user->roles;
if ( !in_array( 'administrator', $user_roles, true ) && !in_array( 'shop_manager', $user_roles, true ) &&  !in_array( 'multistore_user', $user_roles, true ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
//return  $user_roles;
}


$store_name ="";
$stores = array();
if(in_array( 'multistore_user', $user_roles, true ))
{

  $args_stores = array(
    'post_type'        => 'extra_store',

    'meta_query' => array(
      array(
        'key' => 'extra_store_user',
        'value' => $user->ID,
        'compare' => '=='
        )
      )
    );
  $stores = get_posts($args_stores);
  if(!empty($stores))
  {
    $store_name = $stores[0]->ID;

  }

}


$orders = null ;
// Get orders on hold.






if($search_orders)
{

  $search_arguments = array(
        'post_type' => 'shop_order',
        'post_status' => wc_get_order_statuses(), //get all available order statuses in an array
        'posts_per_page' => 30, // query all orders
        'fields' => 'ids', // query all orders



    );


if(is_numeric($query))
{
  $search_arguments["include"] = array($query);
}
else
{
  $search_arguments['meta_query'] = array(
        'relation' => 'OR',
        array(
            'key'     => '_billing_first_name',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
       array(
            'key'     => '_billing_last_name',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
          array(
            'key'     => '_billing_city',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
       array(
            'key'     => '_billing_address_1',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
        array(
            'key'     => '_billing_postcode',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
         array(
            'key'     => '_billing_phone',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
         array(
            'key'     => '_shipping_city',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
       array(
            'key'     => '_shipping_address_1',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
        array(
            'key'     => '_shipping_postcode',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
         array(
            'key'     => '_shipping_phone',
            'value'   => $query,
            'compare' => 'LIKE',
        ),
    );


}


 $orders= get_posts($search_arguments);

 }
 else
 {
  $args = array(
  'status' => apply_filters('wpslash_autoprint_orders_statuses', array('processing', 'accepting')),
  'extra_store_name' => $store_name,
  'return' => 'ids',
  'limit' => apply_filters('wpslash_autoprint_orders_limit', 10)

  //'limit'=>30

  );
$orders = wc_get_orders( $args );

 }



$all_orders_array = array();
foreach($orders as $order_id)
{
  //delete_transient("woofood_rest_order_id_".$order_id);

 $cached_order =  get_transient("woofood_rest_order_id_".$order_id);
  if($cached_order)
  {
    $total_current_order = $cached_order ;
  }
  else
  {

  $current_order  = wc_get_order($order_id);



  $total_current_order = array();



  $current_order_items = array();


  foreach ( $current_order->get_items() as $item_values ) 
  {
      
   

    $item_data = (array) $item_values->get_data();

    $item_data['product_price_with_tax'] = html_entity_decode(strip_tags(wc_price((($item_data['total']+$item_data['total_tax']) /$item_data['quantity']) )), ENT_COMPAT, 'UTF-8');
    $item_data['product_price_without_tax'] = html_entity_decode(strip_tags(wc_price(($item_data['total']/$item_data['quantity']) )), ENT_COMPAT, 'UTF-8');

    if($item_data['total_tax'] > 0)
    {
        $item_data['total_without_tax'] = html_entity_decode(strip_tags(wc_price($item_data['total'])), ENT_COMPAT, 'UTF-8');
  
    }
       if($item_data['subtotal_tax'] > 0)
    {
        $item_data['subtotal_without_tax'] = html_entity_decode(strip_tags(wc_price($item_data['subtotal'])), ENT_COMPAT, 'UTF-8');
  
    }
    $item_data['subtotal'] = html_entity_decode(strip_tags(wc_price(($item_data['subtotal']+$item_data['subtotal_tax']) )), ENT_COMPAT, 'UTF-8');

    $item_data['total'] = html_entity_decode(strip_tags(wc_price(($item_data['total']+$item_data['total_tax']) )), ENT_COMPAT, 'UTF-8');


    $attributes = array();
    if($item_data['product_id'] >0)
    {

    $productsssss = wc_get_product($item_data['product_id']);
    
    $attributes = $productsssss->get_attributes();

    }
    



    $woofood_meta = null;
    $variation_data = array();
    $custom_meta = array();
   $custom_meta_enabled = true;
    $new_meta = array();
    foreach( $item_data['meta_data'] as $current_meta)
    {
      if (strpos($current_meta->key, 'pa_') !== false) {

          $term_obj  = get_term_by('slug', $current_meta->value, $current_meta->key);

        $term_id   = $term_obj->term_id; // The ID  <==  <==  <==  <==  <==  <==  HERE
        $term_name = $term_obj->name; // The Name

        $variation_data[] = array( "id"=>null, "name"=>wc_attribute_label($current_meta->key), "price"=> $term_name);
      }

       if(array_key_exists($current_meta->key, $attributes) && strpos($current_meta->key, 'pa_') === false) {

       $current_attribute = $attributes[$current_meta->key];

        $variation_data[] = array( "id"=>null, "name"=>$current_attribute->get_name(), "price"=> $current_meta->value);
      }
      else if($woofood_woocommerce_product_addons_compatibility_enabled  && strpos($current_meta->key, 'pa_') === false && strpos($current_meta->key, 'woofood') === false && strpos($current_meta->key, '_') === false)
      {
        $variation_data[] = array( "id"=>null, "name"=>$current_meta->key, "price"=> $current_meta->value);

      }

       if ($current_meta->key =="woofood_meta") {
          $woofood_meta = json_decode($current_meta->value, true);

      }


    }



    if(!empty($variation_data))
    {
      if(isset($woofood_meta["extra_options"]))
      {

            // array_unshift($woofood_meta["extra_options"], $variation_data) ;
             $woofood_meta["extra_options"] =  array_merge($variation_data, $woofood_meta["extra_options"]);

      }
      else
      { 
        $woofood_meta["extra_options"] = $variation_data;
      }


       foreach( $item_data['meta_data'] as $current_meta)
    {
     

       if ($current_meta->key =="woofood_meta") {

      }
      else
      {
         $new_meta[] = $current_meta;
      }


    }

    $new_meta[] = array("key"=>"woofood_meta", "value"=>json_encode($woofood_meta));
    $item_data['meta_data'] = $new_meta;
    }

    $current_order_items[] = $item_data;
  }



  $total_current_order = (array) $current_order->get_data();
  $total_current_order["line_items"] = $current_order_items;
  $total_current_order["total"] = html_entity_decode(strip_tags(wc_price($total_current_order["total"])), ENT_COMPAT, 'UTF-8');
  $total_current_order["discount_total"] = html_entity_decode(strip_tags(wc_price($total_current_order["discount_total"])), ENT_COMPAT, 'UTF-8');
  if( $total_current_order["total_tax"] > 0)
  {
    $total_current_order["total_tax"] = html_entity_decode(strip_tags(wc_price($total_current_order["total_tax"])), ENT_COMPAT, 'UTF-8');
  
  }
  else
  {
    unset($total_current_order["total_tax"]); 

  }

  //$total_current_order["total"] = html_entity_decode(strip_tags(wc_price($total_current_order["total"])), ENT_COMPAT, 'UTF-8');
  $date_format = get_option( 'date_format' );
  $date_format =$date_format." H:i:s"; 
  $date_created_array = (array) $total_current_order["date_created"];
  $date_created_array["date"] =str_replace(".000000", "",   $total_current_order["date_created"]->date($date_format));
  $total_current_order["date_created"] =     $date_created_array;
  $order_type_slug = "";
  $new_order_meta = array();
        foreach( $total_current_order['meta_data'] as $current_meta)
    {
     

       if ($current_meta->key =="woofood_order_type") {
        $order_type_slug =  $current_meta->value;
         $new_order_meta[] = array("key"=>"woofood_order_type", "value"=>woofood_get_order_type_by_key($current_meta->value));

      }
      elseif( $current_meta->key =="woofood_time_to_deliver")
      {
        $new_order_meta[] = array("key"=>"woofood_time_to_deliver", "value"=>woofood_get_time_by_meta_value($current_meta->value));

      }
      else
      {
         $new_order_meta[] = $current_meta;
      }


    }
      $total_current_order["order_type_slug"] = $order_type_slug;

$total_current_order['meta_data'] = $new_order_meta;
$total_current_order["status_nice"] = wc_get_order_status_name($total_current_order["status"]);

  foreach( $current_order->get_items(array('fee', 'shipping')) as $item_id => $item_fee ){

    // The fee name
    $fee_name = $item_fee->get_name();

    // The fee total amount
    $fee_total = $item_fee->get_total();

    // The fee total tax amount
    $fee_total_tax = $item_fee->get_total_tax();

    $current_fee =  array("name"=> $fee_name , "total"=> html_entity_decode(strip_tags(wc_price($fee_total+$fee_total_tax)), ENT_COMPAT, 'UTF-8'), "total_without_tax"=> html_entity_decode(strip_tags(wc_price($fee_total)), ENT_COMPAT, 'UTF-8'), "tax"=>html_entity_decode(strip_tags(wc_price($fee_total_tax)), ENT_COMPAT, 'UTF-8')   );

    $total_current_order["fee_lines"][]= $current_fee;

}

/*  foreach( $current_order->get_items('shipping') as $item_id => $item_fee ){

    // The fee name
    $fee_name = $item_fee->get_name();

    // The fee total amount
    $fee_total = $item_fee->get_total();

    // The fee total tax amount
    $fee_total_tax = $item_fee->get_total_tax();

    $current_fee =  array("name"=> $fee_name , "total"=> html_entity_decode(strip_tags(wc_price($fee_total)), ENT_COMPAT, 'UTF-8')  );

    $total_current_order["fee_lines"][]= $current_fee;

}*/

  set_transient("woofood_rest_order_id_".$order_id, $total_current_order, 3600 );



  }


  $all_orders_array [] =  $total_current_order;



} //end foreach 

return rest_ensure_response( $all_orders_array );









}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}
//get all orders//






//GET ORDER//
function get_order( $request ) {
    ob_clean();
  global $woocommerce;
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.

// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}






if( isset( $request[ 'id' ] ) ) {

  $order_id = intval($request[ 'id' ]);
  $order = new WC_Order($order_id);
  $order_array =  (array) $order->get_data();

  $order_items = array();


  foreach ( $order->get_items() as  $item_key => $item_values ) 
  {
    $item_data = $item_values->get_data();
    $order_items[] = $item_data;
  }



  $order_array["line_items"] = $order_items;
  return rest_ensure_response( $order_array );

}
else

{
  return new WP_Error( 'no-id', 'You must specify a valid order id.', array( 'status' => 400 /* Bad Request */ ) );


}


return rest_ensure_response( $extra_option_data );







}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}
//GET ORDER//
function woofood_get_order_statuses() {
    ob_clean();
  global $woocommerce;
  $order_statuses = wc_get_order_statuses();
  $statuses = array();
  foreach ( $order_statuses as $status ) {
    $statuses[] = $status;
  }
  return $statuses;
}
//UPDATE ORDER//
function update_order( $request ) {
    ob_clean();
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
$extra_option_id = 0;
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'id' ] ) ) {

  $order_id = intval($request[ 'id' ]);

  //$order_array =  (array) $order->get_data();

  //$order_items = array();

  //$needs_payment = get_post_meta($order_id, 'woofood_order_need_payment', true);

//update order status//
  if( isset( $request[ 'status' ] ) ) 
  {
      $order = new WC_Order($order_id);


     if($request['minutes'])

        {  

          update_post_meta(intval($order_id ), 'minutes_to_arrive', $request['minutes']);

        }

      if($request[ 'status' ] =="processing") 
      {
        $request[ 'status' ] ="pending";
      }

    if($order->update_status($request[ 'status' ]))
    {
      if($request[ 'status' ] =="processing")
      {
       
          

      }

      if (isset( $request[ 'printed' ]))
  {

    update_post_meta(intval($order_id ), 'printed', $request[ 'printed' ]);



  }
    delete_transient("woofood_rest_order_id_".$order_id);

      return rest_ensure_response( "success" );

    }
    else
    {
      return new WP_Error( 'invalid-status', 'Specify a valid status.', array( 'status' => 400 /* Bad Request */ ) );


    }



  }


  if (isset( $request[ 'printed' ]))
  {

    update_post_meta(intval($order_id ), 'printed', $request[ 'printed' ]);
    delete_transient("woofood_rest_order_id_".$order_id);


    return rest_ensure_response( "success" );




  }

   if (isset( $request[ 'deliveryboy' ]))
  {

    update_post_meta(intval($order_id ), 'wf_deliveryboy', $request[ 'deliveryboy' ]);

    delete_transient("woofood_rest_order_id_".$order_id);

    return rest_ensure_response( "success" );




  }

  else
  {
    return new WP_Error( 'invalid-status', 'Specify a valid status.', array( 'status' => 400 /* Bad Request */ ) );


  }


//update order status//





}

//if we set id








}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}

//UPDATE ORDER//











//FETCH REPORTS//
function fetch_reports( $request ) {
    ob_clean();
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
$extra_option_id = 0;
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'date_from' ] ) && isset( $request[ 'date_from' ] ) && isset( $request[ 'deliveryboy' ] )  ) {

$date_from = $request[ 'date_from' ];
$date_to = $request[ 'date_to' ];
$delivery_boy= $request[ 'deliveryboy' ];

$delivery_name = "All";
$date_from = str_replace('/', '-', $date_from );
$new_date_from = date("Y-m-d", strtotime($date_from));

$date_to = str_replace('/', '-', $date_to)." 00:00:00";
$new_date_to = date("Y-m-d", strtotime($date_to))." 23:59:59";



  $args = array(
        // WC orders post type
        'post_type'   => 'shop_order',
        // Only orders with status "completed" (others common status: 'wc-on-hold' or 'wc-processing')
        'post_status' => array( 'wc-completed' ),
        // all posts
        'numberposts' => -1,
       'date_query' => array(
    array(
      'after' =>  date('Y-m-d H:i:s',strtotime($new_date_from)),
      'before' =>  date('Y-m-d H:i:s',strtotime($new_date_to)),
    ),
  ),
    );


  if($delivery_boy =="all")
{
}
else
{
  $delivery_user = get_user_by("ID", $delivery_boy );
  $delivery_name = $delivery_user->display_name;
   $args["meta_key"] ='wf_deliveryboy';
   $args["meta_value"] =$delivery_boy;

  
}


    // Get all customer orders
    $customer_orders = get_posts( $args );
    $count = count($customer_orders);
    $orders_total = 0;

    $order_values= array();

       foreach ( $customer_orders as $customer_order ){
           
                $order = new WC_Order( $customer_order->ID );
                $orders_total += (float) $order->get_total();
                // Going through each current customer items in the order
               
        }

      $orders_total = html_entity_decode(strip_tags(wc_price($orders_total)), ENT_COMPAT, 'UTF-8');  

          $export_array = array("date_from" =>$new_date_from, "date_to"=> $new_date_to, "delivery_name"=>$delivery_name, "total"=>$orders_total );
            return rest_ensure_response($export_array);


}
else
{
  return new WP_Error( 'invalid-method', 'You must specify a valid date_from  date_to and deliveryboy.', array( 'status' => 400 /* Bad Request */ ) );

}

//if we set id








}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}

//FETCH REPORTS//


//DELETE ORDER//
function delete_order( $request ) {
    ob_clean();
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
$extra_option_id = 0;
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'id' ] ) ) {

  $order_id = intval($request[ 'id' ]);
  delete_transient("woofood_rest_order_id_".$order_id);

  wp_delete_post($order_id,false);
  return rest_ensure_response( "deleted" );




//update order status//





}

//if we set id








}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}





function force_disable( $request ) {
    ob_clean();
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.
  if ( array_key_exists( 'Username', $headers ) && array_key_exists( 'Password', $headers ) ) {
    $creds['user_login'] = $headers["Username"];
    $creds['user_password'] =  $headers["Password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
$extra_option_id = 0;
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {
  echo $user->get_error_message();
  return $user;
}

wp_set_current_user( $user->ID, $user->user_login );

// A subscriber has 'read' access so a very basic user account can be used.
if ( ! current_user_can( "manage_woocommerce" ) ) {
  return new WP_Error( 'rest_forbidden', 'You do not have permissions to view this data.', array( 'status' => 401 ) );
}





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'order_type' ] ) ) {
  $woofood_options = get_option("woofood_options");
  $order_type = $request[ 'order_type' ];
  if($order_type =="delivery")
  {
    if($request[ 'status' ] =="enable")
    {
      $woofood_options["woofood_force_disable_delivery_option"] = 1;

    }
    if($request[ 'status' ] =="disable")
    {
      $woofood_options["woofood_force_disable_delivery_option"] = 0;

    }

  }
  else if($order_type =="pickup")
  {
    if($request[ 'status' ] =="enable")
    {
      $woofood_options["woofood_force_disable_pickup_option"] = 1;
    }
    if($request[ 'status' ] =="disable")
    {
      $woofood_options["woofood_force_disable_pickup_option"] = 0;

    }

  }




update_option( "woofood_options", $woofood_options );


  return rest_ensure_response($request[ 'status' ]);




//update order status//





}

//if we set id








}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}

//DELETE ORDER//


?>