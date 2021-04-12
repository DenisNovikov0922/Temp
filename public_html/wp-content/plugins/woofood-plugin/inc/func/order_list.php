<?php

function wf_order_list_refresh(){

  $order_statuses = $_POST['order_status_select'];
$order_refreshing = $_POST['order_refreshing'];
?>
<script>
  jQuery(document).ready(function($){

 wf_loop = <?php echo $order_refreshing;?>;
});
</script>


<?php
 /* $order_list = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
            'numberposts' => -1,
            'post_type' => wc_get_order_types('view-orders'),
            'post_status' => $order_statuses
                )));*/
 $order_list  = wc_get_orders(array('limit'=> -1,
'post_status'=> $order_statuses,
'fields'=> 'ids',
         'return' => 'ids' ));
//print_r($order_list);
foreach($order_list as $current_order)
{
  ?>
<div class="order_list_item">

<?php get_order_details($current_order); ?>

</div>

<?php

}//end foreach



    die();

}
add_action('wp_ajax_woofood_order_list_refresh', 'wf_order_list_refresh');



//get order details function//
function get_order_details($order_id){
$options_woofood = get_option('woofood_options');
$woofood_enable_order_accepting = $options_woofood['woofood_enable_order_accepting'];


    // 1) Get the Order object
    $order =  new WC_Order( $order_id );
    $order_data = $order->get_data(); // The Order data

    // 3) Get the order items
    $items = $order->get_items();
    $order_phone = $order->get_billing_phone();
    $order_email = $order->get_billing_email();
    $order_date  = $order_data['date_created']->date(get_option('date_format')." ".get_option('time_format'));
    $order_type = get_post_meta($order_id, 'woofood_order_type', true);
    if(!$order_type)
    {
      $order_type = woofood_get_default_order_type();
    }
    $woofood_time_to_deliver  = get_post_meta($order_id, 'woofood_time_to_deliver', true);
    $default_date_format = get_option("date_format");
        $default_time_format = get_option("time_format");

    if($woofood_time_to_deliver)
{
  if($woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap"  )
{
$woofood_time_to_deliver = date_i18n($default_time_format , strtotime($woofood_time_to_deliver  ) );

}
}
    $woofood_date_to_deliver  = get_post_meta($order_id, 'woofood_date_to_deliver', true);
if($woofood_date_to_deliver)
{
  if($woofood_date_to_deliver!=current_time("Y-m-d"))
{
$woofood_date_to_deliver = date_i18n( $default_date_format, strtotime($woofood_date_to_deliver  ) );

}
else
{
  $woofood_date_to_deliver = esc_html('Today', 'woofood-plugin');
}
}
    $order_status = $order->get_status();
  $order_type_text = woofood_get_order_type_by_key($order_type);  
  
$shipping_address =   $order->get_formatted_shipping_address();
$billing_address =   $order->get_formatted_billing_address();


    echo '<div class="order_id">';
    echo '<b>'.esc_html__('Order ID','woofood-plugin').':</b>'.$order_id;
      

    echo '<div class="order_date" style="float:right;">';
    echo '<b>'.esc_html__('Order Date','woofood-plugin').':</b>'.$order_date;
    echo '</div>';

    echo  '</div>';
    
    echo '<div class="wf_order_col_40">';

    echo '<h3>'.esc_html__('Customer Details','woofood-plugin').'</h3>';
    echo '<div class="wf_order_details">';
    echo '<b>'.esc_html__('Time to', 'woofood-plugin')." ".$order_type_text.":</b><br/>".$woofood_time_to_deliver."<br/>";
    if(!empty($woofood_date_to_deliver))
    {
        echo '<b>'.esc_html__('Date to', 'woofood-plugin')." ".$order_type_text.":</b><br/>".$woofood_date_to_deliver."<br/>";
   
    }

    echo '<b>'.esc_html__('Phone', 'woofood-plugin').":</b><br/>".$order_phone."<br/>";
    echo '<b>'.esc_html__('Customer Notes', 'woofood-plugin').":</b><br/>";
echo $order->get_customer_note();
        echo '</div>';
        echo '<div class="wf_order_details">';

    echo '<b>'.esc_html__('Email', 'woofood-plugin').":</b><br/>".$order_email."<br/>";
   echo "<hr/>";

   echo '<b>'.esc_html__('Address', 'woofood-plugin').":</b><br/>";

    $billing_address_html = !empty($shipping_address) ? $shipping_address : $billing_address;

    echo $billing_address_html."<br/>";
        echo '</div>';

   
    $email = $order->get_billing_email();
    $name = $order->get_billing_last_name().' '.$order->get_billing_first_name();
        echo '</div>';
    echo '<div class="wf_order_col_40">';

    echo '<h3>'.esc_html__('Order Details','woofood-plugin').'('.$order_type_text.')</h3>';

    $curr_numn = 1;
    foreach ( $items as $item ) {

      echo "<b>".$curr_numn.") </b>".$item['name']." ".__('<b>Quantity:</b>','woofood-plugin').$item['qty'];

      wc_display_item_meta( $item );
      echo "<hr/>";

   




    ++$curr_numn;

    }

    echo '</div>';



    echo '<div class="wf_order_col_20">';

    
    echo '<div class="wf_order_total">';
    echo '<div id="wf_message_'.$order_id.'"></div>';

    echo __('<b>Order Total:</b>','woofood-plugin').get_woocommerce_currency_symbol().$order->get_total();
    echo '</div>';
       echo '<div class="order_status" style="text-align:center;width:100%;">';

    echo '<b>'.esc_html__('Order Status','woofood-plugin').':</b>'.wc_get_order_status_name($order_status);
        echo '</div>';


    //accept decline buttons//
if($woofood_enable_order_accepting!=0 && $order->get_status() =="accepting")
{
$woofood_minutes_to_arrive = $options_woofood['woofood_minutes_to_arrive'];
$woofood_minutes_to_arrive_array = explode(',',$woofood_minutes_to_arrive);

  ?>
<form id="woofood_accept_order_form_<?php echo $order_id; ?>" action="" method="POST">
                                        <input type="hidden" name="action" value="woofood_accept_order"/>

                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
                                        <div class="minutes-container">
                                        <?php foreach($woofood_minutes_to_arrive_array as $current_minute): ?>

                                        <div  class="woofood-minute">
                                        <input type="radio" name="minutes_to_arrive" value="<?php echo $current_minute;?>" id="minutes_<?php echo $current_minute;?>">
                                        <label for="minutes_<?php echo $current_minute;?>"><?php echo $current_minute;?></label>
                                        </div>
                                      <?php endforeach; ?>

                                        


                                        </div>
                                        <button class="button wf-accept" id="woofood_accept_btn" data-loading-text="<?php _e('Loading...', 'woofood') ?>" type="submit"><?php _e('Accept Order', 'woofood-plugin'); ?></button>

</form>

<form id="woofood_decline_order_form_<?php echo $order_id; ?>" action="" method="POST">
                                        <input type="hidden" name="action" value="woofood_decline_order"/>
                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>

                                        <button class="button wf-decline"  id="woofood_decline_btn" data-loading-text="<?php _e('Loading...', 'woofood') ?>" type="submit"><?php _e('Decline Order', 'woofood-plugin'); ?></button>

</form>
<script type="text/javascript">
  jQuery(document).ready(function($){
        // Post woofood accept form
    $('#woofood_accept_order_form_<?php echo $order_id; ?>').on('submit', function(e){

        e.preventDefault();

      //  var button = $(this).find('button');
      //      button.button('loading');

        $.post(wfajax.ajaxurl, $('#woofood_accept_order_form_<?php echo $order_id; ?>').serialize(), function(data){
            var accept_data = data;

            $('#wf_message_<?php echo $order_id;?>').html(accept_data);
           $('#wf_order_list').submit();

            
        });

    });

    $('#woofood_decline_order_form_<?php echo $order_id; ?>').on('submit', function(e){

        e.preventDefault();

      //  var button = $(this).find('button');
      //      button.button('loading');

        $.post(wfajax.ajaxurl, $('#woofood_decline_order_form_<?php echo $order_id; ?>').serialize(), function(data){
            var decline_data = data;

            $('#wf_message_<?php echo $order_id;?>').html(decline_data);
           $('#wf_order_list').submit();

            
        });

    });










});

</script>
<?php }
    //accept decline buttons//


elseif($order->get_status() =="processing")
{
  ?>
  <form id="woofood_complete_order_form_<?php echo $order_id; ?>" action="" method="POST">
                                        <input type="hidden" name="action" value="woofood_complete_order"/>

                                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"/>
                                        <button class="button wf-accept" id="woofood_accept_btn" data-loading-text="<?php _e('Loading...', 'woofood') ?>" type="submit"><?php _e('Complete Order', 'woofood-plugin'); ?></button>

</form>
<script type="text/javascript">
  jQuery(document).ready(function($){
        // Post woofood accept form
    $('#woofood_complete_order_form_<?php echo $order_id; ?>').on('submit', function(e){

        e.preventDefault();

   

        $.post(wfajax.ajaxurl, $('#woofood_complete_order_form_<?php echo $order_id; ?>').serialize(), function(data){
            var accept_data = data;

            $('#wf_message_<?php echo $order_id;?>').html(accept_data);
           $('#wf_order_list').submit();

            
        });

    });










});
</script>

  <?php


}

elseif($order->get_status() =="completed")
{
  
      echo '<h3>'.__('Order Completed', 'woofood-plugin').'</h3>';



}

elseif($order->get_status() =="cancelled")
{
  
      echo '<h3>'.__('Order Declined', 'woofood-plugin').'</h3>';



}
    echo '</div>';
?>

<?php

}//end function


//get order details function//

?>