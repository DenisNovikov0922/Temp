<?php

add_action( 'rest_api_init', 'register_rest_woofood_delivery_boys');
function register_rest_woofood_delivery_boys() {


	  register_rest_route( 'woofood/v1', 'deliveryboys', 


    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'get_delivery_boys',


      )      
    );



	}


	function get_delivery_boys( $request ) {
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
    $store_name = $stores[0]->post_title;

  }

}



// Get orders on hold.
$args = array(
  'role__in' => array('deliveryboy_user')
  );

$users = get_users( $args );
return rest_ensure_response( $users );

}
}
?>