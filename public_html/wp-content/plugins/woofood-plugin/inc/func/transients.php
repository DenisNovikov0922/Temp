<?php
add_action( 'save_post_shop_order', 'woofood_clear_order_transients_on_update', 10, 1 );
function woofood_clear_order_transients_on_update($order_id) {
     
      delete_transient("woofood_rest_order_id_".$order_id);

}

add_action( 'save_post_product', 'woofood_clear_product_transients_on_update', 10, 1 );
add_action( 'save_post_post', 'woofood_clear_product_transients_on_update', 10, 1 );
add_action( 'save_post_page', 'woofood_clear_product_transients_on_update', 10, 1 );

function woofood_clear_product_transients_on_update($product_id) {

  $all_transients = get_transient('woofood_all_transient_keys');

if(is_array($all_transients))
{
  foreach($all_transients as $transient)
  {
                delete_transient($transient);


  }
}
     


}

function woofood_clear_category_transients_on_update($term_id, $tt_id, $taxonomy) {
  
  if($taxonomy == "product_cat")
  {


    $all_transients = get_transient('woofood_all_transient_keys');

if(is_array($all_transients))
{
  foreach($all_transients as $transient)
  {
                delete_transient($transient);


  }
}

  }
}
add_action( 'edit_term', 'woofood_clear_category_transients_on_update', 10, 3 );
?>