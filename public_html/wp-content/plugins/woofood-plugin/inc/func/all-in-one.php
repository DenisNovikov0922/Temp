<?php

add_filter('woocommerce_is_checkout', 'woofood_set_checkout_page_all');

function woofood_set_checkout_page_all()
{
  if(wc_post_content_has_shortcode( 'woofood_one_page' ))
  {
    return true;
  }
}



function woofood_one_page($atts)
{

$html_export = '<div class="woofood-one-page">';
$html_export .= '<div class="woofood-one-page-left">';

$taxonomy     = 'product_cat';
//$orderby      = '';  
$show_count   = 0;      
$pad_counts   = 0;      
$hierarchical = 1;      
$title        = '';  
$empty        = 0;

$args = array(
'taxonomy'     => $taxonomy,
//'orderby'      => $orderby,
'show_count'   => $show_count,
'pad_counts'   => $pad_counts,
'hierarchical' => $hierarchical,
'title_li'     => $title,
'hide_empty'   => $empty
);


$all_categories = array();

$text_color ="";
$background_color ="";
$border_color = "";
if ( !empty( $atts['text_color'] ))
{
  $text_color =$atts['text_color'];
}
if ( !empty( $atts['background_color'] ))
{
  $background_color = $atts['background_color'];
}
$border_style="";
if ( !empty( $atts['border_color'] ))
{
  $border_color = $atts['border_color'];
  $border_style ='border: 1px solid '.$border_color.';';
}

if ( !empty( $atts['ids'] ))
{
$atts['ids'] = trim($atts['ids']); 


}

if ( !empty( $atts['category_slug'] ))
{
$atts['category_slug'] = array_map( 'trim', str_getcsv( $atts['category_slug'], ',' ) );

foreach($atts['category_slug'] as $cat_slug)
{
$all_categories[] = get_term_by( 'slug', $cat_slug, 'product_cat' );

}

}
else
{
  if ( !empty( $atts['ids'] ))
{
    $custom_category = new stdClass();

  if ( !empty( $atts['title'] ))
  {
   $custom_category->name = $atts['title'];
   $custom_category->slug = strtolower($atts['title']);

  }
  else
  {
       $custom_category->name = '';
       $custom_category->slug = mt_rand(100000, 999999);

  }

  $all_categories[] = $custom_category;


}
else
{
  $all_categories = get_categories( $args );
}


}

foreach ($all_categories as $cat) {
if($cat->category_parent == 0) {
$category_id = $cat->term_id;       

$html_export .=' <div class="woofood-accordion">';
$html_export.= ' <a class="collapsed" data-toggle="collapse" data-target="#wf-accordion-'.str_replace('%20', '-', rawurlencode($cat->slug)).'" href="#wf-accordion-'.str_replace('%20', '-', rawurlencode($cat->slug)).'" aria-expanded="false" aria-controls="collapseThree"> ';

$html_export .=' <div class="panel-heading panel-heading-title" style="
          '.$border_style.'
              background:'.$background_color.';
            "
            >';
  if (is_array($atts)):       
 if (array_key_exists("icon", $atts) && count($all_categories) == 1):
  $html_export .='<img src="'.$atts["icon"].'"/>';

endif;
endif;
$html_export .='<h4 class="panel-title" style="color: '.$text_color.'">';
$html_export .=$cat->name;
$html_export .= '</h4>';
$html_export .=' <div class="accordion-plus-icon" >
<i class="woofood-icon-plus-circled" style="color: '.$text_color.'" ></i> 
</div>  ';
$html_export .= '</div>';
$html_export .= '</a>';


$html_export .= '  <div id="wf-accordion-'.str_replace('%20', '-', rawurlencode($cat->slug)).'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">';

//$html_export .= do_shortcode('[product_category category="'.$cat->slug.'" per_page="-1"]');
  $attributes= array();

  if(!empty($atts["category_slug"]))
  {
    $attributes["category"] = $cat->slug;


  }
  else if(empty($atts["category_slug"]) && !empty($atts["ids"]) )
  {
    
  }
  else if(empty($atts["category_slug"]) && empty($atts["ids"]))
  {
    $attributes["category"] = $cat->slug;

  }
  else
  {

  }

  if(!empty($atts["ids"]))
  {
    $attributes["ids"] = $atts["ids"];


  }

  if(!empty($atts["orderby"]))
  {
    $attributes["orderby"] = $atts["orderby"];


  }
  else
  {
        $attributes["orderby"] ="menu_order";

  }
  
  
  if(!empty($atts["order"]))
  {
    $attributes["order"] = $atts["order"];


  }
  else
  {
        $attributes["order"] ="ASC";

  }

  ob_start();
woofood_products($attributes);
$html_export .= ob_get_clean();
$html_export .='
</div>
</div>
</div>';                     
}}//end foreach
$html_export .='</div>';


$html_export .='<div class="woofood-one-page-right">';
ob_start();
      echo do_shortcode('[woofood_address_changer]');



$checkout = WC()->checkout();
if ( WC()->cart->is_empty() ) {


  }
  //WC()->session->init();
      WC()->session->set( 'cart_totals', 10 );

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
  <?php  do_action( 'woocommerce_checkout_before_order_review' ); ?>

  <div id="order_review" class="woocommerce-checkout-review-order">
    <?php
        $fields = $checkout->get_checkout_fields( 'billing' );

foreach ( $fields as $key => $field ) {
  ?>
  <input type="hidden" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $checkout->get_value( $key ); ?>"/>

  <?php

    }
    ?>

<table class="shop_table woocommerce-checkout-review-order-table">
  <thead>
    <tr>
      <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
      <th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    do_action( 'woocommerce_review_order_before_cart_contents' );

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

      if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
        ?>
        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
          <td class="product-name">
            <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          </td>
          <td class="product-total">
            <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          </td>
        </tr>
        <?php
      }
    }

    do_action( 'woocommerce_review_order_after_cart_contents' );
    ?>
  </tbody>
  <tfoot>

    <tr class="cart-subtotal">
      <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
      <td><?php wc_cart_totals_subtotal_html(); ?></td>
    </tr>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
      <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
        <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
        <td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
      </tr>
    <?php endforeach; ?>

    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

      <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

      <?php wc_cart_totals_shipping_html(); ?>

      <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

    <?php endif; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
      <tr class="fee">
        <th><?php echo esc_html( $fee->name ); ?></th>
        <td><?php wc_cart_totals_fee_html( $fee ); ?></td>
      </tr>
    <?php endforeach; ?>

    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
      <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
        <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
          <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <th><?php echo esc_html( $tax->label ); ?></th>
            <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr class="tax-total">
          <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
          <td><?php wc_cart_totals_taxes_total_html(); ?></td>
        </tr>
      <?php endif; ?>
    <?php endif; ?>

    <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

    <tr class="order-total">
      <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
      <td><?php wc_cart_totals_order_total_html(); ?></td>
    </tr>

    <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

  </tfoot>
</table>
</div>







  <div id="payment" class="woocommerce-checkout-payment">
  <?php if ( WC()->cart->needs_payment() ) : ?>
    <ul class="wc_payment_methods payment_methods methods">
      <?php
      if ( ! empty( $available_gateways ) ) {
        foreach ( $available_gateways as $gateway ) { ?>

          <li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
  <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

  <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
    <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
  </label>
  <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
    <div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
      <?php $gateway->payment_fields(); ?>
    </div>
  <?php endif; ?>
</li>
         
        <?php }
      } else {
        echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
      }
      ?>
    </ul>
  <?php endif; ?>
  <div class="form-row place-order">
    <noscript>
      <?php
      /* translators: $1 and $2 opening and closing emphasis tags respectively */
      printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
      ?>
      <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
    </noscript>

    <?php wc_get_template( 'checkout/terms.php' ); ?>

    <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

    <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

    <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
  </div>
</div>
</form>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>


  <?php do_action( 'woocommerce_checkout_after_order_review' );
  $html_export .=ob_get_clean();

$html_export .='</div>';


$html_export .='</div>';





return $html_export;

} 





add_shortcode('woofood_one_page', 'woofood_one_page');








?>