<?php
//add accept button ajax //
function wf_accept_order(){
 $order_needs_payment = false;


                 

  $order_id = $_POST['order_id']; 
  $minutes_to_arrive = $_POST['minutes_to_arrive']; 

   if ( isset($order_id) ) {

  $order = wc_get_order($order_id);
  update_post_meta($order_id,'minutes_to_arrive', $minutes_to_arrive);

                        $order->update_status('pending');

                         $order->save();


  echo __('Order Accepted Succesfully','woofood-plugin');
  die();
}

}
add_action('wp_ajax_woofood_accept_order', 'wf_accept_order');

//add accept button ajax//


//add complete button ajax //
function wf_complete_order(){


  $order_id = $_POST['order_id']; 
   if ( isset($order_id) ) {

  $order = wc_get_order($order_id);

  $order->update_status('completed');
  echo __('Order Completed Succesfully','woofood-plugin');
  die();
}

}
add_action('wp_ajax_woofood_complete_order', 'wf_complete_order');

//add complete button ajax//

//add decline button ajax //
function wf_decline_order(){


  $order_id = $_POST['order_id']; 
   if ( isset($order_id) ) {

  $order = wc_get_order($order_id);

  $order->update_status('cancelled');

                         $order->save();

  echo __('Order Declined Succesfully','woofood-plugin');
  die();
}

}
add_action('wp_ajax_woofood_decline_order', 'wf_decline_order');

//add decline button ajax//

?>