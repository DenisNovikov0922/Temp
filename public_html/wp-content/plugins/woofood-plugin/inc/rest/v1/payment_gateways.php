<?php
add_action( 'rest_api_init', 'register_rest_woofood_payment_gatewats');
function register_rest_woofood_payment_gatewats() {





  register_rest_route( 'woofood/v1', 'payment/gateways', 





    array(
      'methods' => "POST",
      'callback' => 'woofood_api_payment_gateways',

      )
    );







}

function woofood_api_payment_gateways( $request ) {
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


$available_payment_methods = (array) WC()->payment_gateways->payment_gateways();
$gateways_export = array();
foreach($available_payment_methods as $key=>$current_gateway)
{

  if($current_gateway->enabled =="yes")
  {

      if($current_gateway->id=="stripe")
  {
    $current_gateway->secret_key ="";
$current_gateway->settings["test_secret_key"] = "";
   
$gateways_export[] = $current_gateway;

  }

  if($current_gateway->id=="cod")
  {


   
$gateways_export[] = $current_gateway;

  }


  }


}

  return rest_ensure_response( $gateways_export );


}
else
{
  $user_response = array(
          'message' => "Please provide email and password",
          'success' => false,
        );
return $user_response;

}
}

?>