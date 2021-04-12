<?php
add_action( 'rest_api_init', 'register_rest_woofood_users', 98);
function register_rest_woofood_users() {





  register_rest_route( 'woofood/v1', 'users/register', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_register_user',

      )
    );


  register_rest_route( 'woofood/v1', 'users/login', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_login_user',

      )
    );
  register_rest_route( 'woofood/v1', 'users/reset_password', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_reset_password',

      )
    );

register_rest_route( 'woofood/v1', 'users/edit_profile', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_edit_profile',

      )
    );


register_rest_route( 'woofood/v1', 'users/update_push_token', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_update_push_token',

      )
    );


register_rest_route( 'woofood/v1', 'users/addresses', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_user_addresses',

      )
    );


register_rest_route( 'woofood/v1', 'users/new-address', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_user_new_address',

      )
    );


register_rest_route( 'woofood/v1', 'users/delete-address', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_user_delete_address',

      )
    );

  register_rest_route( 'woofood/v1', 'users/orders', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_user_orders',

      )
    );


  register_rest_route( 'woofood/v1', 'users/place_order', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_user_place_order',

      )
    );

    register_rest_route( 'woofood/v1', 'users/check_order_status', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_check_order_status',

      )
    );


     register_rest_route( 'woofood/v1', 'users/update_order', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_update_order',

      )
    );



}






//REGISTER USER//
function woofood_api_register_user( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();
// Get username and password from the submitted headers.





if( isset( $request[ 'email' ] ) ) {

  //try to create the user//
  $user_id = username_exists( $request[ 'email' ] );
 // return $request[ 'email' ];
if ( !$user_id) {
  $user_id = wp_create_user($request[ 'email' ], $request[ 'password' ] , $request[ 'email' ] );
  
    if($user_id)
    {
            wp_update_user(array('ID' => $user_id,  'first_name' => $request["firstname"]));
            wp_update_user(array('ID' => $user_id,  'last_name' => $request["lastname"]));
            update_user_meta($user_id, 'billing_phone', $request["phone"] );
            update_user_meta($user_id, 'billing_first_name', $request["phone"] );
            update_user_meta($user_id, 'billing_last_name', $request["phone"] );

      $user  = wp_set_current_user( $user_id, $request[ 'email' ]);
      $user_response = array(
          'message' => 'Registration successful!',
          'username' => $user->username,
          'success' => true,
          'user_id' =>$user->ID,
          'role' => 'customer',
          'display_name' =>$user->display_name,
           'billing' => array(
            "first_name" =>$request["firstname"],
            "last_name" =>$request["lastname"],
            "phone" =>$request["phone"]


            ),
        );
return $user_response;
              // return $user_id;

    }

  
} else {
     return new WP_Error( 'already-exist', 'Username already exist.', array( 'status' => 400 /* Bad Request */ ) );

}
 //try to create the user//



}

else
{
	 return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );

}

//if we set id


}

//REGISTER USER//


//LOGIN USER//
function woofood_api_user_orders( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();


  if( isset( $request['logout'] ) && $request['logout'] == "true" ) {
        wp_logout();

        wp_send_json_success( array( 'message' => 'Successfully logged out.', 'logout' => true ) );
      }

 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );

// Get orders by customer with ID 12.
$args = array(
    'customer_id' => $user->ID,
);
$orders =  wc_get_orders( $args );

$all_orders_array = array();
foreach($orders as $current_order)
{
  $total_current_order = array();



  $current_order_items = array();


  foreach ( $current_order->get_items() as  $item_key => $item_values ) 
  {
    $item_data = (array) $item_values->get_data();
    if($item_data["meta_data"])
    {
      $item_meta_data =array ();
      foreach($item_data["meta_data"] as $current_meta)

      {
        if($current_meta->key ==="woofood_meta")
        {
          $item_meta_data[] = array("key"=>'woofood_meta', 'value'=>json_decode($current_meta->value));

        }
        else
        {
          $item_meta_data[] = $current_meta;
        }

      }


$item_data["meta_data"] = $item_meta_data;
    }
    $current_order_items[] = $item_data;
  }



  $total_current_order = (array) $current_order->get_data();
  $total_current_order["line_items"] = $current_order_items;




  $all_orders_array [] =  $total_current_order;

}

return rest_ensure_response( $all_orders_array );
//print_r($orders);
//return new WP_REST_Response($orders);
}

}

//LOGIN USER//


function woofood_api_check_order_status($request)
{
    global $woocommerce;
    $parameters = $request->get_json_params();

 if ( isset( $request[ 'id' ] ) ) 
 {
  $order = wc_get_order(intval($request[ 'id' ]));

  $order_status = $order->get_status();

  $minutes_to_arrive = get_post_meta(intval($request["id"]), 'minutes_to_arrive', true);
if($minutes_to_arrive=="")
  {
    $options_woofood = get_option('woofood_options');
    $minutes_to_arrive = $options_woofood["woofood_delivery_time"];
  }
if ($order_status =="accepting")
{
  $message = esc_html__('Please wait while your order is getting accepted', 'woofood-plugin');

}
if ($order_status =="processing" || $order_status =="completed")
{
$message = esc_html__('Your Order has been accepted', 'woofood-plugin');

}
if ($order_status =="cancelled")
{
$message = esc_html__('Sorry, Your Order has been declined', 'woofood-plugin');

}

  return array("status" => $order_status, "message"=> $message, "minutes"=>$minutes_to_arrive);

 }




}

function woofood_api_update_order($request)
{
    global $woocommerce;
    $parameters = $request->get_json_params();
$status =false;
 if ( isset( $request[ 'id' ] ) ) 
 {
  $order = wc_get_order(intval($request[ 'id' ]));

  $order_status = $order->get_status();
  if(isset( $request[ 'token' ]))
  {
    update_post_meta($order->get_id(), '_transaction_id',$request[ 'token' ] );
      $status =true;
  


  }
    if(isset( $request[ 'status' ]))
  {
      if($request[ 'status' ] =="cancelled")
      {
           if($order->update_status('cancelled'))
    {
      $status =true;
    }

      }


  }



  return array("status" => $status, "message"=> esc_html__('Your Order has been cancelled', 'woofood-plugin') );

 }




}

//add_action( 'rest_api_init', 'wp_rest_allow_all_cors' );
/**
 * Allow all CORS.
 *
 * @since 1.0.0
 */
function wp_rest_allow_all_cors() {

header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
header( 'Access-Control-Allow-Credentials: true' );


} // End fucntion wp_rest_allow_all_cors().

function woofood_api_login_user( $request ) {
   
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();


  if( isset( $request['logout'] ) && $request['logout'] == "true" ) {
        wp_logout();

        wp_send_json_success( array( 'message' => 'Successfully logged out.', 'logout' => true ) );
      }

 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );
$user_response["user_id"]  = $user->ID;
$user_response["username"]  = $user->username;

$fname = get_user_meta( $user->ID, 'first_name', true );
$lname = get_user_meta( $user->ID, 'last_name', true );
$address_1 = get_user_meta( $user->ID, 'billing_address_1', true ); 
$address_2 = get_user_meta( $user->ID, 'billing_address_2', true );
$city = get_user_meta( $user->ID, 'billing_city', true );
$postcode = get_user_meta( $user->ID, 'billing_postcode', true );
$state = get_user_meta( $user->ID, 'billing_state', true );
$pre_previously_stored_addresses = get_user_meta($user->ID, 'previously_stored_addresses', true);  
$phone = get_user_meta( $user->ID, 'billing_phone', true );

$user_response = array(
          'message' => 'Login successful!',
          'username' => $user->username,
          'success' => true,
          'user_id' =>$user->ID,
          'role' => 'customer',
          'display_name' =>$user->display_name,
          'billing' => array(
            "first_name" =>$fname,
            "last_name" =>$lname,
            "address_1" =>$address_1,
            "address_2" =>$address_2,
            "city" =>$city,
            "state" => $state,
            "postcode" =>$postcode,
            "phone" =>$phone


            ),
          "addresses" => $pre_previously_stored_addresses
        );
return $user_response;
}

}


function woofood_api_reset_password( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();



 if ( isset( $request[ 'email' ] ) ) 
  {   




    $user = get_user_by('email', $request["email"]);
    $firstname = $user->first_name;
    $email = $user->user_email;
    $adt_rp_key = get_password_reset_key( $user );
    $user_login = $user->ID;
    $rp_link = '<a href="' . get_permalink( wc_get_page_id( 'myaccount' ) )."/lost-password/?key=$adt_rp_key&id=" . rawurlencode($user_login) . '">' . get_permalink( wc_get_page_id( 'myaccount' ) )."/lost-password/?key=$adt_rp_key&id=" . rawurlencode($user_login) . '</a>';
https://demo.wpslash.com/woofood/my-account/lost-password/?key=q26RJ5Esm2ew2Oy0px7E&id=31
    $message = "Hi ".$firstname.",<br>";
    $message .= "A reset link has been creared on  ".get_bloginfo( 'name' )." for email address ".$email."<br>";
    $message .= "Click here to set the password for your account: <br>";
    $message .= $rp_link.'<br>';

    //deze functie moet je zelf nog toevoegen. 
   $subject = esc_html__("Reset Link  ".get_bloginfo( 'name'), "woofood-plugin");
   $headers = array();

   add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});
   $headers[] = 'From: '.get_bloginfo( 'name').'<'.get_bloginfo('admin_email').'>'."\r\n";
   $email_sent = wp_mail( $email, $subject, $message, $headers);

   // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
   remove_filter( 'wp_mail_content_type', 'set_html_content_type' );


   if($email_sent)
   {
    $user_response = array(
          'message' => esc_html__('Reset Email has been sent succesfully!', 'woofood-plugin'),
          'success' => true
 );
return $user_response;

   }

   


}

}




function woofood_api_user_addresses( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();


 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );



$previously_stored_addresses = get_user_meta($user->ID, 'previously_stored_addresses', true);  

return $previously_stored_addresses;
}

}




function woofood_api_user_new_address( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();




 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );
    $new_address = array();


$base_location = wc_get_base_location();
$new_address["billing_country"] =  $base_location["country"]; 
$options_woofood = get_option('woofood_options');
  $we_can_deliver = false;
  $woofood_google_distance_matrix_api_key = $options_woofood['woofood_google_distance_matrix_api_key'];
  $woofood_store_address = $options_woofood['woofood_store_address'];
  $woofood_max_delivery_distance = $options_woofood['woofood_max_delivery_distance'];
$woofood_auto_selected_store = "";
 $woofood_stores = array(); 
  //delivery distance check is not enabled lets check WooCommerce limitations//

    //get shipping zones//
    $shipping_zones=  (array) WC_Shipping_Zones::get_zones();
    $shipping_methods_accepted = array();
    $shipping_limitations = array();

    foreach($shipping_zones as $key=> $zone)
    {
  

    // echo $zone_key;
      
      //if empty// delivery accepted everywhere//
      if(empty($zone["zone_locations"]))
      {
        $shipping_methods = array();

        foreach($zone["shipping_methods"] as $method_key=>$current_method)
  {

    if($current_method->enabled =="yes")
    {
          $method = array();
          $method["id"] =  $current_method->id;
          $method["instance_id"] =  $current_method->instance_id;

          $method["title"] =  $current_method->title;
          if(isset($current_method->cost))
          {
          $method["cost"] =  (!$current_method->cost) ? 0 : $current_method->cost;

          }

          if($current_method->id =="free_shipping")
          {

          $method["requires"] =  (!$current_method->requires) ? 0 : $current_method->requires;
          $method["min_amount"] =  (!$current_method->min_amount) ? 0 : $current_method->min_amount;

          }





          $shipping_methods[] = $method;


    }



  }

  
            //$we_can_deliver = true;
          

           $shipping_limitations[] = array("zone_id" => $zone["id"], "limitations" => array(), "methods"=> $shipping_methods );

      }
      //if empty// delivery accepted everywhere//

      //we have limitations on this zone//
      else if(!empty($zone["zone_locations"]))
      {
        
        
      



        $zone_limits = array();
        foreach($zone["zone_locations"] as $location)
        {
          if($location->type=="country")
          {
            $zone_limits["country"][] =  $location->code;


          }
          else if($location->type =="state")
          {
            $zone_limits["state"][] =  $location->code;

          }
          else if($location->type=="postcode")
          {
            $postcode = preg_replace('/\s+/', '', $location->code);
            $zone_limits["postcode"][] = $postcode;

          }


        }

        $shipping_methods = array();
        foreach($zone["shipping_methods"] as $method_key=>$current_method)
  {
   if($current_method->enabled =="yes")
    {
          $method = array();
          $method["id"] =  $current_method->id;
          $method["instance_id"] =  $current_method->instance_id;

          $method["title"] =  $current_method->title;
          if(isset($current_method->cost))
          {


          $method["cost"] =  (!$current_method->cost) ? 0 : $current_method->cost;


          }

          if($current_method->id =="free_shipping")
          {

          $method["requires"] =  (!$current_method->requires) ? 0 : $current_method->requires;
          $method["min_amount"] =  (!$current_method->min_amount) ? 0 : $current_method->min_amount;

          }





          $shipping_methods[] = $method;


    }

  }

        $shipping_limitations[] = array("zone_id" => $zone["id"], "limitations" => $zone_limits, "methods"=> $shipping_methods );
   


      }


    }



    //end for each shipping method//


    if(!empty($shipping_limitations))
    {
      foreach($shipping_limitations as $limited_zone)
      {

        //if no limitations all shipping methods available //
        if(empty($limited_zone["limitations"]))
        {



          $shipping_methods_accepted[] = array("zone_id"=> $limited_zone["zone_id"], "methods"=> $limited_zone["methods"]);

        }
        //if no limitations all shipping methods available //

        //weh have limitations//
        else if(!empty($limited_zone["limitations"]))
        {



          $limitations_length = count($limited_zone["limitations"]);
          $passing_limitations = array();

          if($limited_zone["limitations"]["country"])
          {
            if(in_array($base_location["country"], $limited_zone["limitations"]["country"]) )
            {
                  $passing_limitations[] = "country";

            }
            else
            {

            }

          }

          if($limited_zone["limitations"]["state"])
          {

            if(in_array($base_location["country"].":".$base_location["state"], $limited_zone["limitations"]["state"]) )
            {
                  $passing_limitations[] = "state";

            }
            else
            {

            }

          }

           if($limited_zone["limitations"]["postcode"])
          {
            if(in_array(str_replace(" ", "",$request["postcode"]), $limited_zone["limitations"]["postcode"]) )
            {
                  $passing_limitations[] = "postcode";

            }
            else
            {

            }

          }

          if(count($passing_limitations) == $limitations_length)
          {
            $shipping_methods_accepted[] = array("zone_id"=> $limited_zone["zone_id"], "methods"=>$limited_zone["methods"]);

          }

          else
          {
            //limitations not passed//
          }





        }
        //we have limitations//



      }



    }

    if(!empty($shipping_methods_accepted))
    {

      $we_can_deliver = true;
    }
    else
    {
   
                    
                        

                
      $we_can_deliver = false;
    
    }



//if multistore is enabled//
if (class_exists("WooFood_Multistore_Settings"))
{
  $woofood_options_multistore = get_option('woofood_options_multistore');
  $woofood_auto_store_select = $woofood_options_multistore['woofood_auto_store_select'];
  $woofood_total_address = $request["address"].",".$request["city"].",".$request["postcode"].",".$new_address["billing_country"];
  
  $args2 = array(
      'posts_per_page' => - 1,
      'orderby'        => 'title',
      'order'          => 'asc',
      'post_type'      => 'extra_store',
      'post_status'    => 'publish',
      'meta_query' => array(
        array(
          'key' => 'extra_store_enabled'
          )
        )                  
      );
    $get_enabled_stores = get_posts( $args2 );


    $all_stores_with_addresses = array();

  foreach($get_enabled_stores as $current_enabled_store)

    {
        $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
        $current_store_max_delivery_distance = get_post_meta( $current_enabled_store->ID, 'extra_store_max_delivery_distance', true );

      if(!empty($current_store_max_delivery_distance) && !empty($woofood_google_distance_matrix_api_key) )
      {


      



        $current_customer_address= $woofood_total_address;



        $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($current_store_address)."&destinations=".urlencode($current_customer_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
        $details = htmlspecialchars_decode($details);
        $details = str_replace("&amp;", "&", $details );
        $json = file_get_contents($details);

        $details = json_decode($json, TRUE);




        if ($details['rows'][0]['elements'][0]['distance']['value'] < $current_store_max_delivery_distance *1000)
        {
            //We this store can deliver /// 
          $all_stores_with_addresses[$current_enabled_store->ID] = $current_store_max_delivery_distance;



            }//end if



            //No available stores for this location//
            else {



            }//end else 





      }


      else
      {
          $current_store_address = get_post_meta( $current_enabled_store->ID, 'extra_store_address', true );
          $current_store_max_delivery_distance = get_post_meta( $current_enabled_store->ID, 'extra_store_max_delivery_distance', true );

          $all_stores_with_addresses[$current_enabled_store->ID] = $current_store_max_delivery_distance;



      }
      





}//end foreach store


if (!empty($all_stores_with_addresses))
{
  $we_can_deliver =true;


  $store_name_array = array_keys($all_stores_with_addresses, min($all_stores_with_addresses));  
  $woofood_stores = array_keys($all_stores_with_addresses);
  if($woofood_auto_store_select)
  {
  $woofood_auto_selected_store = $store_name_array[0];


  }



}//end if
else
{

$we_can_deliver =false;



}



 



}
//if multistore is enabled//


//is single store//
else
{


    //check delivery distance if key has been specified single store//

  if($woofood_google_distance_matrix_api_key!="" && $woofood_max_delivery_distance!="" )
  {
    $woofood_total_address = $request["address"].",".$request["city"].",".$request["postcode"].",".$new_address["billing_country"];

  $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($woofood_store_address)."&destinations=".urlencode($woofood_total_address)."&mode=driving&sensor=false&key=".$woofood_google_distance_matrix_api_key;
  $details = htmlspecialchars_decode($details);
  $details = str_replace("&amp;", "&", $details );
  $json = file_get_contents($details);

  $details = json_decode($json, TRUE);
if(!empty($details['rows'][0]['elements']) && $details['rows'][0]['elements'][0]["status"]!="ZERO_RESULTS" )
{

   if ($details['rows'][0]['elements'][0]['distance']['value'] < $woofood_max_delivery_distance *1000)
  {
//We can deliver /// 
 $we_can_deliver =true;

  }
  else
  {

  $we_can_deliver =false;


  }





}
else
{
    $we_can_deliver =false;

}




}    

  //check delivery distance if key has been specified multistore//




}

//if is single store//





if(isset($request["first_name"]) && $we_can_deliver)
{
      wp_update_user(array('ID' => $user->ID,  'first_name' => $request["first_name"]));
}

if(isset($request["last_name"]) && $we_can_deliver)
{
      wp_update_user(array('ID' => $user->ID,  'last_name' => $request["last_name"]));
}

if(isset($request["address"]) && $we_can_deliver)
{
  update_user_meta( $user->ID, 'billing_address_1', $request["address"]);
  $new_address["billing_address_1"] = $request["address"];

}
if(isset($request["city"]) && $we_can_deliver)
{
  update_user_meta( $user->ID, 'billing_city', $request["city"]);
    $new_address["billing_city"] = $request["city"];

}
if(isset($request["postcode"]) && $we_can_deliver)
{
  update_user_meta( $user->ID, 'billing_postcode', $request["postcode"]);
    $new_address["billing_postcode"] = $request["postcode"];

}


if(isset($request["doorbell"]) && $we_can_deliver)
{
  $new_address["doorbell"] = $request["doorbell"];

  //update_user_meta( $user->ID, 'do', $request["postcode"]);
}

if($we_can_deliver)
{
    $new_address["methods"] = $shipping_methods_accepted;
    if(class_exists('WooFood_Multistore_Settings') && !empty($woofood_stores))

    {
        $new_stores = array();
        foreach($woofood_stores as $store)
        {
          $new_stores[] = array("id"=>$store, "name"=>get_the_title($store));
        }

          $new_address["order_types"][] = array( "key"=> "delivery", "name"=> "Delivery", "stores"=> $new_stores);
          $new_address["order_types"][] = array( "key"=> "pickup", "name"=> "Pickup", "stores"=> $new_stores);

          if($woofood_auto_store_select)
          {
             $new_address["default_store"] =$woofood_auto_selected_store;

          }



    }
}




    $previously_stored_addresses = get_user_meta($user->ID, 'previously_stored_addresses', true);  
    if(is_array($previously_stored_addresses))
    {

    }
    else
    {
      $previously_stored_addresses = array();
    }
    $previously_stored_addresses[] =$new_address;



  if($we_can_deliver)
  {
        
        update_user_meta( $user->ID, 'previously_stored_addresses',  $previously_stored_addresses);

  }    



$fname = get_user_meta( $user->ID, 'first_name', true );
$lname = get_user_meta( $user->ID, 'last_name', true );
$address_1 = get_user_meta( $user->ID, 'billing_address_1', true ); 
$address_2 = get_user_meta( $user->ID, 'billing_address_2', true );
$city = get_user_meta( $user->ID, 'billing_city', true );
$postcode = get_user_meta( $user->ID, 'billing_postcode', true );
$state = get_user_meta( $user->ID, 'billing_state', true );
$message ="";
 if($we_can_deliver)
  {
    $message = esc_html__('Address has been Successfully', 'woofood-plugin');
  }
  else
  {
    $message = esc_html__('Sorry we cannot deliver to this address', 'woofood-plugin');

  }


$user_response = array(
          'message' =>$message,
          'username' => $user->username,
          'success' => true,
          'we_can_deliver' => $we_can_deliver,
          'user_id' =>$user->ID,
          'role' => 'customer',
          'display_name' =>$user->display_name,
          'billing' => array(
            "first_name" =>$fname,
            "last_name" =>$lname,
            "address_1" =>$address_1,
            "address_2" =>$address_2,
            "city" =>$city,
            "state" => $state,
            "postcode" =>$postcode


            ),
          "addresses" =>$previously_stored_addresses
        );


return $user_response;

}

}




function woofood_api_user_delete_address( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();




 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );
$address_to_delete = array();




if(isset($request["address"]))
{
  $address_to_delete["billing_address_1"] = $request["address"];

}
if(isset($request["city"]))
{
   $address_to_delete["billing_city"] = $request["city"];


}
if(isset($request["postcode"]))
{
   $address_to_delete["billing_postcode"] = $request["postcode"];


}


if(isset($request["doorbell"]))
{
  $address_to_delete["doorbell"] = $request["doorbell"];

  //update_user_meta( $user->ID, 'do', $request["postcode"]);
}
$base_location = wc_get_base_location();
$address_to_delete["billing_country"] =  $base_location["country"]; 


$previously_stored_addresses = get_user_meta($user->ID, 'previously_stored_addresses', true);  

$final_stored_addresses = array();
foreach($previously_stored_addresses as $current_address)
{        unset($current_address["methods"]);
 unset($current_address["stores"]);
  unset($current_address["default_store"]);
  unset($current_address["order_types"]);

  if($current_address == $address_to_delete)
  {

  }
  else
  {
    $final_stored_addresses[] = $current_address;
  }

}
    update_user_meta( $user->ID, 'previously_stored_addresses',  $final_stored_addresses);





return $final_stored_addresses;
}

}




function woofood_api_edit_profile( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();




 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );
$user_response["user_id"]  = $user->ID;
$user_response["username"]  = $user->username;
if(isset($request["first_name"]))
{
  update_user_meta( $user->ID, 'first_name', $request["first_name"]);
}

if(isset($request["last_name"]))
{
  update_user_meta( $user->ID, 'last_name', $request["last_name"]);
}
if(isset($request["address"]))
{
  update_user_meta( $user->ID, 'billing_address_1', $request["address"]);
}
if(isset($request["city"]))
{
  update_user_meta( $user->ID, 'billing_city', $request["city"]);
}
if(isset($request["postcode"]))
{
  update_user_meta( $user->ID, 'billing_postcode', $request["postcode"]);
}
if(isset($request["phone"]))
{
  update_user_meta( $user->ID, 'billing_phone', $request["phone"]);
}
$fname = get_user_meta( $user->ID, 'first_name', true );
$lname = get_user_meta( $user->ID, 'last_name', true );
$address_1 = get_user_meta( $user->ID, 'billing_address_1', true ); 
$address_2 = get_user_meta( $user->ID, 'billing_address_2', true );
$city = get_user_meta( $user->ID, 'billing_city', true );
$postcode = get_user_meta( $user->ID, 'billing_postcode', true );
$state = get_user_meta( $user->ID, 'billing_state', true );
$phone = get_user_meta( $user->ID, 'billing_phone', true );

$user_response = array(
          'message' => 'Login successful!',
          'username' => $creds['user_login'],
          'success' => true,
          'user_id' =>$user->ID,
          'role' => 'customer',
          'display_name' =>$user->display_name,
          'billing' => array(
            "first_name" =>$fname,
            "last_name" =>$lname,
            "address_1" =>$address_1,
            "address_2" =>$address_2,
            "city" =>$city,
            "state" => $state,
            "postcode" =>$postcode,
            "phone" =>$phone


            )
        );
return $user_response;
}

}



//get user orders//


function woofood_api_update_push_token( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();




 if ( isset( $request[ 'email' ] ) ) 
  {    $creds['user_login'] =  $request["email"];

    $creds['user_password'] =  $request["password"];
    $creds['remember'] = false;
$user = wp_signon( $creds, false );  // Verify the user.
// TODO: Consider sending custom message because the default error
// message reveals if the username or password are correct.
if ( is_wp_error($user) ) {

$user_response = array(
          'message' => $user->get_error_message(),
          'success' => false,
        );
return $user_response;
//  return $user;
}

$user  = wp_set_current_user( $user->ID, $user->user_login );
$user_response["user_id"]  = $user->ID;
$user_response["username"]  = $user->username;
if(isset($request["token"]))
{
  update_user_meta( $user->ID, 'push_token', $request["token"]);
}

$user_response = array(
          'message' => 'Updated',
          'success' => true
        );
return $user_response;
}

}



function woofood_api_user_place_order( $request ) {
  global $woocommerce;
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getrequestheaders();

$options_woofood = get_option('woofood_options');

$woofood_enable_order_accepting = $options_woofood['woofood_enable_order_accepting'];

/*if delivery time has been set*/
$default_status = array();
if($woofood_enable_order_accepting!=0) {
  $default_status = array('status'=>'accepting');

}
else
{
  
     $default_status = array('status'=>'processing');



}

$order = wc_create_order($default_status);
    $order->set_address($parameters["billing"], 'billing');
    $order->set_address($parameters["shipping"], 'shipping');
    update_post_meta($order->id, '_customer_user', $parameters["customer_id"]);


foreach ($parameters["line_items"] as $lineitem) {
    //need to get Add-On data in here somehow
  $product_id = null;
  if($lineitem["variation_id"]>0)
  {
  $product_id = $lineitem["variation_id"];

  }
  else
  {
      $product_id = $lineitem["product_id"];


  }
    $item_id = $order->add_product(wc_get_product($product_id), $lineitem["quantity"],[
    'subtotal'     => $lineitem["subtotal"], // e.g. 32.95
    'total'        => $lineitem["total"], // e.g. 32.95
] );
    $item = $order->get_item($item_id);
    $woofood_meta = array();
    $extra_options_array = array();
    $additional_comments_text = "";
    if($lineitem["extra_options"])
    {
      foreach($lineitem["extra_options"] as $current_extra_option_category)
      {

        foreach($current_extra_option_category["options"] as $current_extra)
        {
          $extra_options_array[] = $current_extra;
            woocommerce_add_order_item_meta( $item_id, $current_extra["name"],html_entity_decode(strip_tags(wc_price($current_extra["price"], ENT_COMPAT, 'UTF-8'))));



        }



      }

     

    }



    if($lineitem["additional_comments"])
    {
                  $additional_comments_text = $lineitem["additional_comments"];
                  woocommerce_add_order_item_meta( $item_id, esc_html__('Additional Comments','woofood-plugin'), $lineitem["additional_comments"]);


    }

      $woofood_meta["extra_options"] = $extra_options_array;
      $woofood_meta["additional_comments"] = $additional_comments_text;

            woocommerce_add_order_item_meta( $item_id, "woofood_meta", json_encode($woofood_meta));


}   

  foreach($parameters["shipping_lines"] as $shipping_line)
  {
    $item = new WC_Order_Item_Shipping();

//$item->set_method_title( $shipping_line["method_title"] );
$item->set_method_title( $shipping_line["method_title"]);

    $item->set_method_id( $shipping_line["method_id"] ); // set an existing Shipping method rate ID
    $item->set_total( floatval($shipping_line["total"]) ); // (optional)

      $order->add_item( $item );




  }
  $order->calculate_totals();
$order->update_status('on-hold');
if($woofood_enable_order_accepting!=0) {
$order->update_status('accepting');


}
else
{
  
$order->update_status('processing');




}

if($parameters["store"])
{
      $order->update_meta_data( 'extra_store_name', sanitize_text_field( $parameters["store"] ));

}

  $order->save();

update_post_meta($order->get_id(), '_payment_method',$parameters["payment_method"] );




$order_status = $order->get_status();

  $minutes_to_arrive = get_post_meta( $order->get_id(), 'minutes_to_arrive', true);
if($minutes_to_arrive=="")
  {
    $options_woofood = get_option('woofood_options');
    $minutes_to_arrive = $options_woofood["woofood_delivery_time"];
  }


  return array( "id"=>$order->get_id(), "status" => $order_status, "message"=> esc_html__('Your Order has been accepted', 'woofood-plugin'), "minutes"=>$minutes_to_arrive);

}

//create user order//






?>