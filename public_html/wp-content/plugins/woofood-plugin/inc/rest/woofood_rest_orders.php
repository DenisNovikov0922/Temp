<?php


function register_woofood_extra_init()  {
      register_rest_route( 'wp/v2', 'woofood-orders', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => 'get_woofood_orders'),

       ));
    register_rest_route( 'wp/v2', 'woofood-orders/(?P<id>[\d]+)', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => 'get_woofood_order'
                ),
            array(
        'methods'         => WP_REST_Server::EDITABLE,
        'callback'        => 'update_woofood_order',
       // 'args'            => get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
      ) ,
          
));

}







add_action('rest_api_init', 'register_woofood_extra_init', 99);

function get_items_permissions_check( $request ) {
    //return true; <--use to make readable by all
 $final = false;
     

      if ( is_user_logged_in() ) {
    if ( wp_verify_nonce($request['X-WP-Nonce'], 'wp_rest')) {
        $final = current_user_can( 'edit_something' );
    } else {
      
    }
}

return $final;
  }










function get_woofood_orders()
{

  if(is_user_logged_in())
  {
$all_orders = array();
global $woocommerce;
$order_list = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
            'numberposts' => -1,
            'post_type' => 'shop_order',
            'post_status' => "any",
            'fields' => "ids"
                )));

 foreach($order_list as $current_order_id)
 {
 $order = wc_get_order( $current_order_id );

$order_data = $order->get_data(); // The Order data
$order_data["line_items"] = array();

foreach ($order->get_items() as $item_key => $item_values)
{
      $item_data = $item_values->get_data();

$order_data["line_items"][]=$item_data;

}

$all_orders[] = $order_data;
 }
//print_r($all_orders);
return $all_orders;

  }//end if user is logged in
}//get woofood orders stop

 function get_woofood_order( $request ) {
  if(is_user_logged_in())
  {
  $current_order = array();
      $params = $request->get_params();

     $order = wc_get_order( $params["id"] );

$order_data = $order->get_data(); // The Order data
$order_data["line_items"] = array();
$format_decimal = array( 'subtotal', 'subtotal_tax', 'total', 'total_tax', 'tax_total', 'shipping_tax_total' );
    // Format decimal values.
    foreach ( $format_decimal as $key =>$value ) {
      if ( isset( $order_data[ $key ] ) ) {
        $order_data[ $key ] = wc_format_decimal( $order_data[ $key ], $order_data[ $value ] );
      }
    }
foreach ($order->get_items() as $item_key => $item_values)
{
      $item_data = $item_values->get_data();
      $item_data["price"] =  $item_values->get_total() / max( 1, $item_values->get_quantity() );
      $item_data["sku"] =  $item_values->get_product() ? $item_values->get_product()->get_sku(): null;

      $order_data["line_items"][]=$item_data;

}
return $order_data;
}
  }//end get_woofood_order



  function update_woofood_order_old( $request ) {
    global $woocommerce;
  $current_order = array();
  $params = $request->get_params();
  $current_order_id = $params["id"];
    $order = wc_get_order($current_order_id);
foreach($params as $param_key=>$param_value){

  if ($param_key!="id")
  {


   //CHECK IF TRYING TO UPDATE THE ORDER STATUS// 
  if ($param_key=="status")
  {
            $order->update_status($param_value, '');

           // update_post_meta( $current_order_id, $param_key, $param_value );
  }
  //CHECK IF TRYING TO UPDATE THE ORDER STATUS// 


  //CHECK IF TRYING TO UPDATE THE ORDER TOTAL// 
  if ($param_key=="total")
  {
    $total_value = (float) $param_value;
    $order->set_total($total_value);

           // update_post_meta( $current_order_id, $param_key, $param_value );
  }
  //CHECK IF TRYING TO UPDATE THE ORDER TOTAL// 


 //CHECK IF TRYING TO UPDATE THE ORDER DISCOUNT TOTAL// 
  if ($param_key=="discount_total")
  {
    $total_value = (float) $param_value;
    $order->set_total($total_value);

           // update_post_meta( $current_order_id, $param_key, $param_value );
  }
  //CHECK IF TRYING TO UPDATE THE ORDER DISCOUNT TOTAL// 



  }//if is not id


}
    $order->save();

return $order->get_data();
  }//end update_woofood_order



  function update_woofood_order($request)
  {
    global $woocommerce;

    $id        = isset( $request['id'] ) ? absint( $request['id'] ) : 0;
    $order     = new WC_Order( $id );
    $schema    = get_item_schema("shop_order");
    $data_keys = array_keys( array_filter( $schema['properties'],'filter_writable_props' ) );
    $data_keys[] = "meta_data";


    // Handle all writable props
    foreach ( $data_keys as $key ) {
      $value = $request[ $key ];
      if ( ! is_null( $value ) ) {
        switch ( $key ) {
          case 'billing' :
          case 'shipping' :
            update_address( $order, $value, $key );
            break;
          case 'line_items' :
          case 'shipping_lines' :
          case 'fee_lines' :
          case 'coupon_lines' :
            if ( is_array( $value ) ) {
              foreach ( $value as $item ) {
                if ( is_array( $item ) ) {
                  if ( item_is_null( $item ) || ( isset( $item['quantity'] ) && 0 === $item['quantity'] ) ) {
                    $order->remove_item( $item['id'] );
                  } else {
                    set_item( $order, $key, $item );
                  }
                }
              }
            }
            break;
          default :
            if ( is_callable( array( $order, "set_{$key}" ) ) ) {
              $order->{"set_{$key}"}( $value );
            }
            break;
        }
      }
  }
  $order->set_created_via( 'rest-api' );
      $order->set_prices_include_tax( 'yes' === get_option( 'woocommerce_prices_include_tax' ) );
      $order->calculate_totals();
      $order->save();
      return $order->get_data();


}//end update woofood order







?>