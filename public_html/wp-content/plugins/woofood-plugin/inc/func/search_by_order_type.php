<?php
 function wf_filter_orders_by_order_type() {
  
        global $typenow;
        if ( 'shop_order' === $typenow ) {
                
                $woofood_order_types = woofood_get_order_types();

                //to be added on next version //    
              /*  $args = array(
                    'role__in' => array('deliveryboy_user')
                    );
            $users = get_users( $args );*/
                //to be added on next version //    


            if ( ! empty( $woofood_order_types )) : ?>


                <select name="woofood_order_type" id="dropdown_order_type">
                    <option value="">
                        <?php esc_html_e( 'Filter by Type', 'woofood-plugin' ); ?>
                    </option>
                    <?php foreach ( $woofood_order_types as $key =>$value ) : ?>
                        <option value="<?php echo esc_attr( $key); ?>">
                            <?php echo esc_html( $value ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>


            <?php endif;


                //to be added on next version //    

           /* if ( ! empty( $users )) : ?>


                <select name="woofood_deliveryboy" id="dropdown_order_type">
                    <option value="">
                        <?php esc_html_e( 'Filter by Delivery Boy', 'woofood-plugin' ); ?>
                    </option>
                    <?php foreach ( $users as $user ) : ?>
                        <option value="<?php echo esc_attr( $user->ID); ?>">
                            <?php echo esc_html( $user->data->display_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>


            <?php endif;*/
                //to be added on next version //    



        }
    }


    function wf_orders_parse_query_filter( $query ){
    global $pagenow;
    $type = 'shop_order';
    
    if (isset($_GET['post_type'])) {
        $type = $_GET['post_type'];
    }
    if ( 'shop_order' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['woofood_order_type']) && $_GET['woofood_order_type'] != '') {
        $query->query_vars['meta_key'] = 'woofood_order_type';
        $query->query_vars['meta_value'] = $_GET['woofood_order_type'];
    }

    if ( 'shop_order' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['woofood_deliveryboy']) && $_GET['woofood_deliveryboy'] != '') {
        $query->query_vars['meta_key'] = 'wf_deliveryboy';
        $query->query_vars['meta_value'] = $_GET['woofood_deliveryboy'];
    }
}

add_filter( 'parse_query', 'wf_orders_parse_query_filter' );


        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
add_action( 'restrict_manage_posts','wf_filter_orders_by_order_type');



}
?>