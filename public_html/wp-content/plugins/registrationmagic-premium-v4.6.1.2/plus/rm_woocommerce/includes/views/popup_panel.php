<?php 

?>

 <div class="dbfl" id="rmwc-pu-cart-panel">

	<?php if ( WC()->cart && !WC()->cart->is_empty() ) :?>

		<?php    $i = 0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                $i++;
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail         = wp_get_attachment_image_src( get_post_thumbnail_id( $cart_item['product_id'] ));//apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				
                                        ?>
                                        <div class="rmwc-cart-item <?php echo strtolower($data->fab_theme);?>" id="rmwc-cart-item-<?php echo $i;?>">			
                                                
                                                <div class="rmwc-cart-panel-item-thumb">
						<?php if ( $_product->is_visible() ) : 
                                                            if($thumbnail)
                                                                $src = $thumbnail[0];
                                                            else 
                                                                $src = ""; //Put default thumbnail image src here. 
                                                            ?>							
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<img src=<?php echo $src;?> class="">
                                                        </a>
						<?php endif; ?>
                                                </div>
                                                <div class="rmwc-cart-panel-item-details">
                                                    
                                                    <div class="rmwc-cart-item-detail-row">
                                                        <a href='<?php esc_url($product_permalink); ?>'>
                                                            <?php echo $product_name; ?></a>
                                                    </div>
                                                    <div class="rmwc-cart-item-detail-row rmwc-cart-panel-item-meta">
                                                    <?php echo WC()->cart->get_item_data( $cart_item ); ?>
                                                    </div>

						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<div class="rmwc-cart-item-detail-row rmwc-cart-panel-item-quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</div>', $cart_item, $cart_item_key ); ?>
                                                <?php
                                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                                                '<a href="%s" class="rmwc-cart-item-detail-row rmwc-cart-panel-item-remove" title="%s" data-product_id="%s" data-product_sku="%s">'.__('Remove','registrationmagic-gold').'</a>',
                                                                esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
                                                                __( 'Remove this item', 'registrationmagic-gold' ),
                                                                esc_attr( $product_id ),
                                                                esc_attr( $_product->get_sku() )
                                                        ), $cart_item_key );
                                                        ?>
                                                </div>
                                        </div>
					<?php
				}
			}
		?>

	<?php else : ?>

		<div class="rmwc-empty-cart-panel"><?php echo RM_WC_UI_Strings::get('LABEL_CART_EMPTY'); ?></div>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
			<?php echo RM_WC_UI_Strings::get('LABEL_GO_SHOP'); ?>
                </a>

	<?php endif; ?>

<!-- end product list -->

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<div class="rmwc-cart-total rmwc-cart-item <?php echo strtolower($data->fab_theme);?>"><span class="rmwc-cart-subtotal-caption"><?php _e( 'Subtotal', 'registrationmagic-gold' ); ?>: </span><?php echo WC()->cart->get_cart_subtotal(); ?></div>


	<div class="rmwc-cart-button">
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="rmwc-cart-action-button"><?php _e( 'View Cart', 'registrationmagic-gold' ); ?></a>
		<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="rmwc-cart-action-button"><?php _e( 'Checkout', 'registrationmagic-gold' ); ?></a>
	</div>

<?php endif; ?>

</div>

<?php
//Do not re-add styles and scripts if it is an ajax request.
if(property_exists($data, 'ajax_request'))
        return;
?>
     <style>
         #rmwc-pu-cart-panel .rmwc-cart-item:after{ content: ""; display: block; clear: both;}
         #rmwc-pu-cart-panel .rmwc-cart-item {
            
            margin-bottom: 20px;
            border-radius: 5px;
            overflow: hidden;
            height: 153px;
            display: table;
            width: 100%;
         } 
         #rmwc-pu-cart-panel .rmwc-cart-item.dark{background: #323232;}
         #rmwc-pu-cart-panel .rmwc-cart-item.light{background: #fff;}
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-thumb,
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-details { width: 50%; display: table-cell; float: left; height: 153px}
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-thumb { padding: 10px;}
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-details{ padding: 10px 10px 10px 0px; position: relative;}
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-details > a { 
             position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 12px;
            color: #e86565;
            text-decoration: underline;
         }
         #rmwc-pu-cart-panel .rmwc-cart-panel-item-details > a:hover { text-decoration: none;}
         #rmwc-pu-cart-panel .rmwc-cart-item.rmwc-cart-total {
            height: auto;
            padding: 5px 10px;
            text-align: right;
         }
         .rmwc-cart-button { text-align: center; }
         .rmwc-cart-button > a {
            padding: 10px 10px;
            text-transform: uppercase;
        }
        #rmwc-pu-cart-panel .rmwc-cart-item.rmwc-cart-total .rmwc-cart-subtotal-caption { float: left;}
    </style>
    
   
