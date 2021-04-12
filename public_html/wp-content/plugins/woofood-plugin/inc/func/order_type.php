<?php
//Add Store Selection on Checkout///
add_action( 'woocommerce_checkout_before_order_review', 'wf_select_woofood_order_type_field', 10, 0 );

function wf_select_woofood_order_type_field() {
  global $woocommerce;
  $woofood_options = get_option('woofood_options');
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;
  $woofood_store_address = isset($woofood_options['woofood_store_address']) ? $woofood_options['woofood_store_address'] : null  ;
  $default_order_type=woofood_get_default_order_type();
  $session_order_type = WC()->session->get( 'woofood_order_type');
  $order_types = woofood_get_order_types();
   if($session_order_type && array_key_exists($session_order_type,$order_types ))
   {
    $default_order_type=$session_order_type;

   }

  if ($woofood_enable_pickup_option){


echo '<div class="woofood_order_type">';

wf_form_field_radio( 'woofood_order_type', array(

'type'         => 'radio',

'class'         => array('wf_order_type_radio_50'),

'required'     => true,
'options'  => $order_types,

), $default_order_type);

echo '</div>';




}
else
{
  echo '<input type="radio" name="woofood_order_type" value="'.$default_order_type.'" style="display:none;" checked/>';
}

} //end function



add_action( 'woocommerce_checkout_before_order_review', 'wf_woofood_store_address_checkout_display', 11, 0 );

function wf_woofood_store_address_checkout_display() {

      $woofood_options = get_option('woofood_options');
  $woofood_store_address = isset($woofood_options['woofood_store_address']) ? $woofood_options['woofood_store_address'] : '' ;

  if ($woofood_store_address){

    echo '<div class="woofood_store_address_checkout">';
echo '<h4>'.esc_html__('Address To Pickup', 'woofood-plugin').':</h4>';
echo $woofood_store_address;
echo '</div>';

}

}


add_action( 'woocommerce_after_checkout_form', 'woofood_add_store_address_if_pickup_is_checked');
 
function woofood_add_store_address_if_pickup_is_checked() {

    $woofood_options = get_option('woofood_options');
  $woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $woofood_options['woofood_enable_pickup_option'] : null ;

?>
<script>
jQuery(document).ready(function () {



  var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();


  if(woofood_order_type=="pickup")
  {
            jQuery('#ship-to-different-address-checkbox').prop( "checked", false );

            jQuery('#wf-time-to-deliver.delivery').removeClass('open');
            jQuery('#wf-date-to-deliver.delivery').removeClass('open');

            jQuery('#wf-date-to-deliver.pickup').addClass('open');

        jQuery('#wf-time-to-deliver.pickup').addClass('open');

        jQuery('.woofood_store_address_checkout').addClass('open');

    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")
   {

                jQuery('#wf-time-to-deliver.pickup').removeClass('open');
                                jQuery('#wf-date-to-deliver.pickup').removeClass('open');


            jQuery('#wf-time-to-deliver.delivery').addClass('open');
            jQuery('#wf-date-to-deliver.delivery').addClass('open');

            jQuery('.woofood_store_address_checkout').removeClass('open');

    //jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  



    jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
            jQuery('#ship-to-different-address-checkbox').prop( "checked", false );

                    jQuery('#wf-time-to-deliver.delivery').removeClass('open');
                                        jQuery('#wf-date-to-deliver.delivery').removeClass('open');


            jQuery('#wf-time-to-deliver.pickup').addClass('open');
            jQuery('#wf-date-to-deliver.pickup').addClass('open');

            jQuery('.woofood_store_address_checkout').addClass('open');


    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")

   {                jQuery('#wf-time-to-deliver.pickup').removeClass('open');
                   jQuery('#wf-date-to-deliver.pickup').removeClass('open');

                jQuery('#wf-time-to-deliver.delivery').addClass('open');
                jQuery('#wf-date-to-deliver.delivery').addClass('open');

                jQuery('.woofood_store_address_checkout').removeClass('open');

   // jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  

    

        return false;
    });


});

</script>

<?php



}



add_action( 'woocommerce_checkout_update_order_meta', 'wf_update_order_meta_order_type' ,10, 99);

function wf_update_order_meta_order_type( $order_id, $posted ) {
  $order = wc_get_order( $order_id );
if ( ! empty( $_POST['woofood_order_type'] ) ) {
 

    $order->update_meta_data( 'woofood_order_type', sanitize_text_field( $_POST['woofood_order_type'] ));

    $order->save();
}

else {

  $order->update_meta_data( 'woofood_order_type', woofood_get_default_order_type() );

    $order->save();




}

}//end function


 $woofood_options = get_option('woofood_options');
$woofood_enable_time_to_deliver_option = isset($woofood_options['woofood_enable_time_to_deliver_option']) ? $options_woofood['woofood_enable_time_to_deliver_option']: null;
$woofood_enable_pickup_option = isset($woofood_options['woofood_enable_pickup_option']) ? $options_woofood['woofood_enable_pickup_option']: null;
if($woofood_enable_time_to_deliver_option  && $woofood_enable_pickup_option )
{
    add_action( 'woocommerce_after_checkout_form', 'woofood_order_type_change_script');
 
function woofood_order_type_change_script() {
?>
<script>
jQuery(document).ready(function () {


  


    jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();
            if(woofood_order_type =="delivery")
            {
                jQuery('#wf-time-to-deliver .wf_tdlvr_title').text('<?php esc_html_e('Time to Deliver ','woofood-plugin'); ?>');

            }
            else if(woofood_order_type =="pickup")
            {
                jQuery('#wf-time-to-deliver .wf_tdlvr_title').text('<?php esc_html_e('Time to Pickup ','woofood-plugin'); ?>');

            }

        return false;
    });


});

</script>

<?php

}

}


function woofood_get_order_types()
{
      $order_types = array('delivery'=>esc_html__('Delivery', 'woofood-plugin'), 'pickup'=>esc_html__('Pickup', 'woofood-plugin'));

        return apply_filters( 'woofood_order_types_filter', $order_types);

}

function woofood_get_default_order_type()
{
        $default_order_type = "delivery";

        return apply_filters( 'woofood_default_order_type_filter', $default_order_type);

}

function woofood_get_order_type_by_key($key)
{
        $order_types = woofood_get_order_types();
        if(array_key_exists($key, $order_types))
        {
          return $order_types[$key];
        }

        return "";

}




 
function woofood_no_address_validation_on_pickup( $woo_checkout_fields_array ) {
   if(isset($_POST["woofood_order_type"]))
  {
    if($_POST["woofood_order_type"] == "pickup")
    {
// unset($woo_checkout_fields_array['billing']['billing_phone']);
 unset($woo_checkout_fields_array['billing']['billing_address_1']);
 unset( $woo_checkout_fields_array['billing']['billing_county']);
  unset($woo_checkout_fields_array['billing']['billing_state']);
  unset($woo_checkout_fields_array['billing']['billing_country']);
  unset($woo_checkout_fields_array['billing']['billing_postcode']);
  unset($woo_checkout_fields_array['billing']['billing_city']);

  unset($woo_checkout_fields_array['shipping']['shipping_address_1']);
  unset($woo_checkout_fields_array['shipping']['shipping_county']);
  unset($woo_checkout_fields_array['shipping']['shipping_state']);
  unset($woo_checkout_fields_array['shipping']['shipping_country']);
  unset($woo_checkout_fields_array['shipping']['shipping_postcode']);
  unset($woo_checkout_fields_array['shipping']['shipping_city']);
}
}

    


  return $woo_checkout_fields_array;
}






add_action( 'woocommerce_after_checkout_form', 'woofood_hide_shipping_fields_for_pickup');
 
function woofood_hide_shipping_fields_for_pickup() {

    $woofood_options = get_option('woofood_options');
  $woofood_hide_address_on_pickup_option = isset($woofood_options['woofood_hide_address_on_pickup_option']) ? $woofood_options['woofood_hide_address_on_pickup_option'] : null ;
  if ($woofood_hide_address_on_pickup_option){

      wp_enqueue_script('woofood-hide-shipping-js', WOOFOOD_PLUGIN_URL.'js/hide_shipping_fields.js', array('jquery'), WOOFOOD_PLUGIN_VERSION, 'all' );

}
}





add_action( 'woocommerce_checkout_process', 'woofood_do_not_validate_on_pickup', 999999,1);

function woofood_do_not_validate_on_pickup( $woo_checkout_fields_array ) {
      $woofood_options = get_option('woofood_options');

 $woofood_hide_address_on_pickup_option = isset($woofood_options['woofood_hide_address_on_pickup_option']) ? $woofood_options['woofood_hide_address_on_pickup_option'] : null ;
  if ($woofood_hide_address_on_pickup_option){
    
     add_filter( 'woocommerce_checkout_fields', 'woofood_no_address_validation_on_pickup', 999999  );
  }
}

?>