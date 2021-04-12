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
                
                <?php if(empty($orders)): ?>
                    <tr><td colspan="6"><?php _e('There are no orders yet.','registrationmagic-gold') ?></td></tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
