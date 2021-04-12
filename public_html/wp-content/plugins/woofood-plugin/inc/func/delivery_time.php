<?php

function wf_process_time_woocommerce_meta() {
    add_meta_box( 'process_time_woocommerce', esc_html__( 'Process Time(WooFood Field)', 'woofood-plugin' ), 'wf_process_time_woocommerce_callback', 'product' );
}
add_action( 'add_meta_boxes', 'wf_process_time_woocommerce_meta' );
//add meta box extra price //

//metabox extra_price callback//
function wf_process_time_woocommerce_callback() {


  // Noncename needed to verify where the data originated
      wp_nonce_field( basename(__FILE__), 'process_time_woocommerce_meta_nonce' );

  
  global $post;

  //Get process_time_woocommerce if already exists
  $process_time_woocommerce = get_post_meta($post->ID, 'process_time_woocommerce', true);
  //display the process_time_woocommerce //
  echo '<div class="process-time-woocommerce-field">'._e('Process Time(minutes)', 'woofood-plugin').'<input type="text" name="process_time_woocommerce" value="' . $process_time_woocommerce  . '"  /></div>';

  }
//metabox process_time_woocommerce callback//

//save meta data //
   function wf_process_time_woocommerce_meta_save($post_id) {
    if (!isset($_POST['process_time_woocommerce_meta_nonce']) || !wp_verify_nonce($_POST['process_time_woocommerce_meta_nonce'], basename(__FILE__))) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
   
    //check and save extra_option_price meta//
    if(isset($_POST['process_time_woocommerce'])) {
      update_post_meta($post_id, 'process_time_woocommerce', $_POST['process_time_woocommerce']);
    } else {
      delete_post_meta($post_id, 'process_time_woocommerce');
    }
    //check and save extra_option_price meta//

    

  }
    add_action('save_post', 'wf_process_time_woocommerce_meta_save');











    

$options_woofood = get_option('woofood_options');

$woofood_delivery_time = isset($options_woofood['woofood_delivery_time']) ? $options_woofood['woofood_delivery_time']: null ;

/*if delivery time has been set*/




add_action( 'woocommerce_thankyou', 'wf_delivery_time_thankyou',1, 1  );
 
function wf_delivery_time_thankyou($order_id) {
$order = wc_get_order($order_id);

?>
<?php
$minutes_to_arrive = get_post_meta($order_id , 'minutes_to_arrive', true); 
$order_type = get_post_meta($order_id , 'woofood_order_type', true); 

$options_woofood = get_option('woofood_options');

$woofood_delivery_time = $options_woofood['woofood_delivery_time'];
$woofood_pickup_time = $options_woofood['woofood_pickup_time'];
if($woofood_pickup_time == 0)
{
  $woofood_pickup_time = $woofood_delivery_time;

}

$default_time_format = get_option('time_format');
$default_date_format = get_option('date_format');


$woofood_time_to_deliver = get_post_meta($order_id , 'woofood_time_to_deliver', true); 
$woofood_date_to_deliver = get_post_meta($order_id , 'woofood_date_to_deliver', true); 


if($woofood_time_to_deliver)
{
  if($woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap"  )
{
$woofood_time_to_deliver = date_i18n( $default_time_format, strtotime($woofood_time_to_deliver  ) );

}
}

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

if($minutes_to_arrive=="" ||$minutes_to_arrive == null )
{

  //checking if  custom checkbox  has value else set 0//
if(isset($options_woofood['woofood_auto_delivery_time']) && $options_woofood['woofood_auto_delivery_time'] != "" )
{

    $woofood_auto_delivery_time = $options_woofood['woofood_auto_delivery_time'];

}
else{

    $woofood_auto_delivery_time = "0";
}

if($woofood_auto_delivery_time)
{

      $total_time_to_delivery = 0;
      foreach( $order-> get_items() as $item_key => $item_values ):

      ## Using WC_Order_Item methods ##

      // Item ID is directly accessible from the $item_key in the foreach loop or
        $item_id = $item_values->get_id();

      $item_name = $item_values->get_name(); // Name of the product
      $item_type = $item_values->get_type(); // Type of the order item ("line_item")

      ## Access Order Items data properties (in an array of values) ##
      $item_data = $item_values->get_data();

      $product_name = $item_data['name'];
      $product_id = $item_data['product_id'];
      $variation_id = $item_data['variation_id'];
      $quantity = $item_data['quantity'];
      $tax_class = $item_data['tax_class'];
      $line_subtotal = $item_data['subtotal'];
      $line_subtotal_tax = $item_data['subtotal_tax'];
      $line_total = $item_data['total'];
      $line_total_tax = $item_data['total_tax'];
    $total_time_to_delivery += (int) get_post_meta($product_id, 'process_time_woocommerce',  true)*$quantity;

      endforeach;
      $woofood_delivery_time = $total_time_to_delivery;
    }

}
else
{
  $woofood_delivery_time = $minutes_to_arrive;
  $woofood_pickup_time = $minutes_to_arrive;

}

   if(!$order->has_status("failed"))
        {

        if($order_type !="pickup")
        {


          if(!empty($woofood_date_to_deliver))
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_date_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Delivery Date', 'woofood-plugin'); ?></span></div>

            <?php

            
          }




          if(!empty($woofood_time_to_deliver) && $woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap")
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_time_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Delivery Time', 'woofood-plugin'); ?></span></div>

            <?php

            
          }
          else 
          {
            if($woofood_delivery_time > 0)
            {


            ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text zoom"><?php echo $woofood_delivery_time;?><span class="wf_minutes_format <?php echo woofood_get_minutes_format(true); ?>"><?php echo woofood_get_minutes_format(); ?></span></span></div><span class="delivery_date_title"><?php _e('Delivery Time', 'woofood-plugin'); ?></span></div>


           
            <?php

           }

          }


      ?>

       


<?php
}

else
        {
           if(!empty($woofood_date_to_deliver))
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_date_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Pickup Date', 'woofood-plugin'); ?></span></div>

            <?php

            
          }




          if(!empty($woofood_time_to_deliver) && $woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap" )
          {
             ?>
                         <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_time_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Pickup Time', 'woofood-plugin'); ?></span></div>


            <?php

            
          }
          else
          {
            if($woofood_pickup_time >0) 
            {
            ?>

             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text zoom"><?php echo $woofood_pickup_time;?><span class="wf_minutes_format <?php echo woofood_get_minutes_format(true); ?>"><?php echo woofood_get_minutes_format(); ?></span></span></div><span class="delivery_date_title"><?php _e('Pickup Time', 'woofood-plugin'); ?></span></div>


            <?php
          }

           

          }


      ?>

       


<?php
}
}

}

add_action( 'woocommerce_email_before_order_table', 'wf_delivery_time_email',10, 4  );


function wf_delivery_time_email($order, $sent_to_admin, $plain_text, $email ){
      if( 'customer_processing_order' == $email->id || 'new_order' == $email->id ){

  $options_woofood = get_option('woofood_options');

$woofood_delivery_time = $options_woofood['woofood_delivery_time'];
$woofood_pickup_time = $options_woofood['woofood_pickup_time'];
if($woofood_pickup_time == 0)
{
  $woofood_pickup_time = $woofood_delivery_time;

}
$default_time_format = get_option('time_format');
$default_date_format = get_option('date_format');

$order_id = $order->get_id();
$order_type = get_post_meta($order_id , 'woofood_order_type', true); 
$minutes_to_arrive = get_post_meta($order_id , 'minutes_to_arrive', true); 
$default_time_format = get_option('time_format');

$woofood_time_to_deliver = get_post_meta($order_id , 'woofood_time_to_deliver', true); 
$woofood_date_to_deliver = get_post_meta($order_id , 'woofood_date_to_deliver', true); 

if($woofood_time_to_deliver)
{
if($woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap"  )
{
  $woofood_time_to_deliver = date_i18n( $default_time_format, strtotime($woofood_time_to_deliver  ) );

}
}

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
if($minutes_to_arrive=="" ||$minutes_to_arrive == null )
{
//checking if  custom checkbox  has value else set 0//
if(isset($options_woofood['woofood_auto_delivery_time']) && $options_woofood['woofood_auto_delivery_time'] != "" )
{

    $woofood_auto_delivery_time = $options_woofood['woofood_auto_delivery_time'];

}
else{

    $woofood_auto_delivery_time = "0";
}

if($woofood_auto_delivery_time)
{
$items = $order->get_items();
$total_time_to_delivery = 0;
    foreach ( $items as $item_id => $item ) :
      if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) :
    $product = $item->get_product();
  
    $total_time_to_delivery += (int) get_post_meta($product->id, 'process_time_woocommerce',  true);
    endif;
    endforeach;
 
    $woofood_delivery_time = $total_time_to_delivery;
  }//end if
}
else
{
      $woofood_delivery_time = $minutes_to_arrive;
      $woofood_pickup_time = $minutes_to_arrive;


}

  if(!$order->has_status("failed"))
        {
if($order_type !="pickup")
        {


          if(!empty($woofood_date_to_deliver))
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_date_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Delivery Date', 'woofood-plugin'); ?></span></div>

            <?php

            
          }




          if(!empty($woofood_time_to_deliver) && $woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap")
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_time_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Delivery Time', 'woofood-plugin'); ?></span></div>

            <?php

            
          }
          else 
          {
            if($woofood_delivery_time > 0)
            {


            ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text zoom"><?php echo $woofood_delivery_time;?><span class="wf_minutes_format <?php echo woofood_get_minutes_format(true); ?>"><?php echo woofood_get_minutes_format(); ?></span></span></div><span class="delivery_date_title"><?php _e('Delivery Time', 'woofood-plugin'); ?></span></div>


           
            <?php

           }

          }


      ?>

       


<?php
}

else
        {
           if(!empty($woofood_date_to_deliver))
          {
             ?>
             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_date_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Pickup Date', 'woofood-plugin'); ?></span></div>

            <?php

            
          }




          if(!empty($woofood_time_to_deliver) && $woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap" )
          {
             ?>
                         <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text"><?php echo $woofood_time_to_deliver;?></span></div><span class="delivery_date_title"><?php _e('Pickup Time', 'woofood-plugin'); ?></span></div>


            <?php

            
          }
          else
          {
            if($woofood_pickup_time >0) 
            {
            ?>

             <div class="delivery_date"><div class="delivery_date_wrapper"><span class="delivery_date_text zoom"><?php echo $woofood_pickup_time;?><span class="wf_minutes_format <?php echo woofood_get_minutes_format(true); ?>"><?php echo woofood_get_minutes_format(); ?></span></span></div><span class="delivery_date_title"><?php _e('Pickup Time', 'woofood-plugin'); ?></span></div>


            <?php
          }

           

          }


      ?>

       


<?php
}
}

}
}










function woofood_get_minutes_format($return_key = false)
{
  $minutes_format = array(
"default" => "'",
"mins" => esc_html__('mins', 'woofood-plugin'),
"minutes" => esc_html__('minutes', 'woofood-plugin')

  );
  $options_woofood = get_option('woofood_options');
  $woofood_minutes_display_format = isset($options_woofood["woofood_minutes_display_format"]) ? $options_woofood["woofood_minutes_display_format"] : null;

  if($woofood_minutes_display_format)
  {
    if($return_key)
{
      return $options_woofood["woofood_minutes_display_format"];

}
    return $minutes_format[$options_woofood["woofood_minutes_display_format"]];

  }
  else
  {
    if($return_key)
{
      return "default";

}
        return $minutes_format["default"];

  }

}




add_filter( 'woocommerce_email_styles', 'woofood_add_emai_css', 9999, 2 );
 
function woofood_add_emai_css( $css, $email ) { 
$css .= '
  span.delivery_date_text.zoom
  {
        font-size: 40px;
    margin: auto;
  }
  .delivery_date_wrapper
  {
        max-width: 300px;
    margin: auto;
    border: 1px solid #949494;
    padding: 10px;
    border-radius: 5px;
    height: 68px;
    box-shadow: -2px 2px 2px #9a939385;
    margin-top: 20px;
    box-sizing: border-box;
  }
  span.delivery_date_text
  {
        font-size: 28px;
    margin: auto;
    display: flex;
    justify-content: center;
    flex-direction: row;
    margin-top: 7px;
    margin:auto;

  }
  .delivery_date_wrapper
  {
        max-width: 300px;
    margin: auto;
    border: 1px solid #949494;
    padding: 10px;
    border-radius: 5px;
    height: 68px;
    box-shadow: -2px 2px 2px #9a939385;
    margin-top: 20px;
  }
  span.delivery_date_title
  {
    box-sizing: border-box;
       font-size: 14px;
    text-transform: uppercase;
    position: relative;
    padding: 4px 5px;
    text-align: center;
    margin-top: 0px;
    margin: auto;
    display: inline-block;
    font-weight: 700;
    background: #cc0000;
    color: white;
  }
  .delivery_date
  {    text-align: center;
    margin-bottom: 22px;
  }
';
return $css;
}
?>