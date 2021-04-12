<?php

function wf_push_notification_send(){
$options_woofood_push_notifications = get_option('woofood_options_push_notifications');

$woofood_push_notifications_key = $options_woofood_push_notifications['woofood_push_notifications_key'];

if(!empty($woofood_push_notifications_key))
{
  
// prep the bundle
$msg_body = $_POST['woofood_push_message'];
$msg_title = $_POST['woofood_push_title'];
$msg_topic = 'general';

$msg = array
(
  'body'  => $msg_body,
  'title'   => $msg_title,
  "sound" => "default",
  "vibrate" => "default"

);
$fields = array
(
     //'notification' => $msg,
       'to' => '/topics'.'/'.$msg_topic,
       'notification' => $msg,
       'priority' => 'high',




);
 
$headers = array
(
  'Authorization: key='.$woofood_push_notifications_key,
  'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
$push_response = json_decode($result);
echo $result;
if (!empty($push_response->success))
{
  echo '<div class="woofood_push_success">';
 esc_html_e('Push has been Succesfully sent ','woofood-plugin');
 echo "<br/>";
  esc_html_e('Message ID:','woofood-plugin');

 echo $push_response->message_id;
 echo "</div>";
}
else{
    echo __('<div class="woofood_push_error">Something went wrong!Please check your server key</div>','woofood-plugin');

}
curl_close( $ch );

  die();




}//end if


}//end function
add_action('wp_ajax_woofood_push_notification_send', 'wf_push_notification_send');


$options_woofood_push_notifications = get_option('woofood_options_push_notifications');

$woofood_push_notifications_key = $options_woofood_push_notifications['woofood_push_notifications_key'];
$woofood_push_notifications_completed_enabled = $options_woofood_push_notifications['woofood_push_notifications_completed_enabled'];
$woofood_push_notifications_completed_message = $options_woofood_push_notifications['woofood_push_notifications_completed_message'];

if(!empty($woofood_push_notifications_key) && $woofood_push_notifications_completed_enabled )
{ 
function woofood_push_notification_on_completed($order_id)
{
  global $woocommerce;

$options_woofood_push_notifications = get_option('woofood_options_push_notifications');

$woofood_push_notifications_key = $options_woofood_push_notifications['woofood_push_notifications_key'];
$woofood_push_notifications_completed_enabled = $options_woofood_push_notifications['woofood_push_notifications_completed_enabled'];
$woofood_push_notifications_completed_message = $options_woofood_push_notifications['woofood_push_notifications_completed_message'];
      $order = wc_get_order($order_id);

    // Get the user ID from WC_Order methods
      
    $user_id = $order->get_user_id(); // or $order->get_customer_id();
    if($user_id)
    {
      $push_token = get_user_meta($user_id, "push_token", true);
      if($push_token && $push_token!="")
      {



$msg = array
(
  'body'  => $woofood_push_notifications_completed_message,
  'title'   => "Status",
  "sound" => "default",
  "vibrate" => "default"

);
$fields = array
(
     //'notification' => $msg,
       'to' => $push_token,
       'notification' => $msg,
       'priority' => 'high',




);
 
$headers = array
(
  'Authorization: key='.$woofood_push_notifications_key,
  'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
$push_response = json_decode($result);

if (!empty($push_response->success))
{
 
}
else{
    echo __('<div class="woofood_push_error">Something went wrong!Please check your server key</div>','woofood-plugin');

}
curl_close( $ch );

      }
    }




}
add_action( 'woocommerce_order_status_completed', 'woofood_push_notification_on_completed', 10, 1);

}

?>