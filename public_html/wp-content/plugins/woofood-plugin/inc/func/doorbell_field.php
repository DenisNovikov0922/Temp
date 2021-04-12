<?php
 $woofood_options = get_option('woofood_options');
  $woofood_enable_doorbell_option = isset($woofood_options['woofood_enable_doorbell_option']) ? $options_woofood['woofood_enable_doorbell_option']: null;
  if ($woofood_enable_doorbell_option){
//add doorbell checkout field
add_filter( 'woocommerce_checkout_fields' , 'wf_doorbell_field_checkout' );

// Our hooked in function - $fields is passed via the filter!
function wf_doorbell_field_checkout( $fields ) {
     $fields['billing']['doorbell'] = array(
        'label'     => esc_html__('Name on Doorbell', 'woofood-plugin'),
    'placeholder'   => _x('Name on Doorbell', 'placeholder', 'woofood-plugin'),
    'required'  => false,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

     return $fields;
}

/**
 * Display field value on the order edit page
 */
 



add_action( 'woocommerce_checkout_update_order_meta', 'wf_update_order_meta_doorbell' );

function wf_update_order_meta_doorbell( $order_id ) {
  $order = wc_get_order( $order_id );
if ( ! empty( $_POST['doorbell'] ) ) {
 

    $order->update_meta_data( 'doorbell', sanitize_text_field( $_POST['doorbell'] ));

    $order->save();
}

else {





}
}//end function

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wf_date_checkout_admin_order_meta', 10, 1 );

function wf_date_checkout_admin_order_meta($order){
    echo '<p><strong>'.esc_html__('Giorno di consegna').':</strong> ' . (new DateTime(get_post_meta( $order->get_id(), 'woofood_date_to_deliver', true )))->format('d-m-Y') . '</p>';
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wf_doorbell_checkout_admin_order_meta', 10, 1 );

function wf_doorbell_checkout_admin_order_meta($order){
    echo '<p><strong>'.esc_html__('Name on DoorBell').':</strong> ' . get_post_meta( $order->get_id(), 'doorbell', true ) . '</p>';
}
//add doorbel  checkout field

}
?>