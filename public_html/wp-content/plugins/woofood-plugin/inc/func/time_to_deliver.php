<?php
 $woofood_options = get_option('woofood_options');
  $woofood_enable_time_to_deliver_option = isset($woofood_options['woofood_enable_time_to_deliver_option']) ?  $woofood_options['woofood_enable_time_to_deliver_option'] : null;
 $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
  $woofood_enable_time_to_pickup_option = isset($woofood_options['woofood_enable_time_to_pickup_option']) ? $woofood_options['woofood_enable_time_to_pickup_option'] : null ;
  $woofood_enable_date_to_deliver_option = isset($woofood_options['woofood_enable_date_to_deliver_option']) ? $woofood_options['woofood_enable_date_to_deliver_option'] : null ;
  $woofood_enable_date_to_pickup_option = isset($woofood_options['woofood_enable_date_to_pickup_option']) ? $woofood_options['woofood_enable_date_to_pickup_option'] : null ;


  if ($woofood_enable_time_to_deliver_option) {
//add doorbell checkout field


add_action( 'woocommerce_checkout_before_order_review', 'wf_select_time_to_deliver', 13, 0 );

function wf_select_time_to_deliver() {
  $woofood_options = get_option('woofood_options');
  $woofood_delivery_time = intval($woofood_options['woofood_delivery_time']);
      $woofood_disable_now_from_time = $woofood_options['woofood_disable_now_from_time'];
      $woofood_enable_asap_on_time = $woofood_options['woofood_enable_asap_on_time'];
      $woofood_break_down_times_every = $woofood_options['woofood_break_down_times_every'];

      $woofood_enable_maximum_orders_delivery_timeslot = isset($woofood_options['woofood_enable_maximum_orders_delivery_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_delivery_timeslot']: null  ;
      $woofood_maximum_orders_delivery_timeslot = $woofood_options['woofood_maximum_orders_delivery_timeslot'] ? intval($woofood_options['woofood_maximum_orders_delivery_timeslot']) : 0;

      if(!$woofood_break_down_times_every)
      {
        $woofood_break_down_times_every =  "30";
      }

      $default_time_format = get_option( 'time_format' );
  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }

echo '<div id="wf-time-to-deliver" class="delivery"> ';

$time_to_delivery_options = array();


$today_delivery_hours = woofood_check_if_within_delivery_hours(true);
$current_time = strtotime(current_time("H:i"));

if($woofood_delivery_time > 0)
{
$current_time = strtotime("+".$woofood_delivery_time." minutes", $current_time);
}
else
{
  
}
$we_are_open_now = woofood_check_if_within_delivery_hours(false, true );
if(!$woofood_disable_now_from_time &&  $we_are_open_now )
{
$time_to_delivery_options['now'] = esc_html__('Now', 'woofood-plugin');
}

if($woofood_enable_asap_on_time && $we_are_open_now)
{
$time_to_delivery_options['asap'] = esc_html__('ASAP', 'woofood-plugin');
}

if(is_array($today_delivery_hours))
{
  foreach($today_delivery_hours as $time)
{

  $period = new DatePeriod(
    new DateTime($time["start"]),
    new DateInterval('PT'.$woofood_break_down_times_every .'M'),
    new DateTime($time["end"])
);



  foreach ($period as $date) {
    if($current_time < strtotime($date->format("H:i")) )
    { 
      if($woofood_maximum_orders_delivery_timeslot && $woofood_maximum_orders_delivery_timeslot > 0)
      {
         if(woofood_get_orders_count("delivery", "", $date->format("H:i")) < $woofood_maximum_orders_delivery_timeslot)
      {
            $time_to_delivery_options[$date->format("H:i")] = date_i18n( $default_time_format, strtotime($date->format("H:i") ) );

      }

      }
      else
      {
                    $time_to_delivery_options[$date->format("H:i")] = date_i18n( $default_time_format, strtotime($date->format("H:i") ) );

      }

     
      
        //  $time_to_delivery_options[$date->format($default_time_format)] = $date->format($default_time_format);


    }

    
}


}

}


if(empty($time_to_delivery_options))
{
  $time_to_delivery_options = array("none" => esc_html__('No Available hours', 'woofood-plugin'));
}

echo '<span class="wf_tdlvr_title">'.esc_html__('Time to Deliver', 'woofood-plugin').'</span>';
woocommerce_form_field( 'woofood_time_to_deliver', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('woofood_time_to_deliver'),

'required'     => true,
//'options'  => $time_to_delivery_options,
'options'  => $time_to_delivery_options,

), 'Delivery');




echo '</div>';




} //end wf_select_time_to_deliver







  include_once(WOOFOOD_PLUGIN_DIR.'inc/func/date_to_deliver.php');























/**
 * Display field value on the order edit page
 */
 





add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wf_time_to_deliver_checkout_admin_order_meta', 10, 1 );

function wf_time_to_deliver_checkout_admin_order_meta($order){
    echo '<p><strong>'.esc_html__('Time To Deliver', 'woofood-plugin').':</strong> ' . get_post_meta( $order->get_id(), 'woofood_time_to_deliver', true ) . '</p>';
}
//add doorbel  checkout field

}//end if time to deliver is enabled//


if($woofood_enable_pickup_option && $woofood_enable_time_to_pickup_option)
{
  add_action( 'woocommerce_checkout_before_order_review', 'wf_select_time_to_pickup', 13, 0);
}

function wf_select_time_to_pickup() {
  $woofood_options = get_option('woofood_options');
  $woofood_pickup_time = intval($woofood_options['woofood_pickup_time']);
   $woofood_disable_now_from_pickup_time = $woofood_options['woofood_disable_now_from_pickup_time'];
      $woofood_enable_asap_on_pickup_time = $woofood_options['woofood_enable_asap_on_pickup_time'];
            $woofood_break_down_pickup_times_every = $woofood_options['woofood_break_down_pickup_times_every'];
  $woofood_enable_date_to_pickup_option = isset($woofood_options['woofood_enable_date_to_pickup_option']) ? $woofood_options['woofood_enable_date_to_pickup_option'] : null ;

            $woofood_enable_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_enable_maximum_orders_pickup_timeslot']) ? $woofood_options['woofood_enable_maximum_orders_pickup_timeslot'] : null ;
      $woofood_maximum_orders_pickup_timeslot = isset($woofood_options['woofood_maximum_orders_pickup_timeslot']) ? intval($woofood_options['woofood_maximum_orders_pickup_timeslot']) : 0 ;

            if(!$woofood_break_down_pickup_times_every)
      {
        $woofood_break_down_pickup_times_every =  "30";
      }

        $default_time_format = get_option( 'time_format' );
  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }

echo '<div id="wf-time-to-deliver" class="pickup">';

$time_to_pickup_options = array();


$today_pickup_hours = woofood_check_if_within_pickup_hours(true);
$current_time = strtotime(current_time("H:i"));

if($woofood_pickup_time > 0)
{
$current_time = strtotime("+".$woofood_pickup_time." minutes", $current_time);
}
else
{
 
}
$we_are_open_now = woofood_check_if_within_pickup_hours(false, true );

if(!$woofood_disable_now_from_pickup_time && $we_are_open_now)
{
$time_to_pickup_options['now'] = esc_html__('Now', 'woofood-plugin');
}

if($woofood_enable_asap_on_pickup_time && $we_are_open_now)
{
$time_to_pickup_options['asap'] = esc_html__('ASAP', 'woofood-plugin');
}


if(is_array($today_pickup_hours))
{
  foreach($today_pickup_hours as $time)
{

  $period = new DatePeriod(
    new DateTime($time["start"]),
    new DateInterval('PT'.$woofood_break_down_pickup_times_every.'M'),
    new DateTime($time["end"])
);



  foreach ($period as $date) {
    if($current_time < strtotime($date->format("H:i")) )
    {



      if($woofood_maximum_orders_pickup_timeslot && $woofood_maximum_orders_pickup_timeslot > 0)
      {
         if(woofood_get_orders_count("pickup", "", $date->format("H:i")) < $woofood_maximum_orders_pickup_timeslot)
      {
            $time_to_pickup_options[$date->format("H:i")] = date_i18n( $default_time_format, strtotime($date->format("H:i") ) );

      }

      }
      else
      {
                  $time_to_pickup_options[$date->format("H:i")] = date_i18n( $default_time_format, strtotime($date->format("H:i") ) );

      }
      
         // $time_to_pickup_options[$date->format($default_time_format)] = $date->format("H:i\n");


    }

    
}


}

}

if(empty($time_to_pickup_options))
{
  $time_to_pickup_options = array("none" => esc_html__('No Available hours', 'woofood-plugin'));
}


echo '<span class="wf_tdlvr_title">'.esc_html__('Time to Pickup', 'woofood-plugin').'</span>';
woocommerce_form_field( 'woofood_time_to_pickup', array(
//'label'         => esc_html__('Time to Deliver', 'woofood-plugin'),
 'type'         => 'select',

'class'         => array('woofood_time_to_pickup'),

'required'     => true,
'options'  => $time_to_pickup_options,

), '');

echo '</div>';
} //





  include_once(WOOFOOD_PLUGIN_DIR.'inc/func/date_to_pickup.php');






































 $woofood_options = get_option('woofood_options');
$woofood_enable_time_to_deliver_option = isset($woofood_options['woofood_enable_time_to_deliver_option']) ? $woofood_options['woofood_enable_time_to_deliver_option'] : null;
$woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;

if($woofood_enable_time_to_deliver_option  && $woofood_enable_pickup_option==0 )
{
    add_action( 'woocommerce_after_checkout_form', 'woofood_show_default_time_to_deliver_script');
 
function woofood_show_default_time_to_deliver_script() {
?>
<script>
jQuery(document).ready(function () {


          jQuery('#wf-time-to-deliver.<?php echo woofood_get_default_order_type(); ?>').addClass('open');





});

</script>

<?php

}

}

function woofood_get_orders_count($order_type ="", $date = "", $time = "")
{
  $args = array(   'return' => 'ids' ) ;
  
if(!empty($date))
{
  $args['woofood_date_to_deliver']=$date;
}
  if($order_type!="")
  {
    $args["woofood_order_type"] = $order_type;
  }
   if($time!="")
  {
    $args["woofood_time_to_deliver"] = $time;
  }

$orders = wc_get_orders( $args) ;
return count($orders);
}


function woofood_get_time_by_meta_value($meta_value)
{
   $default_time_format = get_option( 'time_format' );
  if(!$default_time_format)
  {
    $default_time_format = "H:i";
    
  }

  if($meta_value == "now")
  {
    $time = esc_html__('Now', 'woofood-plugin');

  }
  else if($meta_value =="asap") {

    $time = esc_html__('ASAP', 'woofood-plugin');

 }
    else
    {
      $time = date_i18n( $default_time_format, strtotime($meta_value) );

    }
    return $time;

}






add_action( 'woocommerce_checkout_update_order_meta', 'wf_update_order_meta_time_to_deliver' );

function wf_update_order_meta_time_to_deliver( $order_id ) {
  $order = wc_get_order( $order_id );
  $order_type =woofood_get_default_order_type();
  $time_to_deliver = "";
  $time_to_pickup = "";
  $date_to_deliver = "";
  $date_to_pickup = "";

  if ( ! empty( $_POST['woofood_order_type'] ) ) {

    if( $_POST['woofood_order_type'] =="delivery")
    {
      $order_type = "delivery";

    }
     if( $_POST['woofood_order_type'] =="pickup")
    {
            $order_type = "pickup";

    }

  }
 

if ( ! empty( $_POST['woofood_time_to_deliver'] ) ) {
 
       $time_to_deliver = $_POST['woofood_time_to_deliver'];
   /* $order->update_meta_data( 'woofood_time_to_deliver', sanitize_text_field( $_POST['woofood_time_to_deliver'] ));

    $order->save();*/
}
if ( ! empty( $_POST['woofood_time_to_pickup'] ) ) {
 
       $time_to_pickup = $_POST['woofood_time_to_pickup'];
   
}

if ( ! empty( $_POST['woofood_date_to_deliver'] ) ) {
 
       $date_to_deliver = $_POST['woofood_date_to_deliver'];
   
}
if ( ! empty( $_POST['woofood_date_to_pickup'] ) ) {
 
       $date_to_pickup = $_POST['woofood_date_to_pickup'];
   
}



if(!empty($time_to_deliver) && $order_type =="delivery")
{
   $order->update_meta_data( 'woofood_time_to_deliver', sanitize_text_field( $time_to_deliver ));
   if(!empty($date_to_deliver))
   {
      $order->update_meta_data( 'woofood_date_to_deliver', sanitize_text_field( $date_to_deliver ));

   }

    $order->save();
}

if(!empty($time_to_pickup) && $order_type =="pickup")
{
   $order->update_meta_data( 'woofood_time_to_deliver', sanitize_text_field( $time_to_pickup ));

   if(!empty($date_to_pickup))
   {
    $order->update_meta_data( 'woofood_date_to_deliver', sanitize_text_field( $date_to_pickup ));

   }

    $order->save();
}


}//end function

?>