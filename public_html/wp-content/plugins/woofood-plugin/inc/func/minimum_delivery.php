<?php
//minimum delivery amount//
$options_woofood = get_option('woofood_options');
$woofood_minimum_delivery_amount = isset($options_woofood['woofood_minimum_delivery_amount']) ? $options_woofood['woofood_minimum_delivery_amount'] : null;
$method = "";
if(isset($_POST["woofood_order_type"]))
{
    $method = $_POST["woofood_order_type"];
}
else{
    $method = "delivery";
}

if (!empty($woofood_minimum_delivery_amount) && ($method =="delivery"))
{

  add_action( 'woocommerce_checkout_process', 'wf_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wf_minimum_order_amount' );
 
function wf_minimum_order_amount() {
  $options_woofood = get_option('woofood_options');
$woofood_minimum_delivery_amount = $options_woofood['woofood_minimum_delivery_amount'];
    // Set this variable to specify a minimum order value
    $minimum = (float)$woofood_minimum_delivery_amount;

    if ( WC()->cart->subtotal < $minimum ) {

        if( is_cart() ) {

            wc_print_notice( 
                sprintf(  __( 'You must have an order with a minimum of %s to place your order, your current order total is %s.', 'woofood-plugin' ) , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->subtotal )
                ), 'error' 
            );

        } else {

            wc_add_notice( 
                sprintf(  __( 'You must have an order with a minimum of %s to place your order, your current order total is %s.', 'woofood-plugin' ) , 
                    wc_price( $minimum ), 
                    wc_price( WC()->cart->subtotal )
                ), 'error' 
            );

        }
    }

}






}

//minimum delivery amount//

?>