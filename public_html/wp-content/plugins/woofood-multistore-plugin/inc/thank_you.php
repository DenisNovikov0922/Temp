<?php

add_action( 'woocommerce_thankyou', 'woofood_multistore_extra_store_info',3, 1  );
 
function woofood_multistore_extra_store_info($order_id) {

?>
<?php
$store_id = get_post_meta($order_id , 'extra_store_name', true); 
$store_name = get_the_title($store_id);
$store_phone = get_post_meta($store_id , 'extra_store_phone', true); 
$store_address = get_post_meta($store_id , 'extra_store_address', true); 
$store_email = get_post_meta($store_id , 'extra_store_email', true); 
?>
<div class="woofood-multistore-store-detail-thx">
  <div class="woofood-multistore-store-detail-header">
    <?php echo esc_html__('Your Order has been allocated to ', 'woofood-multistore-plugin')."<br/><strong>".$store_name."</strong>"; ?><nr/>
  </div>
   <div class="woofood-multistore-store-detail-content">
    <div class="woofood-multistore-store-content-desc">
<?php esc_html_e("Store Information", "woofood-multistore-plugin"); ?>
 </div>

  <?php if(!empty($store_phone)): ?>

    <div class="woofood-multistore-store-detail-row">
   <strong><?php esc_html_e("Phone Number:", "woofood-multistore-plugin"); ?></strong> <?php echo $store_phone; ?>
 </div>
<?php endif; ?>
  <?php if(!empty($store_address)): ?>

 <div class="woofood-multistore-store-detail-row">
   <strong><?php esc_html_e("Address:", "woofood-multistore-plugin"); ?></strong> <?php echo $store_address; ?>
 </div>
 <?php endif; ?>

  <?php if(!empty($store_email)): ?>
  <div class="woofood-multistore-store-detail-row">
   <strong><?php esc_html_e("Email:", "woofood-multistore-plugin"); ?></strong> <?php echo $store_email; ?>
 </div>
 <?php endif; ?>



  </div>
</div>
<?php

}
?>