<?php
add_action( 'wp_ajax_wpslash_tip_submit_handler', 'wpslash_tip_submit_handler' );

add_action( 'wp_ajax_nopriv_wpslash_tip_submit_handler', 'wpslash_tip_submit_handler' );

function wpslash_tip_submit_handler() {
		 global $woocommerce;
		check_ajax_referer('wpslash_tip_security', 'security');
	
	$tipping_taxable=  ( !empty(get_option( 'wc_settings_tab_wpslash_tipping_taxable', true )) ) && ( get_option( 'wc_settings_tab_wpslash_tipping_taxable', true ) =='yes' )  ?  true : false;
	$tax_class =  !empty(get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true )) ?  get_option( 'wc_settings_tab_wpslash_tipping_tax_class', true ) : '';
	$is_pecentage = false; 
	$percentage = 0;
	$tip = null;
	if (isset($_POST['percentage'])) {
		$is_pecentage = true;
		$percentage = intval( $_POST['percentage'] );

	}


	if ($is_pecentage) {
		 $subtotal = WC()->cart->get_subtotal();
		 $taxes  = WC()->cart->get_subtotal_tax();

		

			$tip =  ( ( $subtotal+$taxes ) * ( $percentage/100 ) );
		if ($tipping_taxable) {
			$wc_tax = new WC_Tax();
			$tax_rates = $wc_tax->find_rates( array('country' => WC()->customer->get_billing_country(), 'tax_class' => $tax_class ) );
			$tax_rate = 0;
			if ( ! empty($tax_rates) ) {
				$tax_rate = reset($tax_rates)['rate'];
			}

			$tip = $tip / ( ( 100+ $tax_rate )/100 );
		}


	} else {
		if (isset($_POST['amount'])) {	
			$tip = floatval( $_POST['amount'] );

		}

	}
	
	 WC()->session->set( 'wpslash_tip_selected', $tip   );

	wp_die(); // this is required to terminate immediately and return a proper response
}


add_action( 'wp_ajax_wpslash_tip_remove', 'wpslash_tip_remove' );

add_action( 'wp_ajax_nopriv_wpslash_tip_remove', 'wpslash_tip_remove' );

function wpslash_tip_remove() {
		 global $woocommerce;
				check_ajax_referer('wpslash_tip_security', 'security');

	 WC()->session->set( 'wpslash_tip_selected', false   );

	wp_die(); // this is required to terminate immediately and return a proper response
}

