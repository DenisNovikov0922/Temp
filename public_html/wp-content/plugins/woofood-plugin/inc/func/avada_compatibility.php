<?php
 $woofood_options = get_option('woofood_options');
  $woofood_enable_avada_compatibility_option = isset($woofood_options['woofood_enable_avada_compatibility_option']) ?  $woofood_options['woofood_enable_avada_compatibility_option'] : null;


  if ($woofood_enable_avada_compatibility_option) {





    add_action("wp_footer", "woofood_avada_extra_css");

}
    function  woofood_avada_extra_css()
    {?>
      <style>
        .woocommerce-content-box.full-width.checkout_coupon
        {
          width:100%;
        }
        .clearboth
        {
          display:none;
        }
        .woocommerce-content-box.full-width
        {
          width:48%;
          float:left;
        }
        .address-change-header
        {
          display:block;
        }
       
        .woocommerce div.product p.price, .woocommerce div.product span.price, .wf_product_view .price
        {
          display: block;
vertical-align: middle !important;
padding: 10px !important;
background: #cc0000 !important;
color: white !important;
text-align: right !important;
float: right;
border-left: 5px solid black;
        }
        .woocommerce-variation-price
        {
          display:none;
        }
       .wf_product_view .input-text.qty.text
        {
          width: 51px;
        }

         .wf_product_view .input-text.qty.text::-webkit-outer-spin-button,
 .wf_product_view .input-text.qty.text::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
  .fusion-body .quantity .minus, .fusion-body .quantity .plus
        {
          background-color: white;
width: 40px;
height: 40px;

        }
      .wf_product_view  .quantity.buttons_added
        {
width: 115px;
        }
      
      </style>

      <?php

    }

?>