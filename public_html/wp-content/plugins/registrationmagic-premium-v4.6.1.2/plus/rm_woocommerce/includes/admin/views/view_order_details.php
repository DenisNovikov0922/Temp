<?php
//var_dump($data->customer_notes);

?>

<div class="rm-order-details">
            <!-- -->
            <table class="rm-woo-com-table">
                <tbody>
                    <tr>
                        <th></th>
                        <th><?php echo RM_WC_UI_Strings::get('LABEL_PRODUCT_NAME'); ?></th>
                        <th><?php echo RM_WC_UI_Strings::get('LABEL_COST'); ?></th>
                        <th><?php echo RM_WC_UI_Strings::get('LABEL_QUANTITY'); ?></th>
                        <th><?php echo RM_WC_UI_Strings::get('LABEL_TOTAL'); ?></th>
                    </tr>
                    <!--********************************-->
<?php foreach($data->products as $product){ ?>
                    <tr>
                        <td>
                            <div class="tableimg-product">
                                <?php if($product->img)
                                        $src = $product->img[0];
                                    else 
                                        $src = ""; //Put default thumbnail image src here. 
                                    ?>
                                <img src=<?php echo $src;?> class="">
                            </div>
                        </td>
                        <td class="product-name"><a href="<?php echo get_edit_post_link($product->id,''); ?>"><?php echo $product->name; ?></td>
                        <td><?php echo $product->cost; ?></td>
                        <td><?php echo $product->qty; ?></td>
                        <td><?php echo $product->line_subtotal; ?></td>
                    </tr>                    
<?php } ?>
                    <tr>
                        <td colspan="2">
                            <div class="difl rm-coupon-wrapper">
                                <div class="rm-coupon-used">
                                    <p><?php echo RM_WC_UI_Strings::get('LABEL_COUPONS_USED'); ?></p>
<?php foreach($data->coupons as $id=>$coupon){ ?>
                                    <p><a href="<?php echo get_edit_post_link($id,''); ?>"><?php echo $coupon; ?></a></p>     
<?php } ?>                                    
                                </div>
                            </div>
                        </td>
                        <td colspan="3">	
                            <div class="difl rm-order-total-wrapper">
                                <table class="rm-order-totals">
                                    <tbody>
                                        <tr>
                                            <td class="til"><?php echo RM_WC_UI_Strings::get('LABEL_SUBTOTAL'); ?></td><td class="tir"><?php echo $data->subtotal; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="til"><?php echo RM_WC_UI_Strings::get('LABEL_DISCOUNT'); ?></td><td class="tir"><?php echo $data->discount; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="til"><?php echo RM_WC_UI_Strings::get('LABEL_SHIPPING'); ?></td><td class="tir"><?php echo $data->shipping_cost; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="til"><?php echo RM_WC_UI_Strings::get('LABEL_ORDER_TOTAL'); ?></td><td class="tir"><?php echo $data->total; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- -->        							
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="5">
                            <div class="rm-shipping-address inline">
                                <h4><?php echo RM_WC_UI_Strings::get('LABEL_BILLING_ADDRESS'); ?></h4>
                                <p><?php echo $data->billing_address; ?></p>                                
                            </div>

                            <div class="rm-shipping-address inline">
                                <h4><?php echo RM_WC_UI_Strings::get('LABEL_SHIPPING_ADDRESS'); ?></h4>
                                <p><?php echo $data->shipping_address; ?></p>      
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="5">
                                <h4><?php echo RM_WC_UI_Strings::get('LABEL_ORDER_NOTES'); ?></h4>
                                <?php foreach($data->order_notes as $note): ?>
                                <div class="rmwc-order-note">
                                    <p><?php echo $note->content; ?></p>
                                    <span><?php printf(RM_WC_UI_Strings::get('LABEL_ORDER_NOTE_FOOTER'),$note->author,$note->date);?></span>
                                </div> 
                                <?php endforeach; ?>
                        </td>
                    </tr>

                </tbody>

            </table>
            </div>
<div id="id_prodigy_dismiss">
    <table>
        <tr>
            <td>&nbsp;</td>
            <td><span id="id_prodigy_dismiss_button" onclick="jQuery('#id_prodigy').hide()"><?php _e('Close', 'registrationmagic-gold'); ?></span></td>
            <td class="rmwc-cell-text-right"><a href="<?php echo $data->edit_url; ?>"><?php _e('Edit Order', 'registrationmagic-gold'); ?> &#8594;</a></td>
        </tr>
    </table>      
</div>

<style type="text/css">
        .rmagic table.rm-woo-com-table img { width: 70px !important; height:70px !important;}
        .product-name { width: 250px;}
        .tir { text-align: right;}
        .til { text-align: left;}
        .rm-coupon-wrapper, .rm-order-total-wrapper { width: 100%;}
        .rm-order-totals { width: 100% !important;}
        .rm-order-total-wrapper table tr, rm-order-total-wrapper table td { border: none; background:initial; }
        .rm-shipping-address { width: 50%;}
        .rm-shipping-address p, .rm-shipping-address h4 { margin: 0px;}
        .inline { display: inline-block; float: left;}
        .rmagic .rm-coupon-wrapper .rm-coupon-used p { display: block;}
        .rmagic .rm-shipping-address p br{ display: block !important;}
        #id_prodigy_dismiss {
            text-align: center;
            background: #ffffff;
            border-top: 1px solid #e1e1e1;
            line-height: 30px;
            text-transform: uppercase;
        }
        #id_prodigy_dismiss_button {
            display: block;
            width: 100px;
            margin: 10px auto;
            background: #ff6c6c;
            color: #FFF;
            cursor: pointer;
            font-size: 12px; 
            border-radius: 4px;
            text-align: center;
        }
        #id_prodigy_dismiss_button:hover {background: #d96666;}
        .rmagic .rm-woo-com-table tr:nth-last-child(1) { border-bottom-width: 0px;}
        #id_prodigy_dismiss, #id_prodigy_dismiss table { margin: 0px;}
       
        #id_prodigy_dismiss td {
            width: 33.33%;
            padding: 0px;
            vertical-align: middle;
        }
        #id_prodigy_dismiss td.rmwc-cell-text-right { text-align: right; padding-right: 30px; }
        .rmwc-order-note{
            margin-bottom: 10px;
            display: block !important;
            background: #FFF;
            padding: 10px;
            border-radius: 5px;
        }
        .rmwc-order-note p{
            display: block;
            font-size: 12px;
            white-space: normal;
            margin: 0;
        }
        .rmwc-order-note span{
            font-size: 10px;
            font-style: italic;
            display: block;
            color: #999;
            text-align: right;
        }
</style>


            <!-- -->
        