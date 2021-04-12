<div id="rmwc_address_tab" class="rmagic-table">
    
    <?php
    $customer_id = get_current_user_id();

    if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
            $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
                    'billing' => __( 'Billing Address', 'registrationmagic-gold' ),
                    'shipping' => __( 'Shipping Address', 'registrationmagic-gold' )
            ), $customer_id );
    } else {
            $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
                    'billing' =>  __( 'Billing Address', 'registrationmagic-gold' )
            ), $customer_id );
    }

$oldcol = 1;
$col    = 1;
?>



<?php foreach ( $get_addresses as $name => $title ) : ?>

	<div class="rmwc-fe-address">
		<header class="woocommerce-Address-title title">
			<h3><?php echo $title; ?></h3>
			<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ,get_permalink(wc_get_page_id('myaccount'))) ); ?>" class="edit"><?php _e( 'Edit', 'registrationmagic-gold' ); ?></a>
		</header>
		<address>
			<?php
				$address = apply_filters( 'woocommerce_my_account_my_address_formatted_address', array(
					'first_name'  => get_user_meta( $customer_id, $name . '_first_name', true ),
					'last_name'   => get_user_meta( $customer_id, $name . '_last_name', true ),
					'company'     => get_user_meta( $customer_id, $name . '_company', true ),
					'address_1'   => get_user_meta( $customer_id, $name . '_address_1', true ),
					'address_2'   => get_user_meta( $customer_id, $name . '_address_2', true ),
					'city'        => get_user_meta( $customer_id, $name . '_city', true ),
					'state'       => get_user_meta( $customer_id, $name . '_state', true ),
					'postcode'    => get_user_meta( $customer_id, $name . '_postcode', true ),
					'country'     => get_user_meta( $customer_id, $name . '_country', true )
				), $customer_id, $name );

				$formatted_address = WC()->countries->get_formatted_address( $address );

				if ( ! $formatted_address )
					_e( 'You have not set up this type of address yet.', 'registrationmagic-gold' );
				else
					echo $formatted_address;
			?>
		</address>
	</div>

<?php endforeach; ?>


</div>