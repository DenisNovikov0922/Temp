<?php

add_action( 'woocommerce_cart_calculate_fees', 'wpslash_tipping_calculate_total' );
function wpslash_tipping_calculate_total( $cart ) {
	$tipping_taxable=  ( !empty(get_option( 'wc_settings_tab_wpslash_tipping_taxable', true )) ) && ( get_option( 'wc_settings_tab_wpslash_tipping_taxable', true ) =='yes' )  ?  true : false;
	$tax_class =  !empty(get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true ) : '';

	if (( is_admin() && ! is_ajax() ) ) {
		return;
	}



	global $woocommerce;

		$tip = WC()->session->get( 'wpslash_tip_selected' );


	if ($tip) {

		WC()->cart->add_fee( 'Mancia' , abs($tip), $tipping_taxable, ( $tipping_taxable ) ? $tax_class :'' );
	 
		WC()->session->set( 'wpslash_tip_name', 'Mancia' );


	 
	}

}


add_action( 'woocommerce_checkout_create_order_fee_item', 'wpslash_tipping_save_meta', 10, 4 );
function wpslash_tipping_save_meta( $item, $fee_key, $fee, $order ) {

		$tip = WC()->session->get( 'wpslash_tip_selected' );

	if ($tip) {
		$order->update_meta_data( '_wpslash_tip', $tip );
	}
}

add_action( 'woocommerce_checkout_create_order', 'wpslash_tipping_remove_fee_from_wc_session', 10, 2 );
function wpslash_tipping_remove_fee_from_wc_session( $order, $data ) {
	$fees_meta = WC()->session->__unset('wpslash_tip_selected');
}
