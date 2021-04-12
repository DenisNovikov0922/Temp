<div class="rmagic-table" id="rmwc_account_tab">
     
	<?php
		//do_action( 'woocommerce_account_content' );
                
    
    ob_start();
    //require_once plugin_dir_path(__FILE__).'view_user_details.php'; 
    echo ob_get_clean();
	?>
 </div>

<div class="rmagic-table" id="rmwc_orders_tab">
    <div id="rmwc_user_view_orders_table">
        <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">
            <tbody>
                <tr>
                    <th>&#35;</th>
                    <th><?php echo RM_WC_UI_Strings::get('LABEL_ORDER_STATUS'); ?></th>
                    <th><?php echo RM_WC_UI_Strings::get('LABEL_ITEMS'); ?></th>
                    <th><?php echo RM_WC_UI_Strings::get('LABEL_PLACED_ON'); ?></th>
                    <th><?php echo RM_WC_UI_Strings::get('LABEL_AMOUNT'); ?></th>
                    <th>&nbsp;</th>
                </tr>
                <?php 
                foreach($orders as $o)
                {
                    echo "<tr><td>$o->id</td><td>$o->status</td><td>$o->item_count</td><td>$o->placed_on</td><td>$o->total</td>";
                    echo "<td>";
                    $order = $o->wc_order_object;
                        $actions = array(
                                'pay'    => array(
                                        'url'  => $order->get_checkout_payment_url(),
                                        'name' => __( 'Pay', 'registrationmagic-gold' )
                                ),
                                'view'   => array(
                                        'url'  => $order->get_view_order_url(),
                                        'name' => __( 'View', 'registrationmagic-gold' )
                                ),
                                'cancel' => array(
                                        'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
                                        'name' => __( 'Cancel', 'registrationmagic-gold' )
                                )
                        );

                        if ( ! $order->needs_payment() ) {
                                unset( $actions['pay'] );
                        }

                        if ( ! in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
                                unset( $actions['cancel'] );
                        }

                        if ( $actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order ) ) {
                                foreach ( $actions as $key => $action ) {
                                        echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                                }
                        }
                        
                        echo "</td></tr>";
								
                }
                ?>

            </tbody>
        </table>
    </div>
</div>

<div class="rmagic-table" id="rmwc_downloads_tab">
        <table class="user-content ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: table;">
        <tbody>
            <tr>
                <th>&#35;</th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_NAME'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_REMAINING_DOWNLOADS'); ?></th>
                <th><?php echo RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES'); ?></th>
                <th>&nbsp;</th>
            </tr>
            <?php 
            $i =1;
            foreach($downloads as $dl)
            {
              
                $rem_dls = (strlen($dl['downloads_remaining']) === 0) ? RM_WC_UI_Strings::get('LABEL_REMAINING_DLS_UNLIMITED') : $dl['downloads_remaining'];                
                $acc_exp = (!$dl['access_expires']) ? RM_WC_UI_Strings::get('LABEL_ACCESS_EXPIRES_NEVER') : strtok($dl['access_expires'],' ');
                echo "<tr><td>$i</td><td>{$dl['download_name']}</td><td>{$rem_dls}</td><td>{$acc_exp}</td><td><a href='{$dl['download_url']}'>".RM_WC_UI_Strings::get('LABEL_DOWNLOAD')."</a></td></tr>";
                $i++;
            }
            
            ?>

        </tbody>
    </table>
</div>

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

<style>
    .rmwc-fe-address {
     width: 50%; 
     float: left; 
}
    
</style>