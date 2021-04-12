<?php
//add filter to search on more fields//
add_filter( 'woocommerce_shop_order_search_fields', 'wf_shop_order_search_woofood_type' );
function wf_shop_order_search_woofood_type( $search_fields ) {
    $search_fields[] = 'woofood_order_type';

    return $search_fields;
}
//add filter to search on more fields//
?>