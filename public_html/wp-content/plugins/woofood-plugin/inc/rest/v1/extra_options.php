<?php

add_action( 'rest_api_init', 'my_register_route');
function my_register_route() {

  register_rest_route( 'woofood/v1', 'extra-options/(?P<id>[\d]+)', 



    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'get_extra_option',
      'args' => array(
        'id' => array( 
          'validate_callback' => function( $param, $request, $key ) {
            return is_numeric( $param );
          }
          )

        ),
      'permission_callback' => function() {
        return true;

      }, 
      )









    );


  register_rest_route( 'woofood/v1', 'extra-options', 



    array(
      'methods' => "POST",
      'callback' => 'update_extra_option',

      )










    );


  register_rest_route( 'woofood/v1', 'extra-options', 




    array(
      'methods' => "DELETE",
      'callback' => 'delete_extra_option',

      )









    );
}

if(!function_exists('getallheaders'))
{

  function getallheaders() { 
    $headers = ''; 
    foreach ( $_SERVER as $name => $value ) { 
      if ( substr( $name, 0, 5 ) == 'HTTP_' ) { 
        $headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value; 
      } 
    } 
    return $headers; 
  }
}



function get_extra_option( $request ) {

  $creds = array();
  $headers = getallheaders();
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
  $extra_option_id = $request[ 'id' ];
}
else

{


}
$extra_option_data = array();
$categories_data = array();
$extra_option = get_post($extra_option_id);


$extra_option_categories = get_the_terms( $extra_option->ID, "extra_option_categories" );
foreach($extra_option_categories as $extra_option_category)
{
  $categories_data[]= array("ID"=>$extra_option_category->term_id, "name"=>$extra_option_category->name);

}


$extra_option_data["ID"] = $extra_option->ID;
$extra_option_data["name"] = $extra_option->post_title;
$extra_option_data["price"] = get_post_meta($extra_option->ID, 'extra_option_price', true);
$extra_option_data["visible_as"] = get_post_meta($extra_option->ID, 'extra_option_visible_as', true);
$extra_option_data["categories"] =  $categories_data;



return rest_ensure_response( $extra_option_data );







}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}







function update_extra_option( $request ) {
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getallheaders();
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

  $extra_option_id = intval($request[ 'id' ]);



//update price if set// 
  if( isset( $request[ 'price' ] ) ) 
  {
    update_post_meta(intval($request[ 'id' ]), 'extra_option_price', $request['price']);
  }
//update price if set// 


//check if we set price update extra option price//  
  if(isset($request["visible_as"]))
  {
    update_post_meta(intval($request[ 'id' ]), 'extra_option_visible_as', $request['visible_as']);

  }

//check if we set price update extra option price//   



  if(isset($request["categories"]))
  {

    wp_set_post_terms( intval($request[ 'id' ]), $request["categories"], "extra_option_categories", false );

  }

}
//create new extra option//
else 
{

//if we set extra option name create new extra option//

  if(isset( $request[ 'name' ]))
  {
// Create post object
    $my_post = array(
      'post_title'    => wp_strip_all_tags( $request["name"] ),
      'post_status'   => 'publish',
      'post_type' => 'extra_option'
      );

// Insert the post into the database
    $post_result = wp_insert_post( $my_post );



//check if result is ok///
    if ( $post_result && ! is_wp_error( $post_result ) ) {
      $post_id = $post_result;
      $extra_option_id  = $post_id;


//check if we set price update extra option price//  
      if(isset($request["price"]))
      {
        update_post_meta(intval($post_id ), 'extra_option_price', $request['price']);

      }
//check if we set price update extra option price// 

//check if we set price update extra option price//  
      if(isset($request["visible_as"]))
      {
        update_post_meta(intval($post_id ), 'extra_option_visible_as', $request['visible_as']);

      }

//check if we set price update extra option price//   



      if(isset($request["categories"]))
      {

        wp_set_post_terms( intval($post_id ), $request["categories"], "extra_option_categories", false );

      }



// Do something else
    }
//check if result is ok///


  }
//if we set extra option name create new extra option//








}
//create new extra option//

//if we set the id then we have to update the option and not create it//




$extra_option_data = array();
$categories_data = array();
$extra_option = get_post($extra_option_id);


$extra_option_categories = get_the_terms( $extra_option->ID, "extra_option_categories" );
foreach($extra_option_categories as $extra_option_category)
{
  $categories_data[]= array("ID"=>$extra_option_category->term_id, "name"=>$extra_option_category->name);

}


$extra_option_data["ID"] = $extra_option->ID;
$extra_option_data["name"] = $extra_option->post_title;
$extra_option_data["price"] = get_post_meta($extra_option->ID, 'extra_option_price', true);
$extra_option_data["visible_as"] = get_post_meta($extra_option->ID, 'extra_option_visible_as', true);
$extra_option_data["categories"] =  $categories_data;




return rest_ensure_response( $extra_option_data );







}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}







function delete_extra_option( $request ) {
  $parameters = $request->get_json_params();
  $creds = array();
  $headers = getallheaders();
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





//if we set the id then we have to update the option and not create it//
if( isset( $request[ 'id' ] ) ) {
  $force = false;
  if($request[ 'force' ] ==true)
  {
    $force = true;

  }

  if(wp_delete_post( intval($request[ 'id' ]), $force ))
  {
    return rest_ensure_response("success");

  }

}
//create new extra option//
else 
{



  return new WP_Error( 'no-id', 'No ID Specified...', array( 'status' => 400 /* Bad Request */ ) );





}
//create new extra option//

//if we set the id then we have to update the option and not create it//




return rest_ensure_response( $extra_option_data );







}
else {
  return rest_ensure_response($headers);
  return new WP_Error( 'invalid-method', 'You must specify a valid username and password.', array( 'status' => 400 /* Bad Request */ ) );
}


}



?>