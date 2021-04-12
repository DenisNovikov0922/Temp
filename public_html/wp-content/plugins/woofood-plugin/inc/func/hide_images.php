<?php
add_action( 'init', 'woofood_hide_images_hook' );
function woofood_hide_images_hook()
{
	$woofood_options = get_option('woofood_options');
$woofood_enable_hide_images = isset($woofood_options['woofood_enable_hide_images']) ? $woofood_options['woofood_enable_hide_images'] : null;
if ($woofood_enable_hide_images){
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	add_filter( 'woocommerce_cart_item_thumbnail', '__return_empty_string' );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
}

}
?>